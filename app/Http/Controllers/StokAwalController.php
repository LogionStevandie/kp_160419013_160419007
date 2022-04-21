<?php

namespace App\Http\Controllers;

use App\Models\StokAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class StokAwalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = DB::table('StokAwal')
            ->select('StokAwal.*','Item.ItemName as itemName', 'Unit.Name as unitName')
            ->leftjoin('Item','StokAwal.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('hapus',0)
            ->paginate(10);
        
        return view('nota.StokAwal.index',[
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
        
        return view('nota.StokAwal.index',[
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
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
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $dataLokasiPerusahaan = DB::table('MPerusahaan')
            ->where("MPerusahaanID", $data['perusahaan'])
            ->get();

        $dataSj = DB::table('surat_jalan')
            ->where('name', 'like', 'STAW/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/%")
            ->get();

        $totalIndex = str_pad(strval(count($dataPo) + 1),4,'0',STR_PAD_LEFT);

        $idStokAwal = DB::table('surat_jalan')->insertGetId(array(
            'name' => 'STAW/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'ItemID' => $data['ItemID'],  
            'tanggalDibuat' => $data['tanggalDibuat'],  
            'jumlah' => $data['jumlah'],  
            'MGudangID' => $data['MGudangID'],  
            'keterangan' => $data['keterangan'],  
            'hapus' => 0,  
            'created_by'=> $user->id,
            'created_on'=> date("Y-m-d h:i:sa"),
            'updated_by'=> $user->id,
            'updated_on'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $idItemInventoryTransaction = DB::table('ItemInventoryTransaction')->insertGetId(array(
            'Name' => 'STAW/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'Description' =>  $data['keterangan'],
            'Date' => $data['tanggalDibuat'],  
            'EmployeeID' => $user->id,  
            'MGudangID' => $data['MGudangID'],  
            'StokAwalID' => $idStokAwal,  
            'created_by'=> $user->id,
            'created_on'=> date("Y-m-d h:i:sa"),
            'updated_by'=> $user->id,
            'updated_on'=> date("Y-m-d h:i:sa"),
            )
        );
        
        DB::table('ItemInventoryTransactionLine')
            ->insert(array(
                'TransactionID' => $idItemInventoryTransaction,  
                'ItemID' => $data['ItemID'],  
                'MGudangID' => $data['MGudangID'],  
                'UnitPrice' => $data['itemHarga'],  
                'Quantity' => $data['itemJumlah'],  
                'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
            )
        );

        return redirect()->route('stokAwal.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\StokAwal  $stokAwal
     * @return \Illuminate\Http\Response
     */
    public function show(StokAwal $stokAwal)
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
        
        return view('nota.StokAwal.detail',[
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'stokAwal' => $stokAwal,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\StokAwal  $stokAwal
     * @return \Illuminate\Http\Response
     */
    public function edit(StokAwal $stokAwal)
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
        
        return view('nota.StokAwal.detail',[
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'stokAwal' => $stokAwal,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StokAwal  $stokAwal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StokAwal $stokAwal)
    {
        //
        $user = Auth::user();
        $data = $request->collect();
            
        DB::table('surat_jalan')
            ->where('id', $stokAwal->id)
            ->update(array(
                'ItemID' => $data['ItemID'],  
                'tanggalDibuat' => $data['tanggalDibuat'],  
                'jumlah' => $data['jumlah'],  
                'MGudangID' => $data['MGudangID'],  
                'keterangan' => $data['keterangan'],  
                'updated_by'=> $user->id,
                'updated_on'=> date("Y-m-d h:i:sa"),
            )
        ); 

        DB::table('ItemInventoryTransaction')
            ->where('StokAwalID', $stokAwal->id)
            ->update(array(
                'Description' =>  $data['keterangan'],
                'Date' => $data['tanggalDibuat'],  
                'EmployeeID' => $user->id,  
                'MGudangID' => $data['MGudangID'],  
                'updated_by'=> $user->id,
                'updated_on'=> date("Y-m-d h:i:sa"),
            )
        );
        $idIIT = DB::table('ItemInventoryTransaction')->select('TransactionID')->where('StokAwalID', $stokAwal->id)->get();
        
        DB::table('ItemInventoryTransactionLine')
            ->where('TransactionID', $idIIT[0]->TransactionID)
            ->update(array(
                'ItemID' => $data['ItemID'],  
                'MGudangID' => $data['MGudangID'],  
                'UnitPrice' => $data['itemHarga'],  
                'Quantity' => $data['itemJumlah'],  
                'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
            )
        );
        return redirect()->route('stokAwal.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\StokAwal  $stokAwal
     * @return \Illuminate\Http\Response
     */
    public function destroy(StokAwal $stokAwal)
    {
        //
        $user = Auth::user();
        DB::table('surat_jalan')
            ->where('id', $stokAwal->id)
            ->update(array(
                'hapus' => '11',  
                'updated_by'=> $user->id,
                'updated_on'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $idIIT = DB::table('ItemInventoryTransaction')->select('TransactionID')->where('StokAwalID', $stokAwal->id)->get();
        DB::table('ItemInventoryTransaction')
            ->where('StokAwalID', $stokAwal->id)
            ->delete();    
        
        DB::table('ItemInventoryTransactionLine')
            ->where('TransactionID', $idIIT[0]->TransactionID)
            ->delete();

        return redirect()->route('stokAwal.index')->with('status','Success!!');
    }

    public function searchStokAwalName(Request $request)
    {
        $name = $request->input('searchname');
        $user = Auth::user();
        $data = DB::table('StokAwal')
            ->select('StokAwal.*','Item.ItemName as itemName', 'Unit.Name as unitName')
            ->leftjoin('Item','StokAwal.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('StokAwal.hapus',0)
            ->where('StokAwal.name','like','%'.$name.'%')
            ->paginate(10);
        
        return view('nota.StokAwal.index',[
            'data' => $data,
        ]);
    }

    public function searchStokAwalDate(Request $request)
    {
        $date = $request->input('searchdate');
        $date = explode("-", $date);
        $user = Auth::user();
        $data = DB::table('StokAwal')
            ->select('StokAwal.*','Item.ItemName as itemName', 'Unit.Name as unitName')
            ->leftjoin('Item','StokAwal.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('StokAwal.hapus',0)
            ->whereBetween('StokAwal.tanggalDibuat',[$date[0], $date[1]])
            ->paginate(10);
        
        return view('nota.StokAwal.index',[
            'data' => $data,
        ]);

    }
}
