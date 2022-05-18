<?php

namespace App\Http\Controllers;

use App\Models\SuratJalan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SuratJalanController extends Controller
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
        $data = DB::table('surat_jalan')
            ->where('hapus',0)
            ->orderByDesc('surat_jalan.tanggalDibuat')
            ->paginate(10);

        $dataDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*')
            ->join('surat_jalan','surat_jalan_detail.suratJalanID','=','surat_jalan.id')
            ->where('surat_jalan.hapus',0)
            ->orderByDesc('surat_jalan.tanggalDibuat')
            ->paginate(10);

        $dataGudang =DB::table('MGudang')
            ->get();  
        return view('master.note.suratJalan.index',[
            'data' => $data,
            'dataDetail' => $dataDetail,
            'dataGudang' => $dataGudang,
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
        $dataGudang =DB::table('MGudang')
            ->get();  


        $dataPurchaseRequestDetail = DB::table('purchase_request_detail')
            ->select('purchase_request_detail.*','purchase_request.name','Item.ItemName as ItemName','Unit.Name as UnitName')//,'purchase_order_detail.harga as hargaItem')//
            ->join('purchase_request', 'purchase_request_detail.idPurchaseRequest', '=','purchase_request.id')
            //->join('purchase_order_detail', 'purchase_request_detail.id', '=','purchase_order_detail.idPurchaseRequestDetail')
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->where('purchase_request_detail.jumlahProses', '<', DB::raw('purchase_request_detail.jumlah'))//errorr disini
            ->get();
        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*','MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang','purchase_request.MGudangID','=','MGudang.MGudangID')
            ->join('MPerusahaan','MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();

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
         $date = date("Y-m-d");
        return view('master.note.suratJalan.tambah',[
            'dataGudang' => $dataGudang,
            'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
            'dataPurchaseRequest' => $dataPurchaseRequest,
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'date' => $date,
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
            ->where('MGudang.MGudangID', '=', $data['MGudangIDAwal'])
            ->get();
        /*$dataLokasiPerusahaan = DB::table('MPerusahaan')
            //->where("MPerusahaanID", $data['perusahaan'])
            ->where("MPerusahaanID", $data['perusahaan'])
            ->get();*/

        $dataSj = DB::table('surat_jalan')
            ->where('name', 'like', 'SJ/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/%")
            ->get();

        $totalIndex = str_pad(strval(count($dataSj) + 1),4,'0',STR_PAD_LEFT);

        $idSuratJalan = DB::table('surat_jalan')->insertGetId(array(
            'name' => 'SJ/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'tanggalDibuat' => $data['tanggalDibuat'],  
            'keteranganKendaraan' => $data['keteranganKendaraan'],  
            'keteranganNomorPolisi' => $data['keteranganNomorPolisi'],  
            'keteranganPemudi' => $data['keteranganPemudi'],  
            'keteranganTransaksi' => $data['keteranganTransaksi'],  
            'MGudangIDAwal' => $data['MGudangIDAwal'],  
            'MGudangIDTujuan' => $data['MGudangIDTujuan'],   
            'keteranganGudangTujuan' => $data['keteranganGudangTujuan'],   
            'keteranganPenerima' => $data['keteranganPenerima'],   
            'PurchaseRequestID' => $data['PurchaseRequestID'],  
            'hapus' => 0,  
            'CreatedBy'=> $user->id,
            'CreatedOn'=> date("Y-m-d h:i:sa"),
            'UpdatedBy'=> $user->id,
            'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        for($i = 0; $i < count($data['itemId']); $i++){
            $idSuratJalanDetail = DB::table('surat_jalan_detail')->insertGetId(array(
                'suratJalanID' => $idSuratJalan,           
                'ItemID' => $data['itemId'][$i],
                'jumlah' => $data['itemJumlah'][$i],
                'keterangan' => $data['itemKeterangan'][$i],
                //'harga' => $data['itemHarga'][$i],//didapat dri hidden ketika milih barang di PO
                'PurchaseRequestDetailID' => $data['itemPRDID'][$i], //didapat dri hidden ketika milih barang di PO
                )
            ); 
        }   
        return redirect()->route('suratJalan.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SuratJalan  $suratJalan
     * @return \Illuminate\Http\Response
     */
    public function show(SuratJalan $suratJalan)
    {
        //
          $dataGudang =DB::table('MGudang')
            ->get();  


        $dataPurchaseRequestDetail = DB::table('purchase_request_detail')
            ->select('purchase_request_detail.*','purchase_request.name','Item.ItemName as ItemName','Unit.Name as UnitName')//
            ->join('purchase_request', 'purchase_request_detail.idPurchaseRequest', '=','purchase_request.id')
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->where('purchase_request_detail.jumlahProses', '<', DB::raw('purchase_request_detail.jumlah'))//errorr disini
            ->get();
        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*','MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang','purchase_request.MGudangID','=','MGudang.MGudangID')
            ->join('MPerusahaan','MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();

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

         $dataTotalDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*', 'purchase_request_detail.id as idPRD','Item.ItemName as itemName' )
            ->join('purchase_request_detail', 'surat_jalan_detail.PurchaseRequestDetailID','=','purchase_request_detail.id')
            ->join('Item', 'surat_jalan_detail.ItemID','=','Item.ItemID')
            ->where('suratJalanID', $suratJalan->id)
            ->get();
        
        return view('master.note.suratJalan.detail',[
            'dataGudang' => $dataGudang,
            'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
            'dataPurchaseRequest' => $dataPurchaseRequest,
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'suratJalan' => $suratJalan,
            'dataTotalDetail' => $dataTotalDetail,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SuratJalan  $suratJalan
     * @return \Illuminate\Http\Response
     */
    public function edit(SuratJalan $suratJalan)
    {
        //
        $dataGudang =DB::table('MGudang')
            ->get();  


        $dataPurchaseRequestDetail = DB::table('purchase_request_detail')
            ->select('purchase_request_detail.*','purchase_request.name','Item.ItemName as ItemName','Unit.Name as UnitName')//
            ->join('purchase_request', 'purchase_request_detail.idPurchaseRequest', '=','purchase_request.id')
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->where('purchase_request_detail.jumlahProses', '<', DB::raw('purchase_request_detail.jumlah'))//errorr disini
            ->get();
        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*','MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang','purchase_request.MGudangID','=','MGudang.MGudangID')
            ->join('MPerusahaan','MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();

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

         $dataTotalDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*', 'purchase_request_detail.id as idPRD','Item.ItemName as itemName' )
            ->join('purchase_request_detail', 'surat_jalan_detail.PurchaseRequestDetailID','=','purchase_request_detail.id')
            ->join('Item', 'surat_jalan_detail.ItemID','=','Item.ItemID')
            ->where('suratJalanID', $suratJalan->id)
            ->get();
        
        return view('master.note.suratJalan.edit',[
            'dataGudang' => $dataGudang,
            'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
            'dataPurchaseRequest' => $dataPurchaseRequest,
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'suratJalan' => $suratJalan,
            'dataTotalDetail' => $dataTotalDetail,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SuratJalan  $suratJalan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SuratJalan $suratJalan)
    {
        //
        $user = Auth::user();
        $data = $request->collect();

        DB::table('surat_jalan')
            ->where('id', $suratJalan->id)
            ->update(array(
                'tanggalDibuat' => $data['tanggalDibuat'],  
                'keteranganKendaraan' => $data['keteranganKendaraan'],  
                'keteranganNomorPolisi' => $data['keteranganNomorPolisi'],  
                'keteranganPemudi' => $data['keteranganPemudi'],  
                'keteranganTransaksi' => $data['keteranganTransaksi'],  
                'MGudangIDAwal' => $data['MGudangIDAwal'],  
                'MGudangIDTujuan' => $data['MGudangIDTujuan'],   
                'keteranganGudangTujuan' => $data['keteranganGudangTujuan'],   
                'keteranganPenerima' => $data['keteranganPenerima'],   
                'PurchaseRequestID' => $data['PurchaseRequestID'],  
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        DB::table('surat_jalan_detail')
            ->where('suratJalanID', $suratJalan->id)
            ->delete();
        for($i = 0; $i < count($data['itemId']); $i++){
            $idSuratJalanDetail = DB::table('surat_jalan_detail')->insertGetId(array(
                'suratJalanID' => $suratJalan->id,           
                'ItemID' => $data['itemId'][$i],
                'jumlah' => $data['itemJumlah'][$i],
                'keterangan' => $data['itemKeterangan'][$i],
                //'harga' => $data['itemHarga'][$i],//didapat dri hidden ketika milih barang di PO
                'PurchaseRequestDetailID' => $data['itemPRDID'][$i], //didapat dri hidden ketika milih barang di PO
                )
            ); 
        }   
        return redirect()->route('suratJalan.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SuratJalan  $suratJalan
     * @return \Illuminate\Http\Response
     */
    public function destroy(SuratJalan $suratJalan)
    {
      
        $user = Auth::user();
        DB::table('surat_jalan')
            ->where('id', $suratJalan->id)
            ->update(array(
                'hapus' => '1',  
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
        return redirect()->route('suratJalan.index')->with('status','Success!!');
    }

    public function searchSuratJalanName(Request $request)
    {
        $name = $request->input('searchname');
        $user = Auth::user();
        $data = DB::table('surat_jalan')
            ->where('hapus',0)
            ->where('surat_jalan.name','like','%'.$name.'%')
            ->orderByDesc('surat_jalan.tanggalDibuat')
            ->paginate(10);

        $dataDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*')
            ->join('surat_jalan','surat_jalan_detail.suratJalanID','=','surat_jalan.id')
            ->where('surat_jalan.hapus',0)
            ->where('surat_jalan.name','like','%'.$name.'%')
            ->orderByDesc('surat_jalan.tanggalDibuat')
            ->paginate(10);
        return view('master.note.suratJalan.index',[
            'data' => $data,
            'dataDetail' => $dataDetail,
        ]);
    }

    public function searchSuratJalanDate(Request $request)
    {
        $date = $request->input('searchdate');
        $date = explode("-", $date);
        $user = Auth::user();
        $data = DB::table('surat_jalan')
            ->where('hapus',0)
            ->whereBetween('surat_jalan.tanggalDibuat',[$date[0], $date[1]])
            ->orderByDesc('surat_jalan.tanggalDibuat')
            ->paginate(10);

        $dataDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*')
            ->join('surat_jalan','surat_jalan_detail.suratJalanID','=','surat_jalan.id')
            ->where('surat_jalan.hapus',0)
            ->where('surat_jalan.tanggalDibuat',[date($date[0]), date($date[1])])
            ->orderByDesc('surat_jalan.tanggalDibuat')
            ->paginate(10);
        return view('master.note.suratJalan.index',[
            'data' => $data,
            'dataDetail' => $dataDetail,
        ]);
    }

    public function searchSuratJalanNameDate(Request $request)
    {
        $name = $request->input('searchname');
        $date = $request->input('searchdate');
        $date = explode("-", $date);
        $user = Auth::user();
        $data = DB::table('surat_jalan')
            ->where('hapus',0)
            ->where('surat_jalan.name','like','%'.$name.'%')
            ->whereBetween('surat_jalan.tanggalDibuat',[$date[0], $date[1]])
            ->orderByDesc('surat_jalan.tanggalDibuat')
            ->paginate(10);

        $dataDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*')
            ->join('surat_jalan','surat_jalan_detail.suratJalanID','=','surat_jalan.id')
            ->where('surat_jalan.hapus',0)
            ->where('surat_jalan.name','like','%'.$name.'%')
            ->where('surat_jalan.tanggalDibuat',[date($date[0]), date($date[1])])
            ->orderByDesc('surat_jalan.tanggalDibuat')
            ->paginate(10);
        return view('master.note.suratJalan.index',[
            'data' => $data,
            'dataDetail' => $dataDetail,
        ]);
    }

    public function print(SuratJalan $suratJalan)
    {
        //
          $dataGudang =DB::table('MGudang')
            ->select('MGudang.*','MPerusahaan.cname as perusahaanName','MPerusahaan.Gambar as perusahaanGambar')
            ->join('MPerusahaan','MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->get();  


        $dataPurchaseRequestDetail = DB::table('purchase_request_detail')
            ->select('purchase_request_detail.*','purchase_request.name','Item.ItemName as ItemName','Unit.Name as UnitName')//
            ->join('purchase_request', 'purchase_request_detail.idPurchaseRequest', '=','purchase_request.id')
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->where('purchase_request_detail.jumlahProses', '<', DB::raw('purchase_request_detail.jumlah'))//errorr disini
            ->get();
        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*','MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang','purchase_request.MGudangID','=','MGudang.MGudangID')
            ->join('MPerusahaan','MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();

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

         $dataTotalDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*', 'purchase_request_detail.id as idPRD','Item.ItemName as itemName' )
            ->join('purchase_request_detail', 'surat_jalan_detail.PurchaseRequestDetailID','=','purchase_request_detail.id')
            ->join('Item', 'surat_jalan_detail.ItemID','=','Item.ItemID')
            ->where('suratJalanID', $suratJalan->id)
            ->get();
        
        return view('master.note.suratJalan.print',[
            'dataGudang' => $dataGudang,
            'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
            'dataPurchaseRequest' => $dataPurchaseRequest,
            'dataBarang' => $dataBarang,
            'dataBarangTag' => $dataBarangTag,
            'dataTag' => $dataTag,
            'suratJalan' => $suratJalan,
            'dataTotalDetail' => $dataTotalDetail,
        ]);
    }
}
