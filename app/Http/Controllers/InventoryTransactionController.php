<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\MGudang;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InventoryTransactionController extends Controller
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
        $data = DB::table('ItemInventoryTransaction')
            ->select('ItemInventoryTransaction.*', 'ItemTransaction.Name as itemTransactionName', 'MSupplier.Name as supplierName', 'MGudang.cname as gudangName', 'MGudang.ccode as gudangCode')
            ->leftjoin('ItemTransaction', 'ItemInventoryTransaction.ItemTransactionID', '=', 'ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier', 'ItemInventoryTransaction.SupplierID', '=', 'MSupplier.SupplierID')
            ->leftjoin('MGudang', 'ItemInventoryTransaction.MGudangID', '=', 'MGudang.MGudangID')
            ->paginate(10);

        $dataDetail = DB::table('ItemInventoryTransactionLine')
            ->get();

        $dataReport = DB::table('ItemInventoryTransactionLine')
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity")
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
            ->paginate(10);
        //dd($dataReport);

        $dataGudang = DB::table("MGudang")
            ->get();

        $dataItem = DB::table("Item")
            ->select("Item.ItemID", "Unit.Name")
            ->join("Unit", 'Item.UnitID', '=', 'Unit.UnitID')
            ->get();


        $user = Auth::user();

        $check = $this->checkAccess('inventoryTransaction.index', $user->id, $user->idRole);
        if ($check) {
            return view('kartuStok.index', [
                'data' => $data,
                'dataDetail' => $dataDetail,
                'dataReport' => $dataReport,
                'dataGudang' => $dataGudang,
                'dataItem' => $dataItem,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kartu Stok');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Tidak ada

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ////Tidak ada
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryTransaction  $inventoryTransaction
     * @return \Illuminate\Http\Response
     */
    public function show($mGudang, $item)
    {
        //
        dd($mGudang);
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

        $transaksiGudangBarang = DB::table('transaction_gudang_barang')
            ->get();
        $transaksiGudangBarangDetail = DB::table('transaction_gudang_barang_detail')
            ->get();

        $stokAwal = DB::table('StokAwal')
            ->get();

        $inventoryTransaction = DB::table('ItemInventoryTransaction')
            ->get();

        $dataReport = DB::table('ItemInventoryTransactionLine')
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity")
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->where('ItemInventoryTransactionLine.MGudangID', $mGudang)
            ->where('ItemInventoryTransactionLine.ItemID', $item)
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
            ->get();


        $user = Auth::user();

        $check = $this->checkAccess('inventoryTransaction.index', $user->id, $user->idRole);
        if ($check) {
            return view('kartuStok.detail', [
                //'inventoryTransaction' => $inventoryTransaction,
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'suratJalan' => $suratJalan,
                'suratJalanDetail' => $suratJalanDetail,
                'transaksiGudangBarang' => $transaksiGudangBarang,
                'transaksiGudangBarangDetail' => $transaksiGudangBarangDetail,
                'stokAwal' => $stokAwal,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kartu Stok');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryTransaction  $inventoryTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryTransaction $inventoryTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryTransaction  $inventoryTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryTransaction $inventoryTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryTransaction  $inventoryTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        //
    }


    public function searchIndexByGudang(Request $request) // dibikin report sapa tau butuh buat printshow e dibeken modal ato seng biasa
    {
        //pas juga semua nota kasik tombol print (layout sama kayak approve gapapa, di approve tambahi total keseluruhan kayak di nota biasa be tempat buat tanda tangan gitu) ya
        $gudang = $request->input('searchgudangid');
        $user = Auth::user();

        $data = DB::table('ItemInventoryTransaction')
            ->select('ItemInventoryTransaction.*', 'ItemTransaction.Name as itemTransactionName', 'MSupplier.Name as supplierName', 'MGudang.cname as gudangName', 'MGudang.ccode as gudangCode')
            ->leftjoin('ItemTransaction', 'ItemInventoryTransaction.ItemTransactionID', '=', 'ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier', 'ItemInventoryTransaction.SupplierID', '=', 'MSupplier.SupplierID')
            ->leftjoin('MGudang', 'ItemInventoryTransaction.MGudangID', '=', 'MGudang.MGudangID')
            ->paginate(10);
        $dataDetail = DB::table('ItemInventoryTransactionLine')
            ->get();

        if ($gudang == "" || $gudang == null) {
            $dataReport = DB::table('ItemInventoryTransactionLine') //index mbek show tok?? nggeh sek njokok sate yo
                ->select('MGudang.cname as gudangName', 'ItemInventoryTransactionLine.MGudangID', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity"))
                ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
                ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
                ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
                ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
                ->paginate(10);
        } else {
            $dataReport = DB::table('ItemInventoryTransactionLine') //index mbek show tok?? nggeh sek njokok sate yo
                ->select('MGudang.cname as gudangName', 'ItemInventoryTransactionLine.MGudangID', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity"))
                ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
                ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
                ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
                ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
                ->where('ItemInventoryTransactionLine.MGudangID', '=', $gudang)
                ->paginate(10);
        }

        //dd($dataReport);
        $dataItem = DB::table("Item")
            ->select("Item.ItemID", "Unit.Name")
            ->join("Unit", 'Item.UnitID', '=', 'Unit.UnitID')
            ->get();
        $dataGudang = DB::table("MGudang")->get();


        $user = Auth::user();

        $check = $this->checkAccess('inventoryTransaction.index', $user->id, $user->idRole);
        if ($check) {
            return view('kartuStok.index', [
                'data' => $data,
                'dataDetail' => $dataDetail,
                'dataReport' => $dataReport,
                'dataGudang' => $dataGudang,
                'gudang' => $gudang,
                'dataItem' => $dataItem,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kartu Stok');
        }
    }

    public function report($mGudang, $item)
    {

        //dd($mGudang);
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

        $transaksiGudangBarang = DB::table('transaction_gudang_barang')
            ->get();
        $transaksiGudangBarangDetail = DB::table('transaction_gudang_barang_detail')
            ->get();

        $stokAwal = DB::table('StokAwal')
            ->get();

        $inventoryTransaction = DB::table('ItemInventoryTransaction')
            ->get();

        $dataReport = DB::table('ItemInventoryTransactionLine')
            //->select('MGudang.cname as gudangName','ItemInventoryTransactionLine.MGudangID','ItemInventoryTransactionLine.ItemID','Item.ItemName',)
            ->select('ItemInventoryTransaction.*', 'ItemInventoryTransactionLine.*', 'MGudang.cname as gudangName', 'Item.ItemName', 'ItemTransaction.Name as tipeTransaksi')
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->leftjoin('ItemTransaction', 'ItemInventoryTransaction.ItemTransactionID', '=', 'ItemTransaction.ItemTransactionID')
            ->where('ItemInventoryTransactionLine.MGudangID', $mGudang)
            ->where('ItemInventoryTransactionLine.ItemID', $item)
            ->get();
        //dd($dataReport);
        $dataReportSingle = DB::table('ItemInventoryTransactionLine')
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName as namaBarang',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity"),
                'MPerusahaan.cname as perusahaanName',
                'Unit.Name as satuan'
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID') //asfasfasfasf seng pake modals
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->where('ItemInventoryTransactionLine.MGudangID', $mGudang)
            ->where('ItemInventoryTransactionLine.ItemID', $item)
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'MPerusahaan.cname', 'Unit.Name')
            ->get();
        //dd($dataReportSingle);


        $user = Auth::user();

        $check = $this->checkAccess('inventoryTransaction.show', $user->id, $user->idRole);
        if ($check) {

            return view('kartuStok.detail', [
                //'inventoryTransaction' => $inventoryTransaction,
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'suratJalan' => $suratJalan,
                'suratJalanDetail' => $suratJalanDetail,
                'transaksiGudangBarang' => $transaksiGudangBarang,
                'transaksiGudangBarangDetail' => $transaksiGudangBarangDetail,
                'stokAwal' => $stokAwal,
                'dataReport' => $dataReport,
                'dataReportSingle' => $dataReportSingle,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kartu Stok');
        }
    }

    public function reportLengkap(Request $request)
    {

        //dd($mGudang);
        $gudang = $request->input('searchgudangid');
        $item = $request->input('searchitemid');
        $dateLengkap = $request->input('searchdateid');

        if (!isset($gudang)) $gudang = "";
        if (!isset($item)) $item = "";

        $date = explode("-", $dateLengkap);

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

        $transaksiGudangBarang = DB::table('transaction_gudang_barang')
            ->get();
        $transaksiGudangBarangDetail = DB::table('transaction_gudang_barang_detail')
            ->get();

        $stokAwal = DB::table('StokAwal')
            ->get();

        $inventoryTransaction = DB::table('ItemInventoryTransaction')
            ->get();

        $checkDataSemua = 0;
        $dataReport = null;
        $dataReportSingle = null;
        $dataReportSingleSebelum = null;
        $dataReportItem = DB::table('ItemInventoryTransactionLine')
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity")
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
            ->get();
        if ($gudang == null || $item == null || $date == null || $gudang == "" || $item == "" || $date == "") {
            //dd($dataReport);
            return view('kartuStok.indexlengkap', [
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'suratJalan' => $suratJalan,
                'suratJalanDetail' => $suratJalanDetail,
                'transaksiGudangBarang' => $transaksiGudangBarang,
                'transaksiGudangBarangDetail' => $transaksiGudangBarangDetail,
                'stokAwal' => $stokAwal,
                'dataReport' => $dataReport,
                'dataReportSingle' => $dataReportSingle,
                'dataReportSingleSebelum' => $dataReportSingleSebelum,
                'dataReportItem' => $dataReportItem,
                'checkDataSemua' => $checkDataSemua,
                'gudang' => $gudang,
                'item' => $item,
                'dateLengkap' => $dateLengkap,
            ]);
        }
        $checkDataSemua = 1;


        $dataReport = DB::table('ItemInventoryTransactionLine')
            //->select('MGudang.cname as gudangName','ItemInventoryTransactionLine.MGudangID','ItemInventoryTransactionLine.ItemID','Item.ItemName',)
            ->select('ItemInventoryTransaction.*', 'ItemInventoryTransactionLine.*', 'MGudang.cname as gudangName', 'Item.ItemName', 'ItemTransaction.Name as tipeTransaksi')
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->leftjoin('ItemTransaction', 'ItemInventoryTransaction.ItemTransactionID', '=', 'ItemTransaction.ItemTransactionID')
            ->where('ItemInventoryTransactionLine.MGudangID', $gudang)
            ->where('ItemInventoryTransactionLine.ItemID', $item)
            ->whereBetween('ItemInventoryTransaction.Date', [$date[0], $date[1]])
            ->get();
        //dd($dataReport);
        $dataReportSingle = DB::table('ItemInventoryTransactionLine')
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName as namaBarang',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity"),
                'MPerusahaan.cname as perusahaanName',
                'Unit.Name as satuan'
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID') //asfasfasfasf seng pake modals
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->where('ItemInventoryTransactionLine.MGudangID', $gudang)
            ->where('ItemInventoryTransactionLine.ItemID', $item)
            ->whereDate('ItemInventoryTransaction.Date', '<=', $date[1])
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'MPerusahaan.cname', 'Unit.Name')
            ->get();
        

        $dataReportSingleSebelum = DB::table('ItemInventoryTransactionLine')
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName as namaBarang',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity"),
                'MPerusahaan.cname as perusahaanName',
                'Unit.Name as satuan'
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID') //asfasfasfasf seng pake modal
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->where('ItemInventoryTransactionLine.MGudangID', $gudang)
            ->where('ItemInventoryTransactionLine.ItemID', $item)
            ->whereDate('ItemInventoryTransaction.Date', '<', $date[0])
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'MPerusahaan.cname', 'Unit.Name')
            ->get();
        //dd($dataReportSingleSebelum);


        $user = Auth::user();

        $check = $this->checkAccess('inventoryTransaction.show', $user->id, $user->idRole);
        if ($check) {

            //dd($gudang);
            return view('kartuStok.indexlengkap', [
                //'inventoryTransaction' => $inventoryTransaction,
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'suratJalan' => $suratJalan,
                'suratJalanDetail' => $suratJalanDetail,
                'transaksiGudangBarang' => $transaksiGudangBarang,
                'transaksiGudangBarangDetail' => $transaksiGudangBarangDetail,
                'stokAwal' => $stokAwal,
                'dataReport' => $dataReport,
                'dataReportSingle' => $dataReportSingle,
                'dataReportSingleSebelum' => $dataReportSingleSebelum,
                'dataReportItem' => $dataReportItem,
                'checkDataSemua' => $checkDataSemua,
                'gudang' => $gudang,
                'item' => $item,
                'dateLengkap' => $dateLengkap,
                'dateLengkapPisah' => $date[0],
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kartu Stok');
        }
    }
    public function print($mGudang, $item)
    {

        //dd($mGudang);
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

        $transaksiGudangBarang = DB::table('transaction_gudang_barang')
            ->get();
        $transaksiGudangBarangDetail = DB::table('transaction_gudang_barang_detail')
            ->get();

        $stokAwal = DB::table('StokAwal')
            ->get();

        $inventoryTransaction = DB::table('ItemInventoryTransaction')
            ->get();

        $dataReport = DB::table('ItemInventoryTransactionLine')
            //->select('MGudang.cname as gudangName','ItemInventoryTransactionLine.MGudangID','ItemInventoryTransactionLine.ItemID','Item.ItemName',)
            ->select('ItemInventoryTransaction.*', 'ItemInventoryTransactionLine.*', 'MGudang.cname as gudangName', 'Item.ItemName', 'ItemTransaction.Name as tipeTransaksi')
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->leftjoin('ItemTransaction', 'ItemInventoryTransaction.ItemTransactionID', '=', 'ItemTransaction.ItemTransactionID')
            ->where('ItemInventoryTransactionLine.MGudangID', $mGudang)
            ->where('ItemInventoryTransactionLine.ItemID', $item)
            ->get();
        //dd($dataReport);
        $dataReportSingle = DB::table('ItemInventoryTransactionLine')
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName as namaBarang',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity"),
                'MPerusahaan.cname as perusahaanName',
                'Unit.Name as satuan'
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID') //asfasfasfasf seng pake modals
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->where('ItemInventoryTransactionLine.MGudangID', $mGudang)
            ->where('ItemInventoryTransactionLine.ItemID', $item)
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'MPerusahaan.cname', 'Unit.Name')
            ->get();
        //dd($dataReportSingle);



        $user = Auth::user();

        $check = $this->checkAccess('inventoryTransaction.show', $user->id, $user->idRole);
        if ($check) {
            return view('kartuStok.detail', [
                //'inventoryTransaction' => $inventoryTransaction,
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'suratJalan' => $suratJalan,
                'suratJalanDetail' => $suratJalanDetail,
                'transaksiGudangBarang' => $transaksiGudangBarang,
                'transaksiGudangBarangDetail' => $transaksiGudangBarangDetail,
                'stokAwal' => $stokAwal,
                'dataReport' => $dataReport,
                'dataReportSingle' => $dataReportSingle,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kartu Stok');
        }
    }
}
