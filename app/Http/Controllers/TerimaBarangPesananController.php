<?php

namespace App\Http\Controllers;

use App\Models\TransactionGudangBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class TerimaBarangPesananController extends Controller
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
        $user = Auth::user();
        $data = DB::table('transaction_gudang_barang')
            ->select('transaction_gudang_barang.*','ItemTransaction.Name as itemTransactionName','MSupplier.Name as supplierName', 'MSupplier.AtasNama as supplierAtasNama')
            ->leftjoin('ItemTransaction','transaction_gudang_barang.ItemTransactionID','=','ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier','transaction_gudang_barang.SupplierID','=','MSupplier.SupplierID')
            ->leftjoin('purchase_request','transaction_gudang_barang.PurchaseRequestID','=','purchase_request.id')
            ->whereNotNull('transaction_gudang_barang.SuratJalanID')
            ->where('transaction_gudang_barang.isMenerima',1)
            ->where('transaction_gudang_barang.hapus',0)
            //->where('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID) asli
            ->where('transaction_gudang_barang.MGudangIDTujuan',1) //debug
            //->orWhere('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID) ya
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        $dataGudang = DB::table('MGudang')
            ->get();
        return view('master.note.terimaBarangPesanan.index',[
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
        $user = Auth::user();

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
            ->get();  

       $suratJalan = DB::table('surat_jalan')
            ->where('hapus', 0)
            ->get();
        //dd($suratJalan);
        $suratJalanDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*','Item.ItemName as itemName','Item.ItemID as ItemID', 'Unit.Name as unitName', 'purchase_request_detail.idPurchaseRequest as idPR')
            ->join('surat_jalan', 'surat_jalan_detail.suratJalanID','=','surat_jalan.id')
            ->join('purchase_request_detail', 'surat_jalan_detail.PurchaseRequestDetailID','=','purchase_request_detail.id')
            ->join('Item','surat_jalan_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('surat_jalan.hapus', 0)
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
        //dd($dataPurchaseRequestDetail);
        
        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*','MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang','purchase_request.MGudangID','=','MGudang.MGudangID')
            ->join('MPerusahaan','MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();
        $dataItemTransaction = DB::table("ItemTransaction")
            ->get();
        

        return view('master.note.terimaBarangPesanan.tambah',[
            'dataBarangTag' => $dataBarangTag,
            'dataBarang' => $dataBarang,
            'dataTag' => $dataTag,
            'suratJalan' => $suratJalan,
            'suratJalanDetail' => $suratJalanDetail,
            'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
            'dataPurchaseRequest' => $dataPurchaseRequest,
            'dataGudang'=>$dataGudang,
            'dataItemTransaction' => $dataItemTransaction,
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
        /*$dataLokasiPerusahaan = DB::table('MPerusahaan')
            ->where("MPerusahaanID", $data['perusahaan'])
            ->get();*/
        
        $dataItemTransaction = DB::table('ItemTransaction')->where('ItemTransactionID',$data['ItemTransaction'])->get();
        $dataPo = DB::table('transaction_gudang_barang')
            ->where('name', 'like', $dataItemTransaction[0]->Code.'/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/%")
            ->get();
        

        $totalIndex = str_pad(strval(count($dataPo) + 1),4,'0',STR_PAD_LEFT);

        $idtransaksigudang = DB::table('transaction_gudang_barang')->insertGetId(array(
            'name' => $dataItemTransaction[0]->Code.'/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'tanggalDibuat' => $data['tanggalDibuat'],  
            'tanggalDatang' => $data['tanggalDatang'],  
            'keteranganKendaraan' => $data['keteranganKendaraan'],  
            'keteranganNomorPolisi' => $data['keteranganNomorPolisi'],  
            'keteranganPemudi' => $data['keteranganPemudi'],  
            'keteranganTransaksi' => $data['keteranganTransaksi'],  
            'ItemTransactionID' => $data['ItemTransaction'],  
            'isMenerima' => 1,  
            'MGudangIDAwal' => $data['MGudangIDAwal'],  
            'MGudangIDTujuan' => $data['MGudangIDTujuan'],  
            'SuratJalanID' => $data['SuratJalanID'],  
            'PurchaseRequestID' => $data['PurchaseRequestID'],  
            'hapus' => 0,  
            'CreatedBy'=> $user->id,
            'CreatedOn'=> date("Y-m-d h:i:sa"),
            'UpdatedBy'=> $user->id,
            'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $idItemInventoryTransaction = DB::table('ItemInventoryTransaction')->insertGetId(array(
            'Name' => $dataItemTransaction[0]->Code.'/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'Description' => $data['keteranganTransaksi'],  
            //'tanggalDatang' => $data['tanggalDatang'],  
            'ItemTransactionID' => $data['ItemTransaction'],  
            'Date' => $data['tanggalDibuat'],  
            //'SupplierID' => $data['Supplier'],  
            'NTBID' => $idtransaksigudang,  
            'EmployeeID' => $user->id,  
            'MGudangID' => $data['MGudangIDTujuan'],  
            'SuratJalanID' => $data['SuratJalanID'],  
            'CreatedBy'=> $user->id,
            'CreatedOn'=> date("Y-m-d h:i:sa"),
            'UpdatedBy'=> $user->id,
            'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
        //keluarkan kabeh item, baru bukak pemilihan PO ne sg mana, PO gk ush dipilih misalkan transfer atau kirim barang
        for($i = 0; $i < count($data['itemId']); $i++){
            $idtransaksigudangdetail = DB::table('transaction_gudang_barang_detail')->insertGetId(array(
                'transactionID' => $idtransaksigudang,
                //'purchaseOrderDetailID' => $data['podID'][$i],
                'PurchaseRequestDetailID' => $data['itemPRDID'][$i],
                'ItemID' => $data['itemId'][$i],
                'jumlah' => $data['itemJumlah'][$i],
                'keterangan' => $data['itemKeterangan'][$i],
                //'harga' => $data['itemHarga'][$i],//didapat dri hidden ketika milih barang di PO
                )
            ); 
            $totalNow = DB::table('purchase_request_detail')->select('jumlah', 'jumlahDiterima')->where('id', $data['itemPRDID'][$i])->get();
            DB::table('purchase_request_detail')
                ->where('id',$data['itemPRDID'][$i])
                ->update(array(
                    'jumlahDiterima' => $totalNow[0]->jumlahDiterima + $data['itemJumlah'][$i],  
                )
            );
 
            $dataItem = DB::table('Item')
                ->select('Unit.UnitID as unit')
                ->leftjoin('Unit','Item.UnitID','=','Unit.UnitID')
                ->where('Item.ItemID', $data['itemId'][$i])
                ->get();
            $dataPOD = DB::table('purchase_order_detail')
                ->select('harga')
                ->where('idItem', $data['itemId'][$i])
                ->orderBy('idItem', 'desc')
                ->limit(1)
                ->get();
            //Item Inventory Transaction line
            DB::table('ItemInventoryTransactionLine')
                ->insert(array(
                    'TransactionID' => $idItemInventoryTransaction,  
                    //'transactionDetailID' => $idtransaksigudangdetail,  
                    'ItemID' => $data['itemId'][$i],  
                    'MGudangID' => $data['MGudangIDTujuan'],  
                    'UnitID' => $dataItem[0]->unit,  
                    'UnitPrice' => $dataPOD[0]->harga,  
                    'Quantity' => $data['itemJumlah'][$i],  
                    'TotalUnitPrice' => $dataPOD[0]->harga * $data['itemJumlah'][$i],  
                )
            );
              
        }
        return redirect()->route('terimaBarangPesanan.index')->with('status','Success!!');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionGudangBarang $terimaBarangPesanan)
    {
        
        //
        $user = Auth::user();

        $dataSupplier = DB::table('MSupplier')
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

        $dataGudang =DB::table('MGudang')
            ->get();  

        $suratJalan = DB::table('surat_jalan')
            ->where('hapus', 0)
            ->get();
        $suratJalanDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*')
            ->join('surat_jalan', 'surat_jalan_detail.suratJalanID','=','surat_jalan.id')
            ->where('surat_jalan.hapus', 0)
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
        //dd($dataPurchaseRequestDetail);
        
        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*','MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang','purchase_request.MGudangID','=','MGudang.MGudangID')
            ->join('MPerusahaan','MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();

        $dataGudang =DB::table('MGudang')
        ->get();  


        return view('master.note.terimaBarangPesanan.detail',[
            'dataSupplier' => $dataSupplier,
            'dataBarangTag' => $dataBarangTag,
            'dataBarang' => $dataBarang,
            'dataTag' => $dataTag,
            'suratJalan' => $suratJalan,
            'suratJalanDetail' => $suratJalanDetail,
            'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
            'dataPurchaseRequest' => $dataPurchaseRequest,
            'transactionGudangBarang' => $terimaBarangPesanan,
        ]);
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionGudangBarang $terimaBarangPesanan)
    {
        
        //
        $user = Auth::user();

        $dataSupplier = DB::table('MSupplier')
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

        $dataGudang =DB::table('MGudang')
            ->get();  
 
        $suratJalan = DB::table('surat_jalan')
            ->where('hapus', 0)
            ->get();

        $suratJalanDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*','Item.ItemName as itemName','Item.ItemID as ItemID', 'Unit.Name as unitName', 'purchase_request_detail.idPurchaseRequest as idPR')
            ->join('surat_jalan', 'surat_jalan_detail.suratJalanID','=','surat_jalan.id')
            ->join('purchase_request_detail', 'surat_jalan_detail.PurchaseRequestDetailID','=','purchase_request_detail.id')
            ->join('Item','surat_jalan_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('surat_jalan.hapus', 0)
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
        //dd($dataPurchaseRequestDetail);
        
        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*','MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang','purchase_request.MGudangID','=','MGudang.MGudangID')
            ->join('MPerusahaan','MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();
        $dataItemTransaction = DB::table("ItemTransaction")
            ->get();

        $dataTotalDetail = DB::table('transaction_gudang_barang_detail')
            ->select('transaction_gudang_barang_detail.*', 'purchase_request_detail.id as idPRD','Item.ItemName as itemName' )
            ->join('purchase_request_detail', 'transaction_gudang_barang_detail.PurchaseRequestDetailID','=','purchase_request_detail.id')
            ->join('Item', 'transaction_gudang_barang_detail.ItemID','=','Item.ItemID')
            ->where('transaction_gudang_barang_detail.transactionID', $terimaBarangPesanan->id)
            ->get();
        //dd($dataTotalDetail);

        return view('master.note.terimaBarangPesanan.edit',[
            'dataSupplier' => $dataSupplier,
            'dataBarangTag' => $dataBarangTag,
            'dataBarang' => $dataBarang,
            'dataTag' => $dataTag,
            'suratJalan' => $suratJalan,
            'suratJalanDetail' => $suratJalanDetail,
            'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
            'dataPurchaseRequest' => $dataPurchaseRequest,
            'transactionGudangBarang' => $terimaBarangPesanan,
            'dataGudang'=>$dataGudang,
            'dataItemTransaction' => $dataItemTransaction,
            'dataTotalDetail' => $dataTotalDetail,
        ]);
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionGudangBarang $terimaBarangPesanan)
    {
        
        //
        $user = Auth::user();
        $data = $request->collect();

        DB::table('transaction_gudang_barang')
            ->where('id', $terimaBarangPesanan->id)
            ->update(array(
                'tanggalDibuat' => $data['tanggalDibuat'],  
                'tanggalDatang' => $data['tanggalDatang'],  
                'keteranganKendaraan' => $data['keteranganKendaraan'],  
                'keteranganNomorPolisi' => $data['keteranganNomorPolisi'],  
                'keteranganPemudi' => $data['keteranganPemudi'],  
                'keteranganTransaksi' => $data['keteranganTransaksi'],  
                'ItemTransactionID' => $data['ItemTransaction'],  
                'isMenerima' => 1,  
                'MGudangIDAwal' => $data['MGudangIDAwal'],  
                'MGudangIDTujuan' => $data['MGudangIDTujuan'],
                'SuratJalanID' => $data['SuratJalanID'],  
                'PurchaseRequestID' => $data['PurchaseRequestID'],    
                'hapus' => 0,  
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        DB::table('ItemInventoryTransaction')
            ->where('NTBID', $terimaBarangPesanan->id)
            ->update(array(
                'Description' => $data['keteranganTransaksi'],  
                //'tanggalDatang' => $data['tanggalDatang'],  
                'ItemTransactionID' => $data['ItemTransaction'],  
                'Date' => $data['tanggalDibuat'],  
                //'SupplierID' => $data['Supplier'],  
                'EmployeeID' => $user->id,  
                'MGudangID' => $data['MGudangIDTujuan'],  
                'SuratJalanID' => $data['SuratJalanID'],  
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $dataTransactionID = DB::table('ItemInventoryTransaction')
            ->where('NTBID', $terimaBarangPesanan->id)
            ->get();
        //dd($dataTransactionID);
        $dataDetailTotal = DB::table('transaction_gudang_barang_detail')
            ->where('transactionID',$terimaBarangPesanan->id)
            ->get();

        foreach($dataDetailTotal as $data){
            DB::table('purchase_request_detail')
                ->where('id', $data->purchaseOrderDetailID)
                ->decrement('jumlahDiterima', $data->jumlah);
        } 

        /*foreach($dataDetailTotal as $data){
            DB::table('purchase_request_detail')
            ->where('id', $data['PurchaseRequestDetailID'])
            ->update([
                'jumlahProses' => DB::raw('jumlahProses' - $data['jumlah']),
            ]);
        } */
         
        DB::table('transaction_gudang_barang_detail')
            ->where('transactionID', $terimaBarangPesanan->id)
            ->delete(); 

        DB::table('ItemInventoryTransactionLine')
            ->where('TransactionID', $dataTransactionID[0]->TransactionID)
            ->delete();
        //keluarkan kabeh item, baru bukak pemilihan PO ne sg mana, PO gk ush dipilih misalkan transfer atau kirim barang
        for($i = 0; $i < count( $data['itemId'] ); $i++){
            $idtransaksigudangdetail = DB::table('transaction_gudang_barang_detail')->insertGetId(array(
                'transactionID' => $terimaBarangPesanan->id,
                'purchaseRequestDetailID' => $data['itemPRDID'][$i],
                'ItemID' => $data['itemId'][$i],
                'jumlah' => $data['itemJumlah'][$i],
                'keterangan' => $data['itemKeterangan'][$i],
                //'harga' => $data['itemHarga'][$i],//didapat dri hidden ketika milih barang di PO
                )
            );  

            $totalNow = DB::table('purchase_request_detail')->select('jumlah', 'jumlahDiterima')->where('id', $data['itemPRDID'][$i])->get();
            DB::table('purchase_request_detail')
                ->where('id',$data['itemPRDID'][$i])
                ->update(array(
                    'jumlahDiterima' => $totalNow[0]->jumlahDiterima + $data['itemJumlah'][$i],  
                )
            );

            $dataItem = DB::table('Item')
                ->select('Unit.UnitID as unit')
                ->leftjoin('Unit','Item.UnitID','=','Unit.UnitID')
                ->where('Item.ItemID', $data['itemId'][$i])
                ->get();
            $dataPOD = DB::table('purchase_order_detail')
                ->select('harga')
                ->where('idItem', $data['itemId'][$i])
                ->orderBy('idItem', 'desc')
                ->limit(1)
                ->get();
            //Item Inventory Transaction line
            DB::table('ItemInventoryTransactionLine')
                ->insert(array(
                    'TransactionID' => $dataTransactionID[0]->TransactionID,  
                    'ItemID' => $data['itemId'][$i],  
                    'MGudangID' => $data['MGudangIDTujuan'],  
                    'UnitID' => $dataItem[0]->unit,  
                    'UnitPrice' => $dataPOD[0]->harga,  
                    'Quantity' => $data['itemJumlah'][$i],  
                    'TotalUnitPrice' => $dataPOD[0]->harga * $data['itemJumlah'][$i],  
                )
            );
                      
        }

        return redirect()->route('terimaBarangPesanan.index')->with('status','Success!!');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionGudangBarang $transactionGudangBarang)
    {
        //
        $user = Auth::user();
        DB::table('transaction_gudang_barang')
            ->where('id', $transactionGudangBarang->id)
            ->update(array(
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
                'Hapus' => 1,
        ));

        return redirect()->route('terimaBarangPesanan.index')->with('status','Success!!');
    }

    public function searchTGBName(Request $request)
    {
        $name = $request->input('searchname');
        $user = Auth::user();
        $data = DB::table('transaction_gudang_barang')
            ->select('transaction_gudang_barang.*','ItemTransaction.Name as itemTransactionName','MSupplier.Name as supplierName', 'MSupplier.AtasNama as supplierAtasNama')
            ->leftjoin('ItemTransaction','transaction_gudang_barang.ItemTransactionID','=','ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier','transaction_gudang_barang.SupplierID','=','MSupplier.SupplierID')
            ->leftjoin('purchase_order','transaction_gudang_barang.PurchaseOrderID','=','purchase_order.id')
            ->whereNotNull('transaction_gudang_barang.SupplierID')
            ->where('transaction_gudang_barang.isMenerima',1)
            ->where('transaction_gudang_barang.hapus',0)
            ->where('transaction_gudang_barang.name','like','%'.$name.'%')
            ->where('transaction_gudang_barang.MGudangIDAwal',$user->MGudangID)
            //->orWhere('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID)
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        return view('master.note.transactionGudang.index',[
            'data' => $data,
            'dataDetail' => $dataDetail,
        ]);
    }

    public function searchTGBDate(Request $request)
    {
        $date = $request->input('searchdate');
        $date = explode("-", $date);
        $user = Auth::user();
        $data = DB::table('transaction_gudang_barang')
            ->select('transaction_gudang_barang.*','ItemTransaction.Name as itemTransactionName','MSupplier.Name as supplierName', 'MSupplier.AtasNama as supplierAtasNama')
            ->leftjoin('ItemTransaction','transaction_gudang_barang.ItemTransactionID','=','ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier','transaction_gudang_barang.SupplierID','=','MSupplier.SupplierID')
            ->leftjoin('purchase_order','transaction_gudang_barang.PurchaseOrderID','=','purchase_order.id')
            ->whereNotNull('transaction_gudang_barang.SupplierID')
            ->where('transaction_gudang_barang.isMenerima',1)
            ->where('transaction_gudang_barang.hapus',0)
            ->whereBetween('transaction_gudang_barang.tanggalDibuat',[$date[0], $date[1]])
            ->where('transaction_gudang_barang.MGudangIDAwal',$user->MGudangID)
            //->orWhere('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID)
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        return view('master.note.transactionGudang.index',[
            'data' => $data,
            'dataDetail' => $dataDetail,
        ]);
    }
}