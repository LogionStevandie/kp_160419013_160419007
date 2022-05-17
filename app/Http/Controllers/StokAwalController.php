<?php

namespace App\Http\Controllers;

use App\Models\StokAwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            ->select('StokAwal.*','Item.ItemName as itemName', 'Unit.Name as unitName','MGudang.cname as gudangName')
            ->leftjoin('Item','StokAwal.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','Item.UnitID','=','Unit.UnitID')
            ->leftjoin('MGudang','StokAwal.MGudangID','=','MGudang.MGudangID')
            ->where('StokAwal.hapus',0)
            ->orderByDesc('StokAwal.tanggalDibuat')
            ->paginate(10);
        //dd($data);
        return view('master.note.stokAwal.index',[
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
        
        return view('master.note.stokAwal.tambah',[
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'dataGudang' => $dataGudang,
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

        $datast = DB::table('StokAwal')
            ->where('name', 'like', 'STAW/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/%")
            ->get();

        $totalIndex = str_pad(strval(count($datast) + 1),4,'0',STR_PAD_LEFT);

        $idStokAwal = DB::table('StokAwal')->insertGetId(array(
            'name' => 'STAW/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'ItemID' => $data['ItemID'],  
            'tanggalDibuat' => $data['tanggalDibuat'],  
            'jumlah' => $data['jumlah'],  
            'MGudangID' => $data['MGudangID'],  
            'keterangan' => $data['keterangan'],  
            'hapus' => 0,  
            'CreatedBy'=> $user->id,
            'CreatedOn'=> date("Y-m-d h:i:sa"),
            'UpdatedBy'=> $user->id,
            'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $idItemInventoryTransaction = DB::table('ItemInventoryTransaction')->insertGetId(array(
            'Name' => 'STAW/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'Description' =>  $data['keterangan'],
            'Date' => $data['tanggalDibuat'],  
            'EmployeeID' => $user->id,  
            'MGudangID' => $data['MGudangID'],  
            'StokAwalID' => $idStokAwal,  
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
                //'UnitPrice' => $data['itemHarga'],  
                'Quantity' => $data['jumlah'],  
                //'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
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
        $dataGudang = DB::table('MGudang')
            ->get();
        
        return view('master.note.stokAwal.detail',[
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'stokAwal' => $stokAwal,
            'dataGudang' => $dataGudang,
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
        $dataGudang = DB::table('MGudang')
            ->get();
        
        return view('master.note.stokAwal.edit',[
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'stokAwal' => $stokAwal,
            'dataGudang' => $dataGudang,
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
            
        DB::table('StokAwal')
            ->where('id', $stokAwal->id)
            ->update(array(
                'ItemID' => $data['ItemID'],  
                'tanggalDibuat' => $data['tanggalDibuat'],  
                'jumlah' => $data['jumlah'],  
                'MGudangID' => $data['MGudangID'],  
                'keterangan' => $data['keterangan'],  
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        DB::table('ItemInventoryTransaction')
            ->where('StokAwalID', $stokAwal->id)
            ->update(array(
                'Description' =>  $data['keterangan'],
                'Date' => $data['tanggalDibuat'],  
                'EmployeeID' => $user->id,  
                'MGudangID' => $data['MGudangID'],  
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        $idIIT = DB::table('ItemInventoryTransaction')->select('TransactionID')->where('StokAwalID', $stokAwal->id)->get();
        
        DB::table('ItemInventoryTransactionLine')
            ->where('TransactionID', $idIIT[0]->TransactionID)
            ->update(array(
                'ItemID' => $data['ItemID'],  
                'MGudangID' => $data['MGudangID'],  
                //'UnitPrice' => $data['itemHarga'],  
                'Quantity' => $data['jumlah'],  
                //'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
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
        DB::table('StokAwal')
            ->where('id', $stokAwal['id'])
            ->update(array(
                'hapus' => 1,  
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $idIIT = DB::table('ItemInventoryTransaction')->select('TransactionID')->where('StokAwalID', $stokAwal['id'])->get();
        DB::table('ItemInventoryTransaction')
            ->where('StokAwalID', $stokAwal['id'])
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
            ->select('StokAwal.*','Item.ItemName as itemName', 'Unit.Name as unitName','MGudang.cname as gudangName')
            ->leftjoin('Item','StokAwal.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','Item.UnitID','=','Unit.UnitID')
            ->leftjoin('MGudang','StokAwal.MGudangID','=','MGudang.MGudangID')
            ->where('StokAwal.hapus',0)
            ->where('StokAwal.name','like','%'.$name.'%')
            ->orderByDesc('StokAwal.tanggalDibuat')
            ->paginate(10);
        
        return view('master.note.stokAwal.index',[
            'data' => $data,
        ]);
    }

    public function searchStokAwalDate(Request $request)
    {
        $date = $request->input('searchdate');
        $date = explode("-", $date);
        $user = Auth::user();
        $data = DB::table('StokAwal')
            ->select('StokAwal.*','Item.ItemName as itemName', 'Unit.Name as unitName','MGudang.cname as gudangName')
            ->leftjoin('Item','StokAwal.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','Item.UnitID','=','Unit.UnitID')
            ->leftjoin('MGudang','StokAwal.MGudangID','=','MGudang.MGudangID')
            ->where('StokAwal.hapus',0)
            ->whereBetween('StokAwal.tanggalDibuat',[$date[0], $date[1]])
            ->orderByDesc('StokAwal.tanggalDibuat')
            ->paginate(10);
        
        return view('master.note.stokAwal.index',[
            'data' => $data,
        ]);

    }

    public function searchStokAwalNameDate(Request $request)
    {
        $name = $request->input('searchname');
        $date = $request->input('searchdate');
        $date = explode("-", $date);
        $user = Auth::user();
        $data = DB::table('StokAwal')
            ->select('StokAwal.*','Item.ItemName as itemName', 'Unit.Name as unitName','MGudang.cname as gudangName')
            ->leftjoin('Item','StokAwal.ItemID','=','Item.ItemID')
            ->leftjoin('Unit','Item.UnitID','=','Unit.UnitID')
            ->leftjoin('MGudang','StokAwal.MGudangID','=','MGudang.MGudangID')
            ->where('StokAwal.hapus',0)
            ->where('StokAwal.name','like','%'.$name.'%')
            ->whereBetween('StokAwal.tanggalDibuat',[$date[0], $date[1]])
            ->orderByDesc('StokAwal.tanggalDibuat')
            ->paginate(10);
        
        return view('master.note.stokAwal.index',[
            'data' => $data,
        ]);

    }

    public function print(StokAwal $stokAwal)
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

        $dataGudang =DB::table('MGudang')
            ->select('MGudang.*','MPerusahaan.cname as perusahaanName','MPerusahaan.Gambar as perusahaanGambar')
            ->join('MPerusahaan','MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->get();  
        
        return view('master.note.stokAwal.detail',[
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'stokAwal' => $stokAwal,
            'dataGudang' => $dataGudang,
        ]);
    }
}
