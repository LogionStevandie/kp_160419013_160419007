<?php

namespace App\Http\Controllers;

use App\Models\TransactionGudangBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

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
            ->select('transaction_gudang_barang.*','ItemTransaction.Name as itemTransactionName','MSupplier.Name as supplierName', 'MSupplier.AtasNama as supplierAtasNama')
            ->leftjoin('ItemTransaction','transaction_gudang_barang.ItemTransactionID','=','ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier','transaction_gudang_barang.SupplierID','=','MSupplier.SupplierID')
            ->leftjoin('purchase_order','transaction_gudang_barang.PurchaseOrderID','=','purchase_order.id')
            ->whereNotNull('transaction_gudang_barang.SupplierID')
            ->where('transaction_gudang_barang.hapus',0)
            ->where('transaction_gudang_barang.MGudangIDAwal',$user->MGudangID)
            ->orWhere('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID)
            ->paginate(10);
        //dd($data);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        $dataGudang = DB::table('MGudang')
            ->get();
        return view('master.note.terimaBarangSupplier.index',[
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
        
        //data Purchase Request yang disetujui
        $dataPurchaseOrderDetail = DB::table('purchase_order_detail')
            ->select('purchase_order_detail.*','purchase_order.name','Item.ItemName as ItemName','Unit.Name as UnitName')//
            ->join('purchase_order', 'purchase_order_detail.idPurchaseOrder', '=','purchase_order.id')
            ->join('Item','purchase_order_detail.idItem','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where('purchase_order_detail.jumlahProses', '<', DB::raw('purchase_order_detail.jumlah'))//errorr disini
            ->get();
        //dd($dataPurchaseRequestDetail);      
        $dataPurchaseOrder = DB::table('purchase_order')
            ->select('purchase_order.*')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->get();
        
        $dataGudang = DB::table("MGudang")->get();
        $dataItemTransaction = DB::table("ItemTransaction")->get();

        return view('master.note.terimaBarangSupplier.tambah',[
            'dataSupplier' => $dataSupplier,
            'dataGudang' => $dataGudang,
            'dataItemTransaction' => $dataItemTransaction,
            'dataBarangTag' => $dataBarangTag,
            'dataBarang' => $dataBarang,
            'dataTag' => $dataTag,
            'dataPurchaseOrderDetail' => $dataPurchaseOrderDetail,
            'dataPurchaseOrder' => $dataPurchaseOrder,
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
        
        //
        $user = Auth::user();
        $data = $request->collect();
        $year = date("Y");
        $month = date("m");

        $dataLokasi = DB::table('MGudang')
            ->select('MKota.*','MPerusahaan.cnames as perusahaanCode')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MGudang.MGudangID', '=', $data['MGudangIDTujuan'])
            ->get();
        
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
            'SupplierID' => $data['Supplier'],  
            'MGudangIDTujuan' => $data['MGudangIDTujuan'],  
            'PurchaseOrderID' => $data['poID'],  
            'hapus' => 0,  
            'CreatedBy'=> $user->id,
            'CreatedOn'=> date("Y-m-d h:i:sa"),
            'UpdatedBy'=> $user->id,
            'CreatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $idItemInventoryTransaction = DB::table('ItemInventoryTransaction')->insertGetId(array(
            'Name' => $dataItemTransaction[0]->Code.'/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'Description' => $data['keteranganTransaksi'],  
            'ItemTransactionID' => $data['ItemTransaction'],  
            'Date' => $data['tanggalDibuat'],  
            'SupplierID' => $data['Supplier'],  
            'NTBID' => $idtransaksigudang,  
            'EmployeeID' => $user->id,  
            'MGudangID' => $data['MGudangIDTujuan'],  
            'CreatedBy'=> $user->id,
            'CreatedOn'=> date("Y-m-d h:i:sa"),
            'UpdatedBy'=> $user->id,
            'CreatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
        //keluarkan kabeh item, baru bukak pemilihan PO ne sg mana, PO gk ush dipilih misalkan transfer atau kirim barang
        for($i = 0; $i < count($data['itemId']); $i++){
            $idtransaksigudangdetail = DB::table('transaction_gudang_barang_detail')->insertGetId(array(
                'transactionID' => $idtransaksigudang,
                'purchaseOrderDetailID' => $data['podID'][$i],//didapet dari variabel yang disimpen di itemnya(combobox item)
                'ItemID' => $data['itemId'][$i],
                'jumlah' => $data['itemJumlah'][$i],
                'keterangan' => $data['itemKeterangan'][$i],
                'harga' => $data['itemHarga'][$i],//didapat dri hidden ketika milih barang di PO
                )
            ); 

            $totalNow = DB::table('purchase_order_detail')->select('jumlah', 'jumlahProses')->where('id', $data['podID'][$i])->get();
            DB::table('purchase_order_detail')
            ->where('id', $data['podID'][$i])
            ->update([
                'jumlahProses' => $totalNow[0]->jumlahProses + $data['itemJumlah'][$i],
            ]);
                

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
            //Item Inventory Transaction line positif
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
        return redirect()->route('terimaBarangSupplier.index')->with('status','Success!!');
    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionGudang  $transactionGudang
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionGudangBarang $transactionGudangBarang)
    {
        //
        
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
        
        //data Purchase Request yang disetujui
        $dataPurchaseOrderDetail = DB::table('purchase_order_detail')
            ->select('purchase_order_detail.*','purchase_order.name','Item.ItemName as ItemName','Unit.Name as UnitName')//
            ->join('purchase_order', 'purchase_order_detail.idPurchaseOrder', '=','purchase_order.id')
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where('purchase_order_detail.jumlahProses', '<', DB::raw('purchase_order_detail.jumlah'))//errorr disini
            ->get();

        $dataPurchaseOrder = DB::table('purchase_order')
            ->select('purchase_order.*')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->get();


        return view('master.note.terimaBarangSupplier.detail',[
            'dataSupplier' => $dataSupplier,
            'dataBarangTag' => $dataBarangTag,
            'dataBarang' => $dataBarang,
            'dataTag' => $dataTag,
            'dataPurchaseOrderDetail' => $dataPurchaseOrderDetail,
            'dataPurchaseOrder' => $dataPurchaseOrder,
            'transactionGudangBarang' => $transactionGudangBarang,
        ]);
    
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

        $dataItemTransaction = DB::table("ItemTransaction")->get();
        
        //data Purchase Request yang disetujui
        $dataPurchaseOrderDetail = DB::table('purchase_order_detail')
            ->select('purchase_order_detail.*','purchase_order.name','Item.ItemName as ItemName','Unit.Name as UnitName')//
            ->join('purchase_order', 'purchase_order_detail.idPurchaseOrder', '=','purchase_order.id')
            ->join('Item','purchase_order_detail.idItem','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where('purchase_order_detail.jumlahProses', '<', DB::raw('purchase_order_detail.jumlah'))//errorr disini
            ->get();
        //dd($dataPurchaseRequestDetail);
        
        $dataPurchaseOrder = DB::table('purchase_order')
            ->select('purchase_order.*')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->get();

        
        $dataTotalDetail = DB::table('transaction_gudang_barang_detail')
            ->select('transaction_gudang_barang_detail.*','purchase_order_detail.harga as hargaPOD', 'purchase_order_detail.id as idPOD','Item.ItemName as itemName' )
            ->join('purchase_order_detail', 'transaction_gudang_barang_detail.purchaseOrderDetailID','=','purchase_order_detail.id')
            ->join('Item', 'transaction_gudang_barang_detail.ItemID','=','Item.ItemID')
            ->where('transactionID', $terimaBarangSupplier->id)
            ->get();

        return view('master.note.terimaBarangSupplier.edit',[
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
        //
        
        //
        $user = Auth::user();
        $data = $request->collect();

        DB::table('transaction_gudang_barang')
            ->where('id', $terimaBarangSupplier->id)
            ->update(array(
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
                'UpdatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        DB::table('ItemInventoryTransaction')
            ->where('NTBID', $terimaBarangSupplier->id)
            ->update(array(
                'Description' => $data['keteranganTransaksi'],  
                'ItemTransactionID' => $data['ItemTransaction'],  
                'Date' => $data['tanggalDibuat'],  
                'SupplierID' => $data['Supplier'],  
                'EmployeeID' => $user->id,   
                'MGudangID' => $data['MGudangIDTujuan'],  
                'UpdatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $dataTransactionID = DB::table('ItemInventoryTransaction')
            ->where('NTBID', $terimaBarangSupplier->id)
            ->get();

        $dataDetailTotal = DB::table('transaction_gudang_barang_detail')
            ->where('transactionID', $terimaBarangSupplier->id)
            ->get();

        //pengurangan jumlah proses lalu diupdate
        foreach($dataDetailTotal as $data){
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
        for($i = 0; $i < count( $data['itemId'] ); $i++){
            $idtransaksigudangdetail = DB::table('transaction_gudang_barang_detail')->insertGetId(array(
                'transactionID' => $terimaBarangSupplier->id,
                'purchaseOrderDetailID' => $data['podID'][$i],
                'ItemID' => $data['itemId'][$i],
                'jumlah' => $data['itemJumlah'][$i],
                'keterangan' => $data['itemKeterangan'][$i],
                'harga' => $data['itemHarga'][$i],//didapat dri hidden ketika milih barang di PO
                )
            ); 

            $totalNow = DB::table('purchase_order_detail')->select('jumlah', 'jumlahProses')->where('id', $data['podID'][$i])->get();
            DB::table('purchase_order_detail')
            ->where('id', $data['podID'][$i])
            ->update([
                'jumlahProses' => $totalNow[0]->jumlahProses + $data['itemJumlah'][$i],
            ]);
              
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
            //Item Inventory Transaction line positif
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

        return redirect()->route('terimaBarangSupplier.index')->with('status','Success!!');
    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TransactionGudang  $transactionGudang
     * @return \Illuminate\Http\Response
     */
    public function destroy(TransactionGudangBarang $transactionGudangBarang)
    {
        //
        
        //
        $user = Auth::user();
        DB::table('transaction_gudang_barang')
            ->where('id', $transactionGudangBarang->id)
            ->update(array(
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
                'Hapus' => 1,
        ));

        return redirect()->route('terimaBarangSupplier.index')->with('status','Success!!');
    
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
            ->where('transaction_gudang_barang.name','like','%'.$name.'%')
            ->where('transaction_gudang_barang.hapus',0)
            ->where('transaction_gudang_barang.MGudangIDAwal',$user->MGudangID)
            ->orWhere('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID)
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        return view('master.note.terimaBarangSupplier.index',[
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
            ->whereBetween('transaction_gudang_barang.tanggalDibuat',[date($date[0]), date($date[1])])
            ->where('transaction_gudang_barang.hapus',0)
            ->where('transaction_gudang_barang.MGudangIDAwal',$user->MGudangID)
            ->orWhere('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID)
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        return view('master.note.terimaBarangSupplier.index',[
            'data' => $data,
            'dataDetail' => $dataDetail,
        ]);
    }

    public function searchTGBNameDate(Request $request)
    {
        $name = $request->input('searchname');
        $date = $request->input('searchdate');
        $date = explode("-", $date);
        $user = Auth::user();
        $data = DB::table('transaction_gudang_barang')
            ->select('transaction_gudang_barang.*','ItemTransaction.Name as itemTransactionName','MSupplier.Name as supplierName', 'MSupplier.AtasNama as supplierAtasNama')
            ->leftjoin('ItemTransaction','transaction_gudang_barang.ItemTransactionID','=','ItemTransaction.ItemTransactionID')
            ->leftjoin('MSupplier','transaction_gudang_barang.SupplierID','=','MSupplier.SupplierID')
            ->leftjoin('purchase_order','transaction_gudang_barang.PurchaseOrderID','=','purchase_order.id')
            ->whereNotNull('transaction_gudang_barang.SupplierID')
            ->where('transaction_gudang_barang.name','like','%'.$name.'%')
            ->whereBetween('transaction_gudang_barang.tanggalDibuat',[date($date[0]), date($date[1])])
            ->where('transaction_gudang_barang.hapus',0)
            ->where('transaction_gudang_barang.MGudangIDAwal',$user->MGudangID)
            ->orWhere('transaction_gudang_barang.MGudangIDTujuan',$user->MGudangID)
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        return view('master.note.terimaBarangSupplier.index',[
            'data' => $data,
            'dataDetail' => $dataDetail,
        ]);
    }
}
