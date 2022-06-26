<?php

namespace App\Http\Controllers;

use App\Models\AdjustmentStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            ->select('ItemAdjustment.*', 'ItemAdjustmentDetail.*', 'Item.ItemName as itemName', 'Unit.Name as unitName', 'MGudang.cname as gudangName')
            ->join('ItemAdjustmentDetail', 'ItemAdjustment.ItemAdjustmentID', '=', 'ItemAdjustmentDetail.ItemAdjustmentID')
            ->leftjoin('Item', 'ItemAdjustmentDetail.ItemID', '=', 'Item.ItemID')
            ->leftjoin('Unit', 'ItemAdjustmentDetail.UnitID', '=', 'Unit.UnitID')
            ->leftjoin('MGudang', 'ItemAdjustmentDetail.MGudangID', '=', 'MGudang.MGudangID')
            ->where('ItemAdjustment.Deleted', 0)
            ->where('ItemAdjustment.Deleted', 0)
            ->orderByDesc('ItemAdjustment.Tanggal', 'ItemAdjustment.ItemAdjustmentID')
            ->paginate(10);

        $user = Auth::user();

        $check = $this->checkAccess('adjustmentStock.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.adjustmentStok.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Penyesuaian stok');
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

        $dataReport = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
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
            ->get();

        $dataReportUntukStok = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select('MGudang.cname as gudangName', 'ItemInventoryTransactionLine.MGudangID', DB::raw('SUM(ItemInventoryTransactionLine.Quantity) as Quantity'), 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'ItemInventoryTransaction.Date')
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'ItemInventoryTransactionLine.Quantity', 'ItemInventoryTransaction.Date')
            ->get();
        //dd($dataReportUntukStok);



        $user = Auth::user();

        $check = $this->checkAccess('adjustmentStock.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.adjustmentStok.tambah', [
                'dataBarang' => $dataBarang,
                'dataBarangTag' => $dataBarangTag,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'dataReport' => $dataReport,
                'dataReportUntukStok' => $dataReportUntukStok,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Tambah Penyesuaian stok');
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
        $user = Auth::user();
        $data = $request->collect();
        $year = date("Y");
        $month = date("m");

        $dataLokasi = DB::table('MGudang')
            ->select('MKota.*', 'MPerusahaan.cnames as perusahaanCode')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MGudang.MGudangID', '=', $data['MGudangID'])
            ->get();

        $datast = DB::table('ItemAdjustment')
            ->where('name', 'like', 'AS/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/%")
            ->get();

        $totalIndex = str_pad(strval(count($datast) + 1), 4, '0', STR_PAD_LEFT);

        $idAs = DB::table('ItemAdjustment')->insertGetId(
            array(
                'Name' => 'AS/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/" . $totalIndex,
                'Tanggal' => $data['tanggalDibuat'],
                'Description' => $data['keterangan'],
                'Deleted' => 0,
                'CreatedBy' => $user->id,
                'CreatedOn' => date("Y-m-d h:i:sa"),
                'UpdatedBy' => $user->id,
                'UpdatedOn' => date("Y-m-d h:i:sa"),
            )
        );

        $jumlahPerubahan = (float)$data['QuantityBaru'] - (float)$data['QuantityAwal'];
        DB::table('ItemAdjustmentDetail')->insert(
            array(
                'ItemAdjustmentID' => $idAs,
                'ItemID' => $data['ItemID'],
                'MGudangID' => $data['MGudangID'],
                'QuantityAwal' => $data['QuantityAwal'],
                'QuantityBaru' => $data['QuantityBaru'],
                'Selisih' => $jumlahPerubahan,
            )
        );

        $idItemInventoryTransaction = DB::table('ItemInventoryTransaction')->insertGetId(
            array(
                'Name' => 'AS/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/" . $totalIndex,
                'Description' =>  $data['keterangan'],
                'Date' => $data['tanggalDibuat'],
                'EmployeeID' => $user->id,
                'MGudangID' => $data['MGudangID'],
                'AdjustmentID' => $idAs,
                'CreatedBy' => $user->id,
                'CreatedOn' => date("Y-m-d h:i:sa"),
                'UpdatedBy' => $user->id,
                'UpdatedOn' => date("Y-m-d h:i:sa"),
            )
        );

        DB::table('ItemInventoryTransactionLine')
            ->insert(
                array(
                    'TransactionID' => $idItemInventoryTransaction,
                    'ItemID' => $data['ItemID'],
                    'MGudangID' => $data['MGudangID'],
                    'Quantity' => $jumlahPerubahan,
                    //'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
                )
            );

        return redirect()->route('adjustmentStock.index')->with('status', 'Tambah nota penyesuaian stok berhasil');
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
        //
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

        $dataReport = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity")
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransaction.TransactionID', '=', 'ItemInventoryTransactionLine.TransactionID')
            //->where('ItemInventoryTransaction.AdjustmentID','!=', $adjustmentStock['ItemAdjustmentID'])
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
            ->get();

        $dataReportDetailStokAwal = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity")
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransaction.TransactionID', '=', 'ItemInventoryTransactionLine.TransactionID')
            ->where('ItemInventoryTransaction.AdjustmentID', '!=', $adjustmentStock['ItemAdjustmentID'])
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
            ->get();

        //dd($dataReportDetailStokAwal);
        //dd($adjustmentStock);
        $adjustmentStockDetail = DB::table('ItemAdjustmentDetail')
            ->where('ItemAdjustmentID', $adjustmentStock['ItemAdjustmentID'])
            ->get();
        //dd($adjustmentStockDetail);

        $dataReportUntukStok = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select('MGudang.cname as gudangName', 'ItemInventoryTransactionLine.MGudangID', DB::raw('SUM(ItemInventoryTransactionLine.Quantity) as Quantity'), 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'ItemInventoryTransaction.Date')
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'ItemInventoryTransactionLine.Quantity', 'ItemInventoryTransaction.Date')
            ->get();



        $user = Auth::user();

        $check = $this->checkAccess('adjustmentStock.show', $user->id, $user->idRole);
        if ($check) {

            return view('master.note.adjustmentStok.detail', [
                'dataBarang' => $dataBarang,
                'dataBarangTag' => $dataBarangTag,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'dataReport' => $dataReport,
                'dataReportUntukStok' => $dataReportUntukStok,
                'dataReportDetailStokAwal' => $dataReportDetailStokAwal,
                'adjustmentStock' => $adjustmentStock,
                'adjustmentStockDetail' => $adjustmentStockDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Detail Penyesuaian stok');
        }
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

        $dataReport = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity")
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransaction.TransactionID', '=', 'ItemInventoryTransactionLine.TransactionID')
            //->where('ItemInventoryTransaction.AdjustmentID','!=', $adjustmentStock['ItemAdjustmentID'])
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
            ->get();
        //dd($dataReport);

        $dataReportDetailStokAwal = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity")
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransaction.TransactionID', '=', 'ItemInventoryTransactionLine.TransactionID')
            ->where('ItemInventoryTransaction.AdjustmentID', '!=', $adjustmentStock['ItemAdjustmentID'])
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
            ->get();

        //dd($dataReportDetailStokAwal);
        $adjustmentStockDetail = DB::table('ItemAdjustmentDetail')
            ->where('ItemAdjustmentID', $adjustmentStock['ItemAdjustmentID'])
            ->get();
        //dd($adjustmentStockDetail);

        $dataReportUntukStok = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select('MGudang.cname as gudangName', 'ItemInventoryTransactionLine.MGudangID', 'ItemInventoryTransactionLine.Quantity', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'ItemInventoryTransaction.Date')
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->where('ItemInventoryTransaction.AdjustmentID', '!=', $adjustmentStock['ItemAdjustmentID'])
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'ItemInventoryTransactionLine.Quantity', 'ItemInventoryTransaction.Date')
            ->get();
        
        $dataReportUntukStok = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select('MGudang.cname as gudangName', 'ItemInventoryTransactionLine.MGudangID', DB::raw('SUM(ItemInventoryTransactionLine.Quantity) as Quantity'), 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'ItemInventoryTransaction.Date')
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'ItemInventoryTransactionLine.Quantity', 'ItemInventoryTransaction.Date')
            ->get();



        $user = Auth::user();

        $check = $this->checkAccess('adjustmentStock.edit', $user->id, $user->idRole);
        if ($check) {

            return view('master.note.adjustmentStok.edit', [
                'dataBarang' => $dataBarang,
                'dataBarangTag' => $dataBarangTag,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'dataReport' => $dataReport,
                'dataReportUntukStok' => $dataReportUntukStok,
                'dataReportDetailStokAwal' => $dataReportDetailStokAwal,
                'adjustmentStock' => $adjustmentStock,
                'adjustmentStockDetail' => $adjustmentStockDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Ubah Penyesuaian stok');
        }
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
            ->update(
                array(
                    'Tanggal' => $data['tanggalDibuat'],
                    'Description' => $data['keterangan'],
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                )
            );

        $jumlahPerubahan = (float)($data['QuantityBaru']) - (float)($data['QuantityAwal']);
        DB::table('ItemAdjustmentDetail')
            ->where('ItemAdjustmentID', $adjustmentStock['ItemAdjustmentID'])
            ->update(
                array(
                    'ItemID' => $data['ItemID'],
                    'MGudangID' => $data['MGudangID'],
                    'QuantityAwal' => $data['QuantityAwal'],
                    'QuantityBaru' => $data['QuantityBaru'],
                    'Selisih' => $jumlahPerubahan,
                )
            );

        DB::table('ItemInventoryTransaction')
            ->where('AdjustmentID', $adjustmentStock['ItemAdjustmentID'])
            ->insertGetId(
                array(
                    'Description' =>  $data['keterangan'],
                    'Date' => $data['tanggalDibuat'],
                    'EmployeeID' => $user->id,
                    'MGudangID' => $data['MGudangID'],
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                )
            );

        $dataIIT = DB::table('ItemInventoryTransaction')
            ->select('ItemInventoryTransaction.TransactionID')
            ->where('AdjustmentID', $adjustmentStock['ItemAdjustmentID'])
            ->get();

        DB::table('ItemInventoryTransactionLine')
            ->where('ItemInventoryTransactionLine.TransactionID', $dataIIT[0]->TransactionID)
            ->update(
                array(
                    'ItemID' => $data['ItemID'],
                    'MGudangID' => $data['MGudangID'],
                    'Quantity' => $jumlahPerubahan,
                    //'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
                )
            );

        return redirect()->route('adjustmentStock.index')->with('status', 'Ubah nota penyesuaian stok berhasil');
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

        $check = $this->checkAccess('adjustmentStock.edit', $user->id, $user->idRole);
        if ($check) {

            DB::table('ItemAdjustment')
                ->where('ItemAdjustmentID', $adjustmentStock['ItemAdjustmentID'])
                ->update(
                    array(
                        'Deleted' => 1,
                        'UpdatedBy' => $user->id,
                        'UpdatedOn' => date("Y-m-d h:i:sa"),
                    )
                );

            $dataIIT = DB::table('ItemInventoryTransaction')
                ->select('ItemInventoryTransaction.TransactionID')
                ->where('AdjustmentID', $adjustmentStock['ItemAdjustmentID'])
                ->get();

            DB::table('ItemInventoryTransaction')
                ->where('AdjustmentID', $adjustmentStock['id'])
                ->delete();

            DB::table('ItemInventoryTransactionLine')
                ->where('TransactionID', $dataIIT[0]->TransactionID)
                ->delete();

            return redirect()->route('adjustmentStock.index')->with('status', 'Hapus nota penyesuaian stok berhasil');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Hapus Penyesuaian stok');
        }
    }

    public function searchNameAS(Request $request)
    {
        //
        $name = $request->input('searchname');
        $user = Auth::user();

        $data = DB::table('ItemAdjustment')
            ->select('ItemAdjustment.*', 'ItemAdjustmentDetail.*', 'Item.ItemName as itemName', 'Unit.Name as unitName', 'MGudang.cname as gudangName')
            ->join('ItemAdjustmentDetail', 'ItemAdjustment.ItemAdjustmentID', '=', 'ItemAdjustmentDetail.ItemAdjustmentID')
            ->leftjoin('Item', 'ItemAdjustmentDetail.ItemID', '=', 'Item.ItemID')
            ->leftjoin('Unit', 'ItemAdjustmentDetail.UnitID', '=', 'Unit.UnitID')
            ->leftjoin('MGudang', 'ItemAdjustmentDetail.MGudangID', '=', 'MGudang.MGudangID')
            ->where('ItemAdjustment.Deleted', 0)
            ->where('ItemAdjustment.Deleted', 0)
            ->where('ItemAdjustment.Name', 'like', '%' . $name . '%')
            ->orderByDesc('ItemAdjustment.Tanggal', 'ItemAdjustment.ItemAdjustmentID')
            ->paginate(10);


        $user = Auth::user();

        $check = $this->checkAccess('adjustmentStock.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.adjustmentStok.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Penyesuaian stok');
        }
    }

    public function searchDateAS(Request $request)
    {
        //
        $date = $request->input('dateRangeSearch');
        $user = Auth::user();
        $date = explode("-", $date);

        $data = DB::table('ItemAdjustment')
            ->select('ItemAdjustment.*', 'ItemAdjustmentDetail.*', 'Item.ItemName as itemName', 'Unit.Name as unitName', 'MGudang.cname as gudangName')
            ->join('ItemAdjustmentDetail', 'ItemAdjustment.ItemAdjustmentID', '=', 'ItemAdjustmentDetail.ItemAdjustmentID')
            ->leftjoin('Item', 'ItemAdjustmentDetail.ItemID', '=', 'Item.ItemID')
            ->leftjoin('Unit', 'ItemAdjustmentDetail.UnitID', '=', 'Unit.UnitID')
            ->leftjoin('MGudang', 'ItemAdjustmentDetail.MGudangID', '=', 'MGudang.MGudangID')
            ->where('ItemAdjustment.Deleted', 0)
            ->where('ItemAdjustment.Deleted', 0)
            ->whereBetween('ItemAdjustment.Tanggal', [$date[0], $date[1]])
            ->orderByDesc('ItemAdjustment.Tanggal', 'ItemAdjustment.ItemAdjustmentID')
            ->paginate(10);



        $user = Auth::user();

        $check = $this->checkAccess('adjustmentStock.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.adjustmentStok.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Penyesuaian stok');
        }
    }

    public function searchNameDateAS(Request $request)
    {
        //
        $name = $request->input('searchname');
        $date = $request->input('dateRangeSearch');
        $user = Auth::user();
        $date = explode("-", $date);


        $data = DB::table('ItemAdjustment')
            ->select('ItemAdjustment.*', 'ItemAdjustmentDetail.*', 'Item.ItemName as itemName', 'Unit.Name as unitName', 'MGudang.cname as gudangName')
            ->join('ItemAdjustmentDetail', 'ItemAdjustment.ItemAdjustmentID', '=', 'ItemAdjustmentDetail.ItemAdjustmentID')
            ->leftjoin('Item', 'ItemAdjustmentDetail.ItemID', '=', 'Item.ItemID')
            ->leftjoin('Unit', 'ItemAdjustmentDetail.UnitID', '=', 'Unit.UnitID')
            ->leftjoin('MGudang', 'ItemAdjustmentDetail.MGudangID', '=', 'MGudang.MGudangID')
            ->where('ItemAdjustment.Deleted', 0)
            ->where('ItemAdjustment.Deleted', 0)
            ->where('ItemAdjustment.Name', 'like', '%' . $name . '%')
            ->whereBetween('ItemAdjustment.Tanggal', [$date[0], $date[1]])
            ->orderByDesc('ItemAdjustment.Tanggal', 'ItemAdjustment.ItemAdjustmentID')
            ->paginate(10);


        $user = Auth::user();

        $check = $this->checkAccess('adjustmentStock.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.adjustmentStok.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Penyesuaian stok');
        }
    }

    public function print(AdjustmentStock $adjustmentStock)
    {
        //
        //
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
            ->select('MGudang.*', 'MPerusahaan.cname as perusahaanName', 'MPerusahaan.Gambar as perusahaanGambar')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->get();

        $dataReport = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity")
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransaction.TransactionID', '=', 'ItemInventoryTransactionLine.TransactionID')
            //->where('ItemInventoryTransaction.AdjustmentID','!=', $adjustmentStock['ItemAdjustmentID'])
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
            ->get();

        $dataReportDetailStokAwal = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select(
                'MGudang.cname as gudangName',
                'ItemInventoryTransactionLine.MGudangID',
                'ItemInventoryTransactionLine.ItemID',
                'Item.ItemName',
                DB::raw("sum(ItemInventoryTransactionLine.Quantity) as totalQuantity")
            )
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransaction.TransactionID', '=', 'ItemInventoryTransactionLine.TransactionID')
            ->where('ItemInventoryTransaction.AdjustmentID', '!=', $adjustmentStock['ItemAdjustmentID'])
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
            ->get();

        //dd($dataReportDetailStokAwal);
        //dd($adjustmentStock);
        $adjustmentStockDetail = DB::table('ItemAdjustmentDetail')
            ->where('ItemAdjustmentID', $adjustmentStock['ItemAdjustmentID'])
            ->get();
        //dd($adjustmentStockDetail);

        $dataReportUntukStok = DB::table('ItemInventoryTransactionLine') //dibuat untuk check barang di gudang tersebut apaan yang perlu dibeneri stok nya
            ->select('MGudang.cname as gudangName', 'ItemInventoryTransactionLine.MGudangID', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName', 'ItemInventoryTransaction.Date')
            ->join('MGudang', 'ItemInventoryTransactionLine.MGudangID', '=', 'MGudang.MGudangID')
            ->join('Item', 'ItemInventoryTransactionLine.ItemID', '=', 'Item.ItemID')
            ->join('ItemInventoryTransaction', 'ItemInventoryTransactionLine.TransactionID', '=', 'ItemInventoryTransaction.TransactionID')
            ->groupBy('ItemInventoryTransactionLine.MGudangID', 'MGudang.cname', 'ItemInventoryTransactionLine.ItemID', 'Item.ItemName')
            ->get();



        $user = Auth::user();

        $check = $this->checkAccess('adjustmentStock.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.adjustmentStok.detail', [
                'dataBarang' => $dataBarang,
                'dataBarangTag' => $dataBarangTag,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'dataReport' => $dataReport,
                'dataReportUntukStok' => $dataReportUntukStok,
                'dataReportDetailStokAwal' => $dataReportDetailStokAwal,
                'adjustmentStock' => $adjustmentStock,
                'adjustmentStockDetail' => $adjustmentStockDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Print Penyesuaian stok');
        }
    }
}
