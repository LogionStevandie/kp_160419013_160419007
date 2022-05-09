<?php

namespace App\Http\Controllers;

use App\Models\AdjustmentStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdjustmentStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = DB::table('ItemAdjustment')
            ->select('ItemAdjustment.*','ItemAdjustmentDetail.*','Item.ItemName as itemName', 'Unit.Name as unitName','MGudang.cname as gudangName')
            ->join('ItemAdjustmentDetail','ItemAdjustment.ItemAdjustmentID','=','ItemAdjustmentDetail.ItemAdjustmentID')
            ->leftjoin('Item','ItemAdjustmentDetail.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','ItemAdjustmentDetail.UnitID','=','Unit.UnitID')
            ->leftjoin('MGudang','ItemAdjustmentDetail.MGudangID','=','MGudang.MGudangID')
            ->where('ItemAdjustment.Deleted',0)
            ->paginate(10);
        return view('master.note.adjustmentStok.index',[
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $dataBarang = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName')
            ->join('Unit','Item.UnitID', '=', 'Unit.UnitID')
            ->leftjoin('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus',0)
            ->get();

        $dataBarangTag = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName','ItemTagValues.ItemTagID')
            ->join('Unit','Item.UnitID', '=', 'Unit.UnitID')
            ->join('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus',0)
            ->get();
        //dd($dataBarangTag);
        $dataTag = DB::table('ItemTag')
            ->get();

        $dataGudang = DB::table('MGudang')
            ->get();
        
        $dataReport = DB::table('ItemInventoryTransactionLine')//dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select('MGudang.cname as gudangName','ItemInventoryTransactionLine.MGudangID','ItemInventoryTransactionLine.ItemID','Item.ItemName', 
            DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity"))
            ->join('MGudang','ItemInventoryTransactionLine.MGudangID','=','MGudang.MGudangID')
            ->join('Item','ItemInventoryTransactionLine.ItemID','=','Item.ItemID')
            ->groupBy('ItemInventoryTransactionLine.MGudangID','MGudang.cname','ItemInventoryTransactionLine.ItemID','Item.ItemName')
            ->paginate(10);
        
        return view('master.note.adjustmentStok.tambah',[
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'dataGudang' => $dataGudang,
            'dataReport' => $dataReport,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = Auth::user();
        $data = $request->collect();
        $year = date("Y");
        $month = date("m");

        $dataLokasi = DB::table('MGudang')
            ->select('MKota.*','MPerusahaan.cnames as perusahaanCode')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MGudang.MGudangID', '=', $data['MGudangID'])
            ->get();

        $datast = DB::table('ItemAdjustment')
            ->where('name', 'like', 'AS/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/%")
            ->get();

        $totalIndex = str_pad(strval(count($datast) + 1),4,'0',STR_PAD_LEFT);

        $idAs = DB::table('ItemAdjustment')->insertGetId(array(
            'Name' => 'AS/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'Tanggal' => $data['tanggalDibuat'],  
            'Description' => $data['keterangan'],  
            'Deleted' => 0,  
            'CreatedBy'=> $user->id,
            'CreatedOn'=> date("Y-m-d h:i:sa"),
            'UpdatedBy'=> $user->id,
            'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $jumlahPerubahan = float($data['QuantityBaru']) - float($data['QuantityAwal']);
        DB::table('ItemAdjustmentDetail')->insert(array(
            'ItemAdjustmentID' => $idAs,
            'ItemID' => $data['ItemID'],  
            'MGudangID' => $data['MGudangID'],  
            'QuantityAwal' => $data['QuantityAwal'],
            'QuantityBaru' => $data['QuantityBaru'],
            'Selisih' => $jumlahPerubahan,
            )
        );

        $idItemInventoryTransaction = DB::table('ItemInventoryTransaction')->insertGetId(array(
            'Name' => 'AS/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'Description' =>  $data['keterangan'],
            'Date' => $data['tanggalDibuat'],  
            'EmployeeID' => $user->id,  
            'MGudangID' => $data['MGudangID'],  
            'AdjustmentID' => $idAs,  
            'CreatedBy'=> $user->id,
            'CreatedOn'=> date("Y-m-d h:i:sa"),
            'UpdatedBy'=> $user->id,
            'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        
        DB::table('ItemInventoryTransactionLine')
            ->insert(array(
                'TransactionID' => $idItemInventoryTransaction,  
                'ItemID' => $data['ItemID'],  
                'MGudangID' => $data['MGudangID'],  
                'Quantity' => $jumlahPerubahan,  
                //'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
            )
        );

        return redirect()->route('adjustmentStock.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AdjustmentStock  $adjustmentStock
     * @return \Illuminate\Http\Response
     */
    public function show(AdjustmentStock $adjustmentStock)
    {
        //
        $data = DB::table('ItemAdjustment')
            ->join('ItemAdjustmentDetail','ItemAdjustment.ItemAdjustmentID','=','ItemAdjustmentDetail.ItemAdjustmentID')
            ->leftjoin('Item','ItemAdjustmentDetail.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','ItemAdjustmentDetail.UnitID','=','Unit.UnitID')
            ->leftjoin('MGudang','ItemAdjustmentDetail.MGudangID','=','MGudang.MGudangID')
            ->where('ItemAdjustment.Deleted',0)
            ->paginate(10);
        return view('master.note.adjustmentStok.detail',[
            'data' => $data,
            'adjustmentStock' => $adjustmentStock,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdjustmentStock  $adjustmentStock
     * @return \Illuminate\Http\Response
     */
    public function edit(AdjustmentStock $adjustmentStock)
    {
        //
        $dataBarang = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName')
            ->join('Unit','Item.UnitID', '=', 'Unit.UnitID')
            ->leftjoin('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus',0)
            ->get();

        $dataBarangTag = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName','ItemTagValues.ItemTagID')
            ->join('Unit','Item.UnitID', '=', 'Unit.UnitID')
            ->join('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus',0)
            ->get();
        //dd($dataBarangTag);
        $dataTag = DB::table('ItemTag')
            ->get();

        $dataGudang = DB::table('MGudang')
            ->get();
        
        $dataReport = DB::table('ItemInventoryTransactionLine')//dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select('MGudang.cname as gudangName','ItemInventoryTransactionLine.MGudangID','ItemInventoryTransactionLine.ItemID','Item.ItemName', 
            DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity"))
            ->join('MGudang','ItemInventoryTransactionLine.MGudangID','=','MGudang.MGudangID')
            ->join('Item','ItemInventoryTransactionLine.ItemID','=','Item.ItemID')
            ->whereNot('AdjustmentID', $adjustmentStock['ItemAdjustmentID'])
            ->groupBy('ItemInventoryTransactionLine.MGudangID','MGudang.cname','ItemInventoryTransactionLine.ItemID','Item.ItemName')
            ->paginate(10);
        
        return view('master.note.adjustmentStok.edit',[
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'dataGudang' => $dataGudang,
            'dataReport' => $dataReport,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AdjustmentStock  $adjustmentStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdjustmentStock $adjustmentStock)
    {
        //
        $user = Auth::user();
        $data = $request->collect();
        $year = date("Y");
        $month = date("m");

        DB::table('ItemAdjustment')
            ->where('ItemAdjustmentID', $adjustmentStock['ItemAdjustmentID'])
            ->update(array(
                'Tanggal' => $data['tanggalDibuat'],  
                'Description' => $data['keterangan'],  
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $jumlahPerubahan = float($data['QuantityBaru']) - float($data['QuantityAwal']);
        DB::table('ItemAdjustmentDetail')
            ->where('ItemAdjustmentID',$adjustmentStock['ItemAdjustmentID'])
            ->update(array(
            'ItemID' => $data['ItemID'],  
            'MGudangID' => $data['MGudangID'],  
            'QuantityAwal' => $data['QuantityAwal'],
            'QuantityBaru' => $data['QuantityBaru'],
            'Selisih' => $jumlahPerubahan,
            )
        );

        DB::table('ItemInventoryTransaction')
            ->where('AdjustmentID',$adjustmentStock['ItemAdjustmentID'])
            ->insertGetId(array(
            'Description' =>  $data['keterangan'],
            'Date' => $data['tanggalDibuat'],  
            'EmployeeID' => $user->id,  
            'MGudangID' => $data['MGudangID'],  
            'UpdatedBy'=> $user->id,
            'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        
        $dataIIT = DB::table('ItemInventoryTransaction')
            ->select('ItemInventoryTransaction.TransactionID')
            ->where('AdjustmentID',$adjustmentStock['ItemAdjustmentID'])
            ->get();

        DB::table('ItemInventoryTransactionLine')
            ->where('TransactionID', $dataIIT->TransactionID)
            ->update(array(
                'TransactionID' => $idItemInventoryTransaction,  
                'ItemID' => $data['ItemID'],  
                'MGudangID' => $data['MGudangID'],  
                'Quantity' => $jumlahPerubahan,  
                //'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
            )
        );

        return redirect()->route('adjustmentStock.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AdjustmentStock  $adjustmentStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdjustmentStock $adjustmentStock)
    {
        //
        $user = Auth::user();
        DB::table('ItemAdjustment')
            ->where('ItemAdjustmentID', $adjustmentStock['ItemAdjustmentID'])
            ->update(array(
                'Deleted' => 1,  
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $dataIIT = DB::table('ItemInventoryTransaction')
            ->select('ItemInventoryTransaction.TransactionID')
            ->where('AdjustmentID',$adjustmentStock['ItemAdjustmentID'])
            ->get();

        DB::table('ItemInventoryTransaction')
            ->where('AdjustmentID', $adjustmentStock['id'])
            ->delete();    
        
        DB::table('ItemInventoryTransactionLine')
            ->where('TransactionID', $dataIIT[0]->TransactionID)
            ->delete();

        return redirect()->route('adjustmentStock.index')->with('status','Success!!');

    }

    public function searchNameAS(Request $request)
    {
        //
        $name=$request->input('searchname');
        $user = Auth::user();

        $data = DB::table('ItemAdjustment')
            ->select('ItemAdjustment.*','ItemAdjustmentDetail.*','Item.ItemName as itemName', 'Unit.Name as unitName','MGudang.cname as gudangName')
            ->join('ItemAdjustmentDetail','ItemAdjustment.ItemAdjustmentID','=','ItemAdjustmentDetail.ItemAdjustmentID')
            ->leftjoin('Item','ItemAdjustmentDetail.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','ItemAdjustmentDetail.UnitID','=','Unit.UnitID')
            ->leftjoin('MGudang','ItemAdjustmentDetail.MGudangID','=','MGudang.MGudangID')
            ->where('ItemAdjustment.Deleted',0)
            ->where('ItemAdjustment.Name','like', '%'.$name.'%')
            ->paginate(10);
        return view('master.note.adjustmentStok.index',[
            'data' => $data,
        ]);
    }

    public function searchDateAS(Request $request)
    {
        //
        $date=$request->input('dateRangeSearch');
        $user = Auth::user();
        $date = explode("-", $date);

        $data = DB::table('ItemAdjustment')
            ->select('ItemAdjustment.*','ItemAdjustmentDetail.*','Item.ItemName as itemName', 'Unit.Name as unitName','MGudang.cname as gudangName')
            ->join('ItemAdjustmentDetail','ItemAdjustment.ItemAdjustmentID','=','ItemAdjustmentDetail.ItemAdjustmentID')
            ->leftjoin('Item','ItemAdjustmentDetail.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','ItemAdjustmentDetail.UnitID','=','Unit.UnitID')
            ->leftjoin('MGudang','ItemAdjustmentDetail.MGudangID','=','MGudang.MGudangID')
            ->where('ItemAdjustment.Deleted',0)
            ->whereBetween('ItemAdjustment.Tanggal',[date($date[0]), date($date[1])])
            ->paginate(10);
        return view('master.note.adjustmentStok.index',[
            'data' => $data,
        ]);
    }

    public function searchNameDateAS(Request $request)
    {
        //
        $date=$request->input('dateRangeSearch');
        $user = Auth::user();
        $date = explode("-", $date);

        $data = DB::table('ItemAdjustment')
            ->select('ItemAdjustment.*','ItemAdjustmentDetail.*','Item.ItemName as itemName', 'Unit.Name as unitName','MGudang.cname as gudangName')
            ->join('ItemAdjustmentDetail','ItemAdjustment.ItemAdjustmentID','=','ItemAdjustmentDetail.ItemAdjustmentID')
            ->leftjoin('Item','ItemAdjustmentDetail.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','ItemAdjustmentDetail.UnitID','=','Unit.UnitID')
            ->leftjoin('MGudang','ItemAdjustmentDetail.MGudangID','=','MGudang.MGudangID')
            ->where('ItemAdjustment.Deleted',0)
            ->where('ItemAdjustment.Name','like', '%'.$name.'%')
            ->whereBetween('ItemAdjustment.Tanggal',[date($date[0]), date($date[1])])
            ->paginate(10);
        return view('master.note.adjustmentStok.index',[
            'data' => $data,
        ]);
    }
}
