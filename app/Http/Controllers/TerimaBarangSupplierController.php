<?php

namespace App\Http\Controllers;

use App\Models\TransactionGudangBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TerimaBarangSupplierController extends Controller
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
            ->join('purchase_order', 'transaction_gudang_barang.PurchaseOrderID', '=', 'purchase_order.id')
            ->where('transaction_gudang_barang.hapus', 0)
            ->whereNotNull('transaction_gudang_barang.SupplierID')
            ->where('transaction_gudang_barang.isMenerima', 1)
            //->where('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID)
            ->where(function ($query) use ($user) {
                $query->where('transaction_gudang_barang.MGudangIDAwal', $user->MGudangID)
                    ->orWhere('transaction_gudang_barang.MGudangIDTujuan', $user->MGudangID);
            })
            ->orderByDesc('transaction_gudang_barang.tanggalDibuat', 'transaction_gudang_barang.id')
            ->paginate(10);
        //dd($data);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        $dataGudang = DB::table('MGudang')
            ->get();


        $check = $this->checkAccess('terimaBarangSupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.terimaBarangSupplier.index', [
                'data' => $data,
                'dataDetail' => $dataDetail,
                'dataGudang' => $dataGudang,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Terima Barang Supplier');
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

        //data Purchase Request yang disetujui
        $dataPurchaseOrderDetail = DB::table('purchase_order_detail')
            ->select(
                'purchase_order_detail.*',
                'purchase_order.name',
                'Item.ItemName as ItemName',
                'Unit.Name as UnitName'
            ) //
            ->join('purchase_order', 'purchase_order_detail.idPurchaseOrder', '=', 'purchase_order.id')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where('purchase_order_detail.jumlahProses', '<', DB::raw('purchase_order_detail.jumlah')) //errorr disini
            ->get();

        $dataPurchaseOrderDetailCombine = DB::table('purchase_order_detail')
            ->select(
                'purchase_order_detail.idPurchaseOrder',
                'purchase_order.name',
                'purchase_order_detail.idItem',
                'purchase_order_detail.harga',
                'purchase_order_detail.idTax',
                'purchase_order_detail.diskon',
                'Item.ItemName as ItemName',
                'Unit.Name as UnitName',
                DB::raw('SUM(purchase_order_detail.jumlah) as jumlah'),
                DB::raw('SUM(purchase_order_detail.jumlahProses) as jumlahProses'),
            ) //
            ->join('purchase_order', 'purchase_order_detail.idPurchaseOrder', '=', 'purchase_order.id')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where(DB::raw('jumlahProses'), '<', DB::raw('jumlah'))
            ->groupBy(
                'purchase_order_detail.idPurchaseOrder',
                'purchase_order.name',
                'purchase_order_detail.idItem',
                'purchase_order_detail.harga',
                'purchase_order_detail.idTax',
                'purchase_order_detail.diskon',
                'Item.ItemName',
                'Unit.Name',
            )
            ->get();
        //dd($dataPurchaseOrderDetailCombine);      
        $dataPurchaseOrder = DB::table('purchase_order')
            ->select('purchase_order.*')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->get();

        $dataGudang = DB::table("MGudang")->get();
        $dataItemTransaction = DB::table("ItemTransaction")->get();
        $date = date("Y-m-d");




        $check = $this->checkAccess('terimaBarangSupplier.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.terimaBarangSupplier.tambah', [
                'dataSupplier' => $dataSupplier,
                'dataGudang' => $dataGudang,
                'dataItemTransaction' => $dataItemTransaction,
                'dataBarangTag' => $dataBarangTag,
                'dataBarang' => $dataBarang,
                'dataTag' => $dataTag,
                'dataPurchaseOrderDetail' => $dataPurchaseOrderDetail,
                'dataPurchaseOrderDetailCombine' => $dataPurchaseOrderDetailCombine,
                'dataPurchaseOrder' => $dataPurchaseOrder,
                'date' => $date
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Terima Barang Supplier');
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

        if (request()->get('itemId') == null || request()->get('itemId') == "") {
            return redirect()->back()->with('status', 'Isikan data keranjang');
        }
        $year = date("Y");
        $month = date("m");

        $dataLokasi = DB::table('MGudang')
            ->select('MKota.*', 'MPerusahaan.cnames as perusahaanCode')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MGudang.MGudangID', '=', $data['MGudangIDTujuan'])
            ->get();

        $dataItemTransaction = DB::table('ItemTransaction')->where('ItemTransactionID', $data['ItemTransaction'])->get();
        $dataPo = DB::table('transaction_gudang_barang')
            ->where('name', 'like', $dataItemTransaction[0]->Code . '/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/%")
            ->get();


        $totalIndex = str_pad(strval(count($dataPo) + 1), 4, '0', STR_PAD_LEFT);

        $idtransaksigudang = DB::table('transaction_gudang_barang')->insertGetId(
            array(
                'name' => $dataItemTransaction[0]->Code . '/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/" . $totalIndex,
                'tanggalDibuat' => $data['tanggalDibuat'],
                'tanggalDatang' => $data['tanggalDatang'],
                'keteranganKendaraan' => $data['keteranganKendaraan'],
                'keteranganNomorPolisi' => $data['keteranganNomorPolisi'],
                'keteranganPemudi' => $data['keteranganPemudi'],
                'keteranganTransaksi' => $data['keteranganTransaksi'],
                'ItemTransactionID' => $data['ItemTransaction'],
                'isMenerima' => 1,
                'SupplierID' => $data['Supplier'],
                'MGudangIDTujuan' => $data['MGudangIDTujuan'],
                'PurchaseOrderID' => $data['poID'],
                'hapus' => 0,
                'CreatedBy' => $user->id,
                'CreatedOn' => date("Y-m-d h:i:sa"),
                'UpdatedBy' => $user->id,
                'CreatedOn' => date("Y-m-d h:i:sa"),
            )
        );

        $idItemInventoryTransaction = DB::table('ItemInventoryTransaction')->insertGetId(
            array(
                'Name' => $dataItemTransaction[0]->Code . '/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/" . $totalIndex,
                'Description' => $data['keteranganTransaksi'],
                'ItemTransactionID' => $data['ItemTransaction'],
                'Date' => $data['tanggalDibuat'],
                'SupplierID' => $data['Supplier'],
                'NTBID' => $idtransaksigudang,
                'EmployeeID' => $user->id,
                'MGudangID' => $data['MGudangIDTujuan'],
                'CreatedBy' => $user->id,
                'CreatedOn' => date("Y-m-d h:i:sa"),
                'UpdatedBy' => $user->id,
                'CreatedOn' => date("Y-m-d h:i:sa"),
            )
        );
        //keluarkan kabeh item, baru bukak pemilihan PO ne sg mana, PO gk ush dipilih misalkan transfer atau kirim barang
        for ($i = 0; $i < count($data['itemId']); $i++) {
            $idtransaksigudangdetail = DB::table('transaction_gudang_barang_detail')->insertGetId(
                array(
                    'transactionID' => $idtransaksigudang,
                    'purchaseOrderDetailID' => $data['podID'][$i], //didapet dari variabel yang disimpen di itemnya(combobox item)
                    'ItemID' => $data['itemId'][$i],
                    'jumlah' => $data['itemJumlah'][$i],
                    'keterangan' => $data['itemKeterangan'][$i],
                    'harga' => $data['itemHarga'][$i], //didapat dri hidden ketika milih barang di PO
                )
            );

            $totalNow = DB::table('purchase_order_detail')->select('jumlah', 'jumlahProses')->where('id', $data['podID'][$i])->get();
            DB::table('purchase_order_detail')
                ->where('id', $data['podID'][$i])
                ->update([
                    'jumlahProses' => $totalNow[0]->jumlahProses + $data['itemJumlah'][$i],
                ]);


            //LOGIC EVERYTHING BECOME 1
            /*
            $totalNow = DB::table('purchase_order_detail')
                ->select('id', 'jumlah', 'jumlahProses')
                ->where('id', $data['poID'][$i])
                ->where('ItemID', $data['itemId'][$i])
                ->get();
            $checkInputJumlahNya = $data['itemJumlah'][$i];
            foreach ($totalNow as $pod) {
                //permainan angka disini HERE!!!!
                $checkJumlah = $pod->jumlah;
                $checkJumlahProses = $pod->jumlahProses;
                $checkInputSisa = $checkJumlah - $checkJumlahProses;
                if ($checkInputSisa <= $checkInputJumlahNya) {
                    $checkInputJumlahNya -= $checkInputSisa;
                    DB::table('purchase_order_detail')
                        ->where('id',  $pod->id)
                        ->update([
                            'jumlahProses' => $pod->jumlahProses + $checkInputSisa,
                        ]);
                } else if ($checkInputSisa > $checkInputJumlahNya && $checkInputJumlahNya != 0) {
                    DB::table('purchase_order_detail')
                        ->where('id',  $pod->id)
                        ->update([
                            'jumlahProses' => $pod->jumlahProses + $checkInputJumlahNya,
                        ]);
                }
            }
*/
            DB::table('purchase_order_detail')
                ->where('id', $data['podID'][$i])
                ->update([
                    'jumlahProses' => $totalNow[0]->jumlahProses + $data['itemJumlah'][$i],
                ]);


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
            //Item Inventory Transaction line positif
            DB::table('ItemInventoryTransactionLine')
                ->insert(
                    array(
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

        //otomatis proses selesai. $data['poID']
        $dataPODAuto = DB::table('purchase_order_detail')
            ->where('idPurchaseOrder', $data['poID'])
            ->get();

        foreach ($dataPODAuto as $pod) {
            if ($pod->jumlah > $pod->jumlahProses) {
                return redirect()->route('terimaBarangSupplier.index')->with('status', 'Success!!');
            }
        }
        //PO SELESAI
        DB::table('purchase_order')
            ->where('id', $data['poID'])
            ->update(array(
                'proses' => 2,
            ));

        return redirect()->route('terimaBarangSupplier.index')->with('status', 'Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionGudang  $transactionGudang
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionGudangBarang $terimaBarangSupplier)
    {
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

        $dataItemTransaction = DB::table("ItemTransaction")->get();

        $dataTotalDetail = DB::table('transaction_gudang_barang_detail')
            ->select('transaction_gudang_barang_detail.*', 'purchase_order_detail.harga as hargaPOD', 'purchase_order_detail.id as idPOD', 'Item.ItemName as itemName')
            ->join('purchase_order_detail', 'transaction_gudang_barang_detail.purchaseOrderDetailID', '=', 'purchase_order_detail.id')
            ->join('Item', 'transaction_gudang_barang_detail.ItemID', '=', 'Item.ItemID')
            ->where('transactionID', $terimaBarangSupplier->id)
            ->get();



        //data Purchase Request yang disetujui
        $dataPurchaseOrderDetail = DB::table('purchase_order_detail')
            ->select(
                'purchase_order_detail.*',
                'purchase_order.name',
                'Item.ItemName as ItemName',
                'Unit.Name as UnitName',
                'transaction_gudang_barang_detail.transactionID',
                'purchase_order.id as poid'
            ) //
            ->join('purchase_order', 'purchase_order_detail.idPurchaseOrder', '=', 'purchase_order.id')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('transaction_gudang_barang_detail', 'purchase_order_detail.id', '=', 'transaction_gudang_barang_detail.purchaseOrderDetailID')
            //->where('transaction_gudang_barang_detail.transactionID', '!=', $terimaBarangSupplier->id)
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where(function ($query) use ($terimaBarangSupplier) {
                $query->when(request('transaction_gudang_barang_detail.transactionID', $terimaBarangSupplier->id), function ($q, $data) {
                    return $q->where(DB::raw('purchase_order_detail.jumlahProses '), '<', DB::raw('purchase_order_detail.jumlah'))
                        ->orWhere(DB::raw('purchase_order_detail.jumlahProses - transaction_gudang_barang_detail.jumlah'), '<', DB::raw('purchase_order_detail.jumlah'));
                });
            })
            /*->where(function($query) use ($user) {
                $query->where(DB::raw('purchase_order_detail.jumlahProses - transaction_gudang_barang_detail.jumlah'),'<', DB::raw('purchase_order_detail.jumlah'))
                    ->orWhere(DB::raw('purchase_order_detail.jumlahProses'),'<', DB::raw('purchase_order_detail.jumlah'));
            })*/
            //->where(DB::raw('purchase_order_detail.jumlahProses - transaction_gudang_barang_detail.jumlah'),'<', DB::raw('purchase_order_detail.jumlah'))//errorr disini
            ->get();


        /*$getIDPO = $dataPurchaseOrderDetail
            ->where("transactionID",'=',$terimaBarangSupplier->id);
        //dd($getIDPO);
        //dd($dataPurchaseOrderDetail);

        $arridpo = array();
        foreach($getIDPO as $id){
            array_push($arridpo, $id->poid);
        }
        $dataPurchaseOrderDetail = $dataPurchaseOrderDetail
            ->where("transactionID",'==',$terimaBarangSupplier->id)
            ->whereIn('poid', $arridpo)
            ->where(function($query) use ($arridpo, $terimaBarangSupplier) {
                $query->where("transactionID",'!=',$terimaBarangSupplier->id)
                    ->whereIn('poid', $arridpo);
            });

        dd($dataPurchaseOrderDetail);
        */
        $dataPurchaseOrder = DB::table('purchase_order')
            ->select('purchase_order.*')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->get();






        $check = $this->checkAccess('terimaBarangSupplier.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.terimaBarangSupplier.detail', [
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataBarang' => $dataBarang,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'dataItemTransaction' => $dataItemTransaction,
                'dataPurchaseOrderDetail' => $dataPurchaseOrderDetail,
                'dataPurchaseOrder' => $dataPurchaseOrder,
                'transactionGudangBarang' => $terimaBarangSupplier,
                'dataTotalDetail' => $dataTotalDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Terima Barang Supplier');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TransactionGudang  $transactionGudang
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionGudangBarang $terimaBarangSupplier)
    {
        //

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

        $dataItemTransaction = DB::table("ItemTransaction")->get();

        $dataTotalDetail = DB::table('transaction_gudang_barang_detail')
            ->select('transaction_gudang_barang_detail.*', 'purchase_order_detail.harga as hargaPOD', 'purchase_order_detail.id as idPOD', 'Item.ItemName as itemName')
            ->join('purchase_order_detail', 'transaction_gudang_barang_detail.purchaseOrderDetailID', '=', 'purchase_order_detail.id')
            ->join('Item', 'transaction_gudang_barang_detail.ItemID', '=', 'Item.ItemID')
            ->where('transactionID', $terimaBarangSupplier->id)
            ->get();



        //data Purchase Request yang disetujui
        $dataPurchaseOrderDetail = DB::table('purchase_order_detail')
            ->select('purchase_order_detail.*', 'purchase_order.name', 'Item.ItemName as ItemName', 'Unit.Name as UnitName', 'transaction_gudang_barang_detail.transactionID', 'purchase_order.id as poid') //
            ->join('purchase_order', 'purchase_order_detail.idPurchaseOrder', '=', 'purchase_order.id')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('transaction_gudang_barang_detail', 'purchase_order_detail.id', '=', 'transaction_gudang_barang_detail.purchaseOrderDetailID')
            //->where('transaction_gudang_barang_detail.transactionID', '!=', $terimaBarangSupplier->id)
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where(function ($query) use ($terimaBarangSupplier) {
                $query->when(request('transaction_gudang_barang_detail.transactionID', $terimaBarangSupplier->id), function ($q, $data) {
                    return $q->where(DB::raw('purchase_order_detail.jumlahProses '), '<', DB::raw('purchase_order_detail.jumlah'))
                        ->orWhere(DB::raw('purchase_order_detail.jumlahProses - transaction_gudang_barang_detail.jumlah'), '<', DB::raw('purchase_order_detail.jumlah'));
                });
            })
            /*->where(function($query) use ($user) {
                $query->where(DB::raw('purchase_order_detail.jumlahProses - transaction_gudang_barang_detail.jumlah'),'<', DB::raw('purchase_order_detail.jumlah'))
                    ->orWhere(DB::raw('purchase_order_detail.jumlahProses'),'<', DB::raw('purchase_order_detail.jumlah'));
            })*/
            //->where(DB::raw('purchase_order_detail.jumlahProses - transaction_gudang_barang_detail.jumlah'),'<', DB::raw('purchase_order_detail.jumlah'))//errorr disini
            ->get();
        $dataPurchaseOrderDetailCombine = DB::table('purchase_order_detail')
            ->select(
                'purchase_order_detail.idPurchaseOrder',
                'purchase_order.name',
                'purchase_order_detail.idItem',
                'purchase_order_detail.harga',
                'purchase_order_detail.idTax',
                'purchase_order_detail.diskon',
                'Item.ItemName as ItemName',
                'Unit.Name as UnitName',
                DB::raw('SUM(purchase_order_detail.jumlah) as jumlah'),
                DB::raw('SUM(purchase_order_detail.jumlahProses) as jumlahProses'),
            ) //
            ->join('purchase_order', 'purchase_order_detail.idPurchaseOrder', '=', 'purchase_order.id')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where(function ($query) use ($terimaBarangSupplier) {
                $query->when(request('transaction_gudang_barang_detail.transactionID', $terimaBarangSupplier->id), function ($q, $data) {
                    return $q->where(DB::raw('jumlahProses'), '<', DB::raw('jumlah'))
                        ->orWhere(DB::raw('jumlahProses - transaction_gudang_barang_detail.jumlah'), '<', DB::raw('jumlah'));
                });
            })
            //->where(DB::raw('jumlahProses'), '<', DB::raw('jumlah'))
            ->groupBy(
                'purchase_order_detail.idPurchaseOrder',
                'purchase_order.name',
                'purchase_order_detail.idItem',
                'purchase_order_detail.harga',
                'purchase_order_detail.idTax',
                'purchase_order_detail.diskon',
                'Item.ItemName',
                'Unit.Name',
            )
            ->get();

        /*$getIDPO = $dataPurchaseOrderDetail
            ->where("transactionID",'=',$terimaBarangSupplier->id);
        //dd($getIDPO);
        //dd($dataPurchaseOrderDetail);

        $arridpo = array();
        foreach($getIDPO as $id){
            array_push($arridpo, $id->poid);
        }
        $dataPurchaseOrderDetail = $dataPurchaseOrderDetail
            ->where("transactionID",'==',$terimaBarangSupplier->id)
            ->whereIn('poid', $arridpo)
            ->where(function($query) use ($arridpo, $terimaBarangSupplier) {
                $query->where("transactionID",'!=',$terimaBarangSupplier->id)
                    ->whereIn('poid', $arridpo);
            });

        dd($dataPurchaseOrderDetail);
        */
        $dataPurchaseOrder = DB::table('purchase_order')
            ->select('purchase_order.*')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->get();







        $check = $this->checkAccess('terimaBarangSupplier.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.terimaBarangSupplier.edit', [
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataBarang' => $dataBarang,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'dataItemTransaction' => $dataItemTransaction,
                'dataPurchaseOrderDetail' => $dataPurchaseOrderDetail,
                'dataPurchaseOrder' => $dataPurchaseOrder,
                'transactionGudangBarang' => $terimaBarangSupplier,
                'dataTotalDetail' => $dataTotalDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Terima Barang Supplier');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransactionGudang  $transactionGudang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionGudangBarang $terimaBarangSupplier)
    {
        $user = Auth::user();
        $data = $request->collect();

        if (request()->get('itemId') == null || request()->get('itemId') == "") {
            return redirect()->back()->with('status', 'Isikan data keranjang');
        }

        DB::table('transaction_gudang_barang')
            ->where('id', $terimaBarangSupplier->id)
            ->update(
                array(
                    'tanggalDibuat' => $data['tanggalDibuat'],
                    'tanggalDatang' => $data['tanggalDatang'],
                    'keteranganKendaraan' => $data['keteranganKendaraan'],
                    'keteranganNomorPolisi' => $data['keteranganNomorPolisi'],
                    'keteranganPemudi' => $data['keteranganPemudi'],
                    'keteranganTransaksi' => $data['keteranganTransaksi'],
                    'ItemTransactionID' => $data['ItemTransaction'],
                    'SupplierID' => $data['Supplier'],
                    'MGudangIDTujuan' => $data['MGudangIDTujuan'],
                    'PurchaseOrderID' => $data['poID'],
                    'hapus' => 0,
                    'UpdatedBy' => $user->id,
                    'CreatedOn' => date("Y-m-d h:i:sa"),
                )
            );

        DB::table('ItemInventoryTransaction')
            ->where('NTBID', $terimaBarangSupplier->id)
            ->update(
                array(
                    'Description' => $data['keteranganTransaksi'],
                    'ItemTransactionID' => $data['ItemTransaction'],
                    'Date' => $data['tanggalDibuat'],
                    'SupplierID' => $data['Supplier'],
                    'EmployeeID' => $user->id,
                    'MGudangID' => $data['MGudangIDTujuan'],
                    'UpdatedBy' => $user->id,
                    'CreatedOn' => date("Y-m-d h:i:sa"),
                )
            );

        $dataTransactionID = DB::table('ItemInventoryTransaction')
            ->where('NTBID', $terimaBarangSupplier->id)
            ->get();

        $dataDetailTotal = DB::table('transaction_gudang_barang_detail')
            ->where('transactionID', $terimaBarangSupplier->id)
            ->get();

        //pengurangan jumlah proses lalu diupdate
        foreach ($dataDetailTotal as $data) {
            /*DB::table('purchase_order_detail')
            ->where('id', $data->purchaseOrderDetailID)
            ->update([
                'jumlahProses' => DB::raw('jumlahProses' - $data->jumlah),
            ]);*/
            DB::table('purchase_order_detail')
                ->where('id', $data->purchaseOrderDetailID)
                ->decrement('jumlahProses', $data->jumlah);
        }

        DB::table('transaction_gudang_barang_detail')
            ->where('transactionID', $terimaBarangSupplier->id)
            ->delete();
        DB::table('ItemInventoryTransactionLine')
            ->where('TransactionID', $dataTransactionID[0]->TransactionID)
            ->delete();
        //keluarkan kabeh item, baru bukak pemilihan PO ne sg mana, PO gk ush dipilih misalkan transfer atau kirim barang
        for ($i = 0; $i < count(request()->get('itemId')); $i++) {
            $idtransaksigudangdetail = DB::table('transaction_gudang_barang_detail')->insertGetId(
                array(
                    'transactionID' => $terimaBarangSupplier->id,
                    'purchaseOrderDetailID' => request()->get('podID')[$i],
                    'ItemID' => request()->get('itemId')[$i],
                    'jumlah' => request()->get('itemJumlah')[$i],
                    'keterangan' => request()->get('itemKeterangan')[$i],
                    'harga' => request()->get('itemHarga')[$i], //didapat dri hidden ketika milih barang di PO
                )
            );

            $totalNow = DB::table('purchase_order_detail')->select('jumlah', 'jumlahProses')->where('id', request()->get('podID')[$i])->get();
            DB::table('purchase_order_detail')
                ->where('id', request()->get('podID')[$i])
                ->update([
                    'jumlahProses' => $totalNow[0]->jumlahProses + request()->get('itemJumlah')[$i],
                ]);

            $dataItem = DB::table('Item')
                ->select('Unit.UnitID as unit')
                ->leftjoin('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
                ->where('Item.ItemID', request()->get('itemId')[$i])
                ->get();
            $dataPOD = DB::table('purchase_order_detail')
                ->select('harga')
                ->where('idItem', request()->get('itemId')[$i])
                ->orderBy('idItem', 'desc')
                ->limit(1)
                ->get();
            //Item Inventory Transaction line positif
            DB::table('ItemInventoryTransactionLine')
                ->insert(
                    array(
                        'TransactionID' => $dataTransactionID[0]->TransactionID,
                        'ItemID' => request()->get('itemId')[$i],
                        'MGudangID' => $data['MGudangIDTujuan'],
                        'UnitID' => $dataItem[0]->unit,
                        'UnitPrice' => $dataPOD[0]->harga,
                        'Quantity' => request()->get('itemJumlah')[$i],
                        'TotalUnitPrice' => $dataPOD[0]->harga * request()->get('itemJumlah')[$i],
                    )
                );
            /* DB::table('ItemInventoryTransactionLine')
                ->where('TransactionID', $dataTransactionID[0]->TransactionID)
                ->update(array(
                    'ItemID' => $data['itemId'][$i],  
                    'MGudangID' => $data['MGudangIDTujuan'],  
                    'UnitID' => $dataItem[0]->unit,  
                    'UnitPrice' => $dataPOD[0]->harga,  
                    'Quantity' => $data['itemJumlah'][$i],  
                    'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
                )
            );*/
        }

        //otomatis proses selesai. $data['poID']
        $dataPODAuto = DB::table('purchase_order_detail')
            ->where('idPurchaseOrder', $data['poID'])
            ->get();

        foreach ($dataPODAuto as $pod) {
            if ($pod->jumlah > $pod->jumlahProses) {
                DB::table('purchase_order')
                    ->where('id', $data['poID'])
                    ->update(array(
                        'proses' => 1,
                    ));
                return redirect()->route('terimaBarangSupplier.index')->with('status', 'Success!!');
            }
        }
        //PO SELESAI
        DB::table('purchase_order')
            ->where('id', $data['poID'])
            ->update(array(
                'proses' => 2,
            ));

        return redirect()->route('terimaBarangSupplier.index')->with('status', 'Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransactionGudang  $transactionGudang
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionGudangBarang $terimaBarangSupplier)
    {
        //

        //
        $user = Auth::user();
        $check = $this->checkAccess('terimaBarangSupplier.edit', $user->id, $user->idRole);
        if ($check) {
            $dataTransactionID = DB::table('ItemInventoryTransaction')
                ->where('NTBID', $terimaBarangSupplier->id)
                ->get();
            DB::table('ItemInventoryTransactionLine')
                ->where('TransactionID', $dataTransactionID[0]->TransactionID)
                ->delete();


            $data = DB::table('transaction_gudang_barang_detail')
                ->where('transactionID', '=', $terimaBarangSupplier->id)
                ->get();

            foreach ($data as $d) {
                DB::table('purchase_order_detail')
                    ->where('id', $d->purchaseOrderDetailID)
                    ->decrement('jumlahProses', $d->jumlah);
            }

            DB::table('transaction_gudang_barang_detail')
                ->where('transactionID', '=', $terimaBarangSupplier->id)
                ->delete();

            DB::table('transaction_gudang_barang')
                ->where('id', '=', $terimaBarangSupplier->id)
                ->update(array(
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                    'hapus' => 1,
                ));



            return redirect()->route('terimaBarangSupplier.index')->with('status', 'Success!!');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Terima Barang Supplier');
            //dd($terimaBarangSupplier->id);
        }
    }

    public function searchTGBName(Request $request)
    {
        $name = $request->input('searchname');

        $user = Auth::user();
        $data = DB::table('transaction_gudang_barang')
            ->select('transaction_gudang_barang.*', 'ItemTransaction.Name as itemTransactionName', 'MSupplier.Name as supplierName', 'MSupplier.AtasNama as supplierAtasNama')
            ->leftjoin('ItemTransaction', 'transaction_gudang_barang.ItemTransactionID', '=', 'ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier', 'transaction_gudang_barang.SupplierID', '=', 'MSupplier.SupplierID')
            ->join('purchase_order', 'transaction_gudang_barang.PurchaseOrderID', '=', 'purchase_order.id')
            ->where('transaction_gudang_barang.hapus', 0)
            ->whereNotNull('transaction_gudang_barang.SupplierID')
            ->where('transaction_gudang_barang.isMenerima', 1)
            ->where('transaction_gudang_barang.name', 'like', '%' . $name . '%')
            //->where('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID)
            ->where(function ($query) use ($user) {
                $query->where('transaction_gudang_barang.MGudangIDAwal', $user->MGudangID)
                    ->orWhere('transaction_gudang_barang.MGudangIDTujuan', $user->MGudangID);
            })
            ->orderByDesc('transaction_gudang_barang.tanggalDibuat', 'transaction_gudang_barang.id')
            ->paginate(10);
        //dd($data);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        $dataGudang = DB::table('MGudang')
            ->get();

        $check = $this->checkAccess('terimaBarangSupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.terimaBarangSupplier.index', [
                'data' => $data,
                'dataDetail' => $dataDetail,
                'dataGudang' => $dataGudang,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Terima Barang Supplier');
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
            ->join('purchase_order', 'transaction_gudang_barang.PurchaseOrderID', '=', 'purchase_order.id')
            ->where('transaction_gudang_barang.hapus', 0)
            ->whereNotNull('transaction_gudang_barang.SupplierID')
            ->where('transaction_gudang_barang.isMenerima', 1)
            ->whereBetween('transaction_gudang_barang.tanggalDibuat', [$date[0], $date[1]])
            //->where('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID)
            ->where(function ($query) use ($user) {
                $query->where('transaction_gudang_barang.MGudangIDAwal', $user->MGudangID)
                    ->orWhere('transaction_gudang_barang.MGudangIDTujuan', $user->MGudangID);
            })
            ->orderByDesc('transaction_gudang_barang.tanggalDibuat', 'transaction_gudang_barang.id')
            ->paginate(10);
        //dd($data);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        $dataGudang = DB::table('MGudang')
            ->get();

        $check = $this->checkAccess('terimaBarangSupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.terimaBarangSupplier.index', [
                'data' => $data,
                'dataDetail' => $dataDetail,
                'dataGudang' => $dataGudang,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Terima Barang Supplier');
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
            ->join('purchase_order', 'transaction_gudang_barang.PurchaseOrderID', '=', 'purchase_order.id')
            ->where('transaction_gudang_barang.hapus', 0)
            ->whereNotNull('transaction_gudang_barang.SupplierID')
            ->where('transaction_gudang_barang.isMenerima', 1)
            ->where('transaction_gudang_barang.name', 'like', '%' . $name . '%')
            ->whereBetween('transaction_gudang_barang.tanggalDibuat', [$date[0], $date[1]])
            //->where('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID)
            ->where(function ($query) use ($user) {
                $query->where('transaction_gudang_barang.MGudangIDAwal', $user->MGudangID)
                    ->orWhere('transaction_gudang_barang.MGudangIDTujuan', $user->MGudangID);
            })
            ->orderByDesc('transaction_gudang_barang.tanggalDibuat', 'transaction_gudang_barang.id')
            ->paginate(10);
        //dd($data);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        $dataGudang = DB::table('MGudang')
            ->get();

        $check = $this->checkAccess('terimaBarangSupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.terimaBarangSupplier.index', [
                'data' => $data,
                'dataDetail' => $dataDetail,
                'dataGudang' => $dataGudang,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Terima Barang Supplier');
        }
    }


    public function print(TransactionGudangBarang $terimaBarangSupplier)
    {
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
            ->select('MGudang.*', 'MPerusahaan.cname as perusahaanName', 'MPerusahaan.Gambar as perusahaanGambar')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->get();

        $dataItemTransaction = DB::table("ItemTransaction")->get();

        $dataTotalDetail = DB::table('transaction_gudang_barang_detail')
            ->select('transaction_gudang_barang_detail.*', 'purchase_order_detail.harga as hargaPOD', 'purchase_order_detail.id as idPOD', 'Item.ItemName as itemName')
            ->join('purchase_order_detail', 'transaction_gudang_barang_detail.purchaseOrderDetailID', '=', 'purchase_order_detail.id')
            ->join('Item', 'transaction_gudang_barang_detail.ItemID', '=', 'Item.ItemID')
            ->where('transactionID', $terimaBarangSupplier->id)
            ->get();



        //data Purchase Request yang disetujui
        $dataPurchaseOrderDetail = DB::table('purchase_order_detail')
            ->select('purchase_order_detail.*', 'purchase_order.name', 'Item.ItemName as ItemName', 'Unit.Name as UnitName', 'transaction_gudang_barang_detail.transactionID', 'purchase_order.id as poid') //
            ->join('purchase_order', 'purchase_order_detail.idPurchaseOrder', '=', 'purchase_order.id')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('transaction_gudang_barang_detail', 'purchase_order_detail.id', '=', 'transaction_gudang_barang_detail.purchaseOrderDetailID')
            //->where('transaction_gudang_barang_detail.transactionID', '!=', $terimaBarangSupplier->id)
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where(function ($query) use ($terimaBarangSupplier) {
                $query->when(request('transaction_gudang_barang_detail.transactionID', $terimaBarangSupplier->id), function ($q, $data) {
                    return $q->where(DB::raw('purchase_order_detail.jumlahProses '), '<', DB::raw('purchase_order_detail.jumlah'))
                        ->orWhere(DB::raw('purchase_order_detail.jumlahProses - transaction_gudang_barang_detail.jumlah'), '<', DB::raw('purchase_order_detail.jumlah'));
                });
            })
            ->get();

        $dataPurchaseOrder = DB::table('purchase_order')
            ->select('purchase_order.*')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->get();






        $check = $this->checkAccess('terimaBarangSupplier.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.note.terimaBarangSupplier.print', [
                'dataSupplier' => $dataSupplier,
                'dataBarangTag' => $dataBarangTag,
                'dataBarang' => $dataBarang,
                'dataTag' => $dataTag,
                'dataGudang' => $dataGudang,
                'dataItemTransaction' => $dataItemTransaction,
                'dataPurchaseOrderDetail' => $dataPurchaseOrderDetail,
                'dataPurchaseOrder' => $dataPurchaseOrder,
                'transactionGudangBarang' => $terimaBarangSupplier,
                'dataTotalDetail' => $dataTotalDetail,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Terima Barang Supplier');
        }
    }
}
