<?php

namespace App\Http\Controllers;

use App\Models\TransactionGudangBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KirimBarangPesananController extends Controller
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
            ->select('transaction_gudang_barang.*', 'ItemTransaction.Name as itemTransactionName', 'MSupplier.Name as supplierName', 'MSupplier.AtasNama as supplierAtasNama')
            ->leftjoin('ItemTransaction', 'transaction_gudang_barang.ItemTransactionID', '=', 'ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier', 'transaction_gudang_barang.SupplierID', '=', 'MSupplier.SupplierID')
            ->leftjoin('purchase_request', 'transaction_gudang_barang.PurchaseRequestID', '=', 'purchase_request.id')
            ->where('transaction_gudang_barang.isMenerima', '0')
            ->whereNotNull('transaction_gudang_barang.SuratJalanID')
            ->where('transaction_gudang_barang.MGudangIDAwal', $user->MGudangID)
            ->where('transaction_gudang_barang.hapus', 0)
            //->orWhere('MGudangIDTujuan',$user->MGudangID)
            ->orderByDesc('transaction_gudang_barang.tanggalDibuat', 'transaction_gudang_barang.id')
            ->paginate(10);
        // ->get();
        //dd($data);
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        $dataGudang = DB::table('MGudang')
            ->get();

        $user = Auth::user();
        $check = $this->checkAccess('kirimBarangPesanan.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.kirimBarangPesanan.index', [
                'data' => $data,
                'dataDetail' => $dataDetail,
                'dataGudang' => $dataGudang,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kirim Barang Pesanan');
        }
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
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->leftjoin('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus', 0)
            ->get();

        $dataBarangTag = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName', 'ItemTagValues.ItemTagID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus', 0)
            ->get();
        //dd($dataBarangTag);
        $dataTag = DB::table('ItemTag')
            ->get();

        $dataGudang = DB::table('MGudang')
            ->get();

        $suratJalan = DB::table('surat_jalan')
            ->where('hapus', 0)
            ->get();
        //dd($suratJalan);
        $suratJalanDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*', 'Item.ItemName as itemName', 'Item.ItemID as ItemID', 'Unit.Name as unitName', 'purchase_request_detail.idPurchaseRequest as idPR')
            ->join('surat_jalan', 'surat_jalan_detail.suratJalanID', '=', 'surat_jalan.id')
            ->join('purchase_request_detail', 'surat_jalan_detail.PurchaseRequestDetailID', '=', 'purchase_request_detail.id')
            ->join('Item', 'surat_jalan_detail.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('surat_jalan.hapus', 0)
            ->get();
        //dd($suratJalanDetail);

        $dataPurchaseRequestDetail = DB::table('purchase_request_detail')
            ->select('purchase_request_detail.*', 'purchase_request.name', 'Item.ItemName as ItemName', 'Unit.Name as UnitName') //
            ->join('purchase_request', 'purchase_request_detail.idPurchaseRequest', '=', 'purchase_request.id')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->where('purchase_request_detail.jumlahProses', '<', DB::raw('purchase_request_detail.jumlah')) //errorr disini
            ->get();
        //dd($dataPurchaseRequestDetail);

        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*', 'MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();

        $dataGudang = DB::table('MGudang')
            ->get();

        $dataItemTransaction = DB::table("ItemTransaction")
            ->get();




        $user = Auth::user();
        $check = $this->checkAccess('kirimBarangPesanan.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.kirimBarangPesanan.tambah', [
                'dataBarangTag' => $dataBarangTag,
                'dataBarang' => $dataBarang,
                'dataTag' => $dataTag,
                'suratJalan' => $suratJalan,
                'suratJalanDetail' => $suratJalanDetail,
                'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
                'dataPurchaseRequest' => $dataPurchaseRequest,
                'dataGudang' => $dataGudang,
                'dataItemTransaction' => $dataItemTransaction,
                'user' => $user,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kirim Barang Pesanan');
        }
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
        //
        $user = Auth::user();
        $data = $request->collect();
        $year = date("Y");
        $month = date("m");

        $dataLokasi = DB::table('MGudang')
            ->select('MKota.*', 'MPerusahaan.cnames as perusahaanCode')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        /*$dataLokasiPerusahaan = DB::table('MPerusahaan')
            ->where("MPerusahaanID", $data['perusahaan'])
            ->get();*/

        $dataItemTransaction = DB::table('ItemTransaction')->where('ItemTransactionID', $data['ItemTransaction'])->get();
        $dataPo = DB::table('transaction_gudang_barang')
            ->where('name', 'like', $dataItemTransaction[0]->Code . '/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/%")
            ->get();


        $totalIndex = str_pad(strval(count($dataPo) + 1), 4, '0', STR_PAD_LEFT);

        $idtransaksigudang = DB::table('transaction_gudang_barang')->insertGetId(
            array(
                'name' => $dataItemTransaction[0]->Code . '/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/" . $totalIndex,
                'tanggalDibuat' => $data['tanggalDibuat'],
                //'tanggalDatang' => $data['tanggalDatang'],  
                'keteranganKendaraan' => $data['keteranganKendaraan'],
                'keteranganNomorPolisi' => $data['keteranganNomorPolisi'],
                'keteranganPemudi' => $data['keteranganPemudi'],
                'keteranganTransaksi' => $data['keteranganTransaksi'],
                'ItemTransactionID' => $data['ItemTransaction'],
                'isMenerima' => 0,
                'MGudangIDAwal' => $data['MGudangIDAwal'],
                'MGudangIDTujuan' => $data['MGudangIDTujuan'],
                'SuratJalanID' => $data['SuratJalanID'],
                'PurchaseRequestID' => $data['PurchaseRequestID'],
                'hapus' => 0,
                'CreatedBy' => $user->id,
                'CreatedOn' => date("Y-m-d h:i:sa"),
                'UpdatedBy' => $user->id,
                'UpdatedOn' => date("Y-m-d h:i:sa"),
            )
        );

        $idItemInventoryTransaction = DB::table('ItemInventoryTransaction')->insertGetId(
            array(
                'Name' => $dataItemTransaction[0]->Code . '/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/" . $totalIndex,
                'Description' => $data['keteranganTransaksi'],
                //'tanggalDatang' => $data['tanggalDatang'],  
                'ItemTransactionID' => $data['ItemTransaction'],
                'Date' => $data['tanggalDibuat'],
                //'SupplierID' => $data['Supplier'],  
                'NTBID' => $idtransaksigudang,
                'EmployeeID' => $user->id,
                'MGudangID' => $data['MGudangIDAwal'],
                'SuratJalanID' => $data['SuratJalanID'],
                'CreatedBy' => $user->id,
                'CreatedOn' => date("Y-m-d h:i:sa"),
                'UpdatedBy' => $user->id,
                'UpdatedOn' => date("Y-m-d h:i:sa"),
            )
        );
        //keluarkan kabeh item, baru bukak pemilihan PO ne sg mana, PO gk ush dipilih misalkan transfer atau kirim barang
        for ($i = 0; $i < count($data['itemId']); $i++) {
            $idtransaksigudangdetail = DB::table('transaction_gudang_barang_detail')->insertGetId(
                array(
                    'transactionID' => $idtransaksigudang,
                    'PurchaseRequestDetailID' => $data['itemPRDID'][$i],
                    'ItemID' => $data['itemId'][$i],
                    'jumlah' => $data['itemJumlah'][$i],
                    'keterangan' => $data['itemKeterangan'][$i],
                    //'harga' => $data['itemHarga'][$i],//didapat dri hidden ketika milih barang di PO
                )
            );

            $dataItem = DB::table('Item')
                ->select('Unit.UnitID as unit')
                ->leftjoin('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
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
                ->insert(
                    array(
                        'TransactionID' => $idItemInventoryTransaction,
                        //'transactionDetailID' => $idtransaksigudangdetail,  
                        'ItemID' => $data['itemId'][$i],
                        'MGudangID' => $data['MGudangIDAwal'],
                        'UnitID' => $dataItem[0]->unit,
                        'UnitPrice' => $dataPOD[0]->harga,
                        'Quantity' => $data['itemJumlah'][$i] * -1.0,
                        'TotalUnitPrice' => $dataPOD[0]->harga * $data['itemJumlah'][$i],
                    )
                );
        }
        return redirect()->route('kirimBarangPesanan.index')->with('status', 'Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionGudangBarang $kirimBarangPesanan)
    {


        //
        $user = Auth::user();

        $dataSupplier = DB::table('MSupplier')
            ->get();

        $dataBarang = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->leftjoin('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus', 0)
            ->get();

        $dataBarangTag = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName', 'ItemTagValues.ItemTagID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus', 0)
            ->get();
        //dd($dataBarangTag);
        $dataTag = DB::table('ItemTag')
            ->get();

        $dataGudang = DB::table('MGudang')
            ->get();

        $suratJalan = DB::table('surat_jalan')
            ->where('hapus', 0)
            ->get();
        $suratJalanDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*')
            ->join('surat_jalan', 'surat_jalan_detail.suratJalanID', '=', 'surat_jalan.id')
            ->where('surat_jalan.hapus', 0)
            ->get();


        $dataPurchaseRequestDetail = DB::table('purchase_request_detail')
            ->select('purchase_request_detail.*', 'purchase_request.name', 'Item.ItemName as ItemName', 'Unit.Name as UnitName') //
            ->join('purchase_request', 'purchase_request_detail.idPurchaseRequest', '=', 'purchase_request.id')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->where('purchase_request_detail.jumlahProses', '<', DB::raw('purchase_request_detail.jumlah')) //errorr disini
            ->get();
        //dd($dataPurchaseRequestDetail);

        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*', 'MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();
        $dataItemTransaction = DB::table("ItemTransaction")
            ->get();
        $dataTotalDetail = DB::table('transaction_gudang_barang_detail')
            ->select('transaction_gudang_barang_detail.*', 'purchase_request_detail.id as idPRD', 'Item.ItemName as itemName')
            ->join('purchase_request_detail', 'transaction_gudang_barang_detail.PurchaseRequestDetailID', '=', 'purchase_request_detail.id')
            ->join('Item', 'transaction_gudang_barang_detail.ItemID', '=', 'Item.ItemID')
            ->where('transaction_gudang_barang_detail.transactionID', $kirimBarangPesanan->id)
            ->get();


        $user = Auth::user();
        $check = $this->checkAccess('kirimBarangPesanan.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.kirimBarangPesanan.detail', [
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataBarang' => $dataBarang,
                'dataTag' => $dataTag,
                'suratJalan' => $suratJalan,
                'suratJalanDetail' => $suratJalanDetail,
                'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
                'dataGudang' => $dataGudang,
                'dataPurchaseRequest' => $dataPurchaseRequest,
                'transactionGudangBarang' => $kirimBarangPesanan,
                'dataItemTransaction' => $dataItemTransaction,
                'dataTotalDetail' => $dataTotalDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kirim Barang Pesanan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionGudangBarang $kirimBarangPesanan)
    {


        //
        $user = Auth::user();

        $dataSupplier = DB::table('MSupplier')
            ->get();

        $dataBarang = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->leftjoin('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus', 0)
            ->get();

        $dataBarangTag = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName', 'ItemTagValues.ItemTagID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus', 0)
            ->get();
        //dd($dataBarangTag);
        $dataTag = DB::table('ItemTag')
            ->get();

        $dataGudang = DB::table('MGudang')
            ->get();
        $dataItemTransaction = DB::table("ItemTransaction")
            ->get();

        $suratJalan = DB::table('surat_jalan')
            ->where('hapus', 0)
            ->get();

        $suratJalanDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*', 'Item.ItemName as itemName', 'Item.ItemID as ItemID', 'Unit.Name as unitName', 'purchase_request_detail.idPurchaseRequest as idPR')
            ->join('surat_jalan', 'surat_jalan_detail.suratJalanID', '=', 'surat_jalan.id')
            ->join('purchase_request_detail', 'surat_jalan_detail.PurchaseRequestDetailID', '=', 'purchase_request_detail.id')
            ->join('Item', 'surat_jalan_detail.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('surat_jalan.hapus', 0)
            ->get();

        $dataPurchaseRequestDetail = DB::table('purchase_request_detail')
            ->select('purchase_request_detail.*', 'purchase_request.name', 'Item.ItemName as ItemName', 'Unit.Name as UnitName') //
            ->join('purchase_request', 'purchase_request_detail.idPurchaseRequest', '=', 'purchase_request.id')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->where('purchase_request_detail.jumlahProses', '<', DB::raw('purchase_request_detail.jumlah')) //errorr disini
            ->get();
        //dd($dataPurchaseRequestDetail);

        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*', 'MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();

        $dataTotalDetail = DB::table('transaction_gudang_barang_detail')
            ->select('transaction_gudang_barang_detail.*', 'purchase_request_detail.id as idPRD', 'Item.ItemName as itemName')
            ->join('purchase_request_detail', 'transaction_gudang_barang_detail.PurchaseRequestDetailID', '=', 'purchase_request_detail.id')
            ->join('Item', 'transaction_gudang_barang_detail.ItemID', '=', 'Item.ItemID')
            ->where('transaction_gudang_barang_detail.transactionID', $kirimBarangPesanan->id)
            ->get();

        $user = Auth::user();
        $check = $this->checkAccess('kirimBarangPesanan.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.kirimBarangPesanan.edit', [
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataBarang' => $dataBarang,
                'dataGudang' => $dataGudang,
                'dataItemTransaction' => $dataItemTransaction,
                'dataTag' => $dataTag,
                'suratJalan' => $suratJalan,
                'suratJalanDetail' => $suratJalanDetail,
                'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
                'dataPurchaseRequest' => $dataPurchaseRequest,
                'transactionGudangBarang' => $kirimBarangPesanan,
                'dataTotalDetail' => $dataTotalDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kirim Barang Pesanan');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionGudangBarang $kirimBarangPesanan)
    {


        //
        $user = Auth::user();
        $data = $request->collect();

        DB::table('transaction_gudang_barang')
            ->where('id', $kirimBarangPesanan->id)
            ->update(
                array(
                    'tanggalDibuat' => $data['tanggalDibuat'],
                    //'tanggalDatang' => $data['tanggalDatang'],  
                    'keteranganKendaraan' => $data['keteranganKendaraan'],
                    'keteranganNomorPolisi' => $data['keteranganNomorPolisi'],
                    'keteranganPemudi' => $data['keteranganPemudi'],
                    'keteranganTransaksi' => $data['keteranganTransaksi'],
                    'ItemTransactionID' => $data['ItemTransaction'],
                    'isMenerima' => 0,
                    'MGudangIDAwal' => $data['MGudangIDAwal'],
                    'MGudangIDTujuan' => $data['MGudangIDTujuan'],
                    'SuratJalanID' => $data['SuratJalanID'],
                    'PurchaseRequestID' => $data['PurchaseRequestID'],
                    'hapus' => 0,
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                )
            );

        DB::table('ItemInventoryTransaction')
            ->where('NTBID', $kirimBarangPesanan->id)
            ->update(
                array(
                    'Description' => $data['keteranganTransaksi'],
                    //'tanggalDatang' => $data['tanggalDatang'],  
                    'ItemTransactionID' => $data['ItemTransaction'],
                    'Date' => $data['tanggalDibuat'],
                    //'SupplierID' => $data['Supplier'],  
                    'EmployeeID' => $user->id,
                    'MGudangID' => $data['MGudangIDAwal'],
                    'SuratJalanID' => $data['SuratJalanID'],
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                )
            );

        $dataTransactionID = DB::table('ItemInventoryTransaction')
            ->where('NTBID', $kirimBarangPesanan->id)
            ->get();

        /*$dataDetailTotal = DB::table('transaction_gudang_barang_detail')
            ->where('PurchaseRequestID', $data['PurchaseRequestID'])
            ->get();*/

        DB::table('transaction_gudang_barang_detail')
            ->where('transactionID', $kirimBarangPesanan->id)
            ->delete();

        DB::table('ItemInventoryTransactionLine')
            ->where('TransactionID', $dataTransactionID[0]->TransactionID)
            ->delete();
        //keluarkan kabeh item, baru bukak pemilihan PO ne sg mana, PO gk ush dipilih misalkan transfer atau kirim barang
        for ($i = 0; $i < count($data['itemId']); $i++) {
            $idtransaksigudangdetail = DB::table('transaction_gudang_barang_detail')->insertGetId(
                array(
                    'transactionID' => $kirimBarangPesanan->id,
                    'purchaseRequestDetailID' => $data['itemPRDID'][$i],
                    'ItemID' => $data['itemId'][$i],
                    'jumlah' => $data['itemJumlah'][$i],
                    'keterangan' => $data['itemKeterangan'][$i],
                    //'harga' => $data['itemHarga'][$i],//didapat dri hidden ketika milih barang di PO
                )
            );


            $dataItem = DB::table('Item')
                ->select('Unit.UnitID as unit')
                ->leftjoin('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
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
                ->insert(
                    array(
                        'TransactionID' => $dataTransactionID[0]->TransactionID,
                        //'transactionDetailID' => $idtransaksigudangdetail,  
                        'ItemID' => $data['itemId'][$i],
                        'MGudangID' => $data['MGudangIDAwal'],
                        'UnitID' => $dataItem[0]->unit,
                        'UnitPrice' => $dataPOD[0]->harga,
                        'Quantity' => $data['itemJumlah'][$i] * -1.0,
                        'TotalUnitPrice' => $dataPOD[0]->harga * $data['itemJumlah'][$i],
                    )
                );
        }

        return redirect()->route('kirimBarangPesanan.index')->with('status', 'Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionGudangBarang $kirimBarangPesanan)
    {
        //
        $user = Auth::user();

        DB::table('transaction_gudang_barang')
            ->where('id', $kirimBarangPesanan['id'])
            ->update(array(
                'UpdatedBy' => $user->id,
                'UpdatedOn' => date("Y-m-d h:i:sa"),
                'hapus' => 1,
            ));

        return redirect()->route('kirimBarangPesanan.index')->with('status', 'Success!!');
    }
    public function searchTGBName(Request $request)
    {
        $name = $request->input('searchname');
        $user = Auth::user();
        $data = DB::table('transaction_gudang_barang')
            ->select('transaction_gudang_barang.*', 'ItemTransaction.Name as itemTransactionName', 'MSupplier.Name as supplierName', 'MSupplier.AtasNama as supplierAtasNama')
            ->leftjoin('ItemTransaction', 'transaction_gudang_barang.ItemTransactionID', '=', 'ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier', 'transaction_gudang_barang.SupplierID', '=', 'MSupplier.SupplierID')
            ->leftjoin('purchase_order', 'transaction_gudang_barang.PurchaseOrderID', '=', 'purchase_order.id')
            ->whereNotNull('SupplierID')
            ->where('isMenerima', 0)
            ->where('transaction_gudang_barang.name', 'like', '%' . $name . '%')
            ->where('MGudangIDAwal', $user->MGudangID)
            ->where('transaction_gudang_barang.hapus', 0)
            //->orWhere('MGudangIDTujuan',$user->MGudangID)
            ->orderByDesc('transaction_gudang_barang.tanggalDibuat', 'transaction_gudang_barang.id')
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();

        $user = Auth::user();
        $check = $this->checkAccess('kirimBarangPesanan.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.kirimBarangPesanan.index', [
                'data' => $data,
                'dataDetail' => $dataDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kirim Barang Pesanan');
        }
    }

    public function searchTGBDate(Request $request)
    {
        $date = $request->input('searchdate');
        $date = explode("-", $date);
        $user = Auth::user();
        $data = DB::table('transaction_gudang_barang')
            ->select('transaction_gudang_barang.*', 'ItemTransaction.Name as itemTransactionName', 'MSupplier.Name as supplierName', 'MSupplier.AtasNama as supplierAtasNama')
            ->leftjoin('ItemTransaction', 'transaction_gudang_barang.ItemTransactionID', '=', 'ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier', 'transaction_gudang_barang.SupplierID', '=', 'MSupplier.SupplierID')
            ->leftjoin('purchase_order', 'transaction_gudang_barang.PurchaseOrderID', '=', 'purchase_order.id')
            ->whereNotNull('SupplierID')
            ->where('isMenerima', 0)
            ->whereBetween('transaction_gudang_barang.tanggalDibuat', [$date[0], $date[1]])
            ->where('MGudangIDAwal', $user->MGudangID)
            ->where('transaction_gudang_barang.hapus', 0)
            //->orWhere('MGudangIDTujuan',$user->MGudangID)
            ->orderByDesc('transaction_gudang_barang.tanggalDibuat', 'transaction_gudang_barang.id')
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        $user = Auth::user();
        $check = $this->checkAccess('kirimBarangPesanan.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.kirimBarangPesanan.index', [
                'data' => $data,
                'dataDetail' => $dataDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kirim Barang Pesanan');
        }
    }

    public function searchTGBNameDate(Request $request)
    {
        $name = $request->input('searchname');
        $date = $request->input('searchdate');
        $date = explode("-", $date);
        $user = Auth::user();
        $data = DB::table('transaction_gudang_barang')
            ->select('transaction_gudang_barang.*', 'ItemTransaction.Name as itemTransactionName', 'MSupplier.Name as supplierName', 'MSupplier.AtasNama as supplierAtasNama')
            ->leftjoin('ItemTransaction', 'transaction_gudang_barang.ItemTransactionID', '=', 'ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier', 'transaction_gudang_barang.SupplierID', '=', 'MSupplier.SupplierID')
            ->leftjoin('purchase_order', 'transaction_gudang_barang.PurchaseOrderID', '=', 'purchase_order.id')
            ->whereNotNull('SupplierID')
            ->where('isMenerima', 0)
            ->where('transaction_gudang_barang.name', 'like', '%' . $name . '%')
            ->whereBetween('transaction_gudang_barang.tanggalDibuat', [$date[0], $date[1]])
            ->where('MGudangIDAwal', $user->MGudangID)
            ->where('transaction_gudang_barang.hapus', 0)
            //->orWhere('MGudangIDTujuan',$user->MGudangID)
            ->orderByDesc('transaction_gudang_barang.tanggalDibuat', 'transaction_gudang_barang.id')
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        $user = Auth::user();
        $check = $this->checkAccess('kirimBarangPesanan.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.kirimBarangPesanan.index', [
                'data' => $data,
                'dataDetail' => $dataDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kirim Barang Pesanan');
        }
    }

    public function print(TransactionGudangBarang $kirimBarangPesanan)
    {


        //
        $user = Auth::user();

        $dataSupplier = DB::table('MSupplier')
            ->get();

        $dataBarang = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->leftjoin('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus', 0)
            ->get();

        $dataBarangTag = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName', 'ItemTagValues.ItemTagID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus', 0)
            ->get();
        //dd($dataBarangTag);
        $dataTag = DB::table('ItemTag')
            ->get();

        $dataGudang = DB::table('MGudang')
            ->get();

        $suratJalan = DB::table('surat_jalan')
            ->where('hapus', 0)
            ->get();
        $suratJalanDetail = DB::table('surat_jalan_detail')
            ->select('surat_jalan_detail.*')
            ->join('surat_jalan', 'surat_jalan_detail.suratJalanID', '=', 'surat_jalan.id')
            ->where('surat_jalan.hapus', 0)
            ->get();


        $dataPurchaseRequestDetail = DB::table('purchase_request_detail')
            ->select('purchase_request_detail.*', 'purchase_request.name', 'Item.ItemName as ItemName', 'Unit.Name as UnitName') //
            ->join('purchase_request', 'purchase_request_detail.idPurchaseRequest', '=', 'purchase_request.id')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->where('purchase_request_detail.jumlahProses', '<', DB::raw('purchase_request_detail.jumlah')) //errorr disini
            ->get();
        //dd($dataPurchaseRequestDetail);

        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*', 'MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();
        $dataItemTransaction = DB::table("ItemTransaction")
            ->get();
        $dataTotalDetail = DB::table('transaction_gudang_barang_detail')
            ->select('transaction_gudang_barang_detail.*', 'purchase_request_detail.id as idPRD', 'Item.ItemName as itemName')
            ->join('purchase_request_detail', 'transaction_gudang_barang_detail.PurchaseRequestDetailID', '=', 'purchase_request_detail.id')
            ->join('Item', 'transaction_gudang_barang_detail.ItemID', '=', 'Item.ItemID')
            ->where('transaction_gudang_barang_detail.transactionID', $kirimBarangPesanan->id)
            ->get();


        $user = Auth::user();
        $check = $this->checkAccess('kirimBarangPesanan.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.kirimBarangPesanan.print', [
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataBarang' => $dataBarang,
                'dataTag' => $dataTag,
                'suratJalan' => $suratJalan,
                'suratJalanDetail' => $suratJalanDetail,
                'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
                'dataGudang' => $dataGudang,
                'dataPurchaseRequest' => $dataPurchaseRequest,
                'transactionGudangBarang' => $kirimBarangPesanan,
                'dataItemTransaction' => $dataItemTransaction,
                'dataTotalDetail' => $dataTotalDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kirim Barang Pesanan');
        }
    }
}
