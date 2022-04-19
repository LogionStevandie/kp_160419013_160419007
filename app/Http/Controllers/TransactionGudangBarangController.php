<?php

namespace App\Http\Controllers;

use App\Models\TransactionGudangBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class TransactionGudangBarangController extends Controller
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
            ->where('MGudangIDAwal',$user->MGudangID)
            ->orWhere('MGudangIDTujuan',$user->MGudangID)
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        return view('master.transactionGudang.index',[
            'data' => $data,
            'dataDetail' => $dataDetail,
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
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where('purchase_order_detail.jumlahProses', '<', DB::raw('purchase_order_detail.jumlah'))//errorr disini
            ->get();
        //dd($dataPurchaseRequestDetail);
        
        $dataPurchaseOrder = DB::table('purchase_request')
            ->select('purchase_order.*')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->get();

        return view('master.transactionGudang.tambah',[
            'dataSupplier' => $dataSupplier,
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
        
        $dataItemTransaction = DB::table('ItemTransaction')->where('ItemTransactionID',$data['ItemTransaction'])->get();
        $dataPo = DB::table('purchase_order')
            ->where('name', 'like', $dataItemTransaction[0]->Code.'/'.$dataLokasiPerusahaan[0]->cnames.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/%")
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
            'isMenerima' => $data['isMenerima'],  
            'SupplierID' => $data['Supplier'],  
            'PurchaseOrderID' => $data['PurchaseOrder'],  
            'MGudangIDAwal' => $data['MGudangIDAwal'],  
            'MGudangIDTujuan' => $data['MGudangIDTujuan'],  
            'hapus' => 0,  
            'created_by'=> $user->id,
            'created_on'=> date("Y-m-d h:i:sa"),
            'updated_by'=> $user->id,
            'updated_on'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $idItemInventoryTransaction = DB::table('ItemInventoryTransaction')->insertGetId(array(
            'Name' => $dataItemTransaction[0]->Code.'/'.$dataLokasi[0]->perusahaanCode.'/'.$dataLokasi[0]->ckode.'/'.$year.'/'.$month."/".$totalIndex,
            'Description' => $data['keteranganPengiriman'],  
            'tanggalDatang' => $data['tanggalDatang'],  
            'ItemTransactionID' => $data['ItemTransaction'],  
            'Date' => $data['tanggalDibuat'],  
            'SupplierID' => $data['Supplier'],  
            'NTBID' => $idtransaksigudang,  
            'EmployeeID' => $user->id,  
            'SalesOrderID' => $data['SalesOrderID'],  
            'SalesInvoiced' => $data['SalesInvoiced'],  
            'MGudangID' => $data['MGudangIDAwal'],  
            'TransferID' => $data['MGudangIDTujuan'],  
            'AdjustmentID' => $data['AdjustmentID'],  
            'SuratJalanID' => $data['SuratJalanID'],  
            'KwitansiID' => $data['KwitansiID'],  
            'created_by'=> $user->id,
            'created_on'=> date("Y-m-d h:i:sa"),
            'updated_by'=> $user->id,
            'updated_on'=> date("Y-m-d h:i:sa"),
            )
        ); 
        //keluarkan kabeh item, baru bukak pemilihan PO ne sg mana, PO gk ush dipilih misalkan transfer atau kirim barang
        for($i = 0; $i < count($data['itemId']); $i++){
            $idtransaksigudangdetail = DB::table('transaction_gudang_barang_detail')->insertGetId(array(
                'transactionID' => $idpo,
                'purchaseOrderDetailID' => $data['podID'][$i],
                'idItem' => $data['itemId'][$i],
                'jumlah' => $data['itemJumlah'][$i],
                'keterangan' => $data['itemKeterangan'][$i],
                'harga' => $data['itemHarga'][$i],//didapat dri hidden ketika milih barang di PO
                )
            ); 

            if($data['Supplier'] == "0" || $data['Supplier'] == "" || $data['Supplier'] == null){
                $totalNow = DB::table('purchase_order_detail')->select('jumlah', 'jumlahProses')->where('id', $data['podID'][$i])->get();
                DB::table('purchase_order_detail')
                ->where('id', $data['podID'][$i])
                ->update([
                    'jumlahProses' => $totalNow[0]->jumlahProses + $data['itemJumlah'][$i],
                ]);
            }    

            //Item Inventory Transaction line
            if($data['isMenerima'] == "1"){
                DB::table('ItemInventoryTransactionLine')
                    ->insert(array(
                        'TransactionID' => $idItemInventoryTransaction,  
                        //'transactionDetailID' => $idtransaksigudangdetail,  
                        'ItemID' => $data['itemId'][$i],  
                        'MGudangID' => $data['MGudangIDAwal'],  
                        //'UnitID' => $data['unitID'][$i],  
                        'UnitPrice' => $data['itemHarga'][$i],  
                        'Quantity' => $data['itemJumlah'][$i],  
                        'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
                    )
                );
            } 
            else if($data['isMenerima'] == "0"){
                DB::table('ItemInventoryTransactionLine')
                    ->insert(array(
                        'TransactionID' => $idItemInventoryTransaction,  
                        //'transactionDetailID' => $idtransaksigudangdetail,  
                        'ItemID' => $data['itemId'][$i],  
                        'MGudangID' => $data['MGudangIDAwal'],  
                        //'UnitID' => $data['unitID'][$i],  
                        'UnitPrice' => $data['itemHarga'][$i],  
                        'Quantity' => $data['itemJumlah'][$i] * -1,  
                        'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
                    )
                );
            }          
        }
        return redirect()->route('transactionGudang.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function show(TransactionGudangBarang $transactionGudangBarang)
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
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where('purchase_order_detail.jumlahProses', '<', DB::raw('purchase_order_detail.jumlah'))//errorr disini
            ->get();

        return view('master.transactionGudang.detail',[
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
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionGudangBarang $transactionGudangBarang)
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
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->where('purchase_order_detail.jumlahProses', '<', DB::raw('purchase_order_detail.jumlah'))//errorr disini
            ->get();
        //dd($dataPurchaseRequestDetail);
        
        $dataPurchaseOrder = DB::table('purchase_request')
            ->select('purchase_order.*')
            ->where('purchase_order.approved', 1)
            ->where('purchase_order.hapus', 0)
            ->where('purchase_order.proses', 1)
            ->get();

        return view('master.transactionGudang.edit',[
            'dataSupplier' => $dataSupplier,
            'dataBarangTag' => $dataBarangTag,
            'dataBarang' => $dataBarang,
            'dataTag' => $dataTag,
            'dataPurchaseOrderDetail' => $dataPurchaseOrderDetail,
            'dataPurchaseOrder' => $dataPurchaseOrder,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransactionGudangBarang  $transactionGudangBarang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionGudangBarang $transactionGudangBarang)
    {
        //
        $user = Auth::user();
        $data = $request->collect();

        DB::table('transaction_gudang_barang')
            ->where('id', $transactionGudangBarang->id)
            ->update(array(
                'tanggalDibuat' => $data['tanggalDibuat'],  
                'tanggalDatang' => $data['tanggalDatang'],  
                'keteranganKendaraan' => $data['keteranganKendaraan'],  
                'keteranganNomorPolisi' => $data['keteranganNomorPolisi'],  
                'keteranganPemudi' => $data['keteranganPemudi'],  
                'keteranganPengiriman' => $data['keteranganPengiriman'],  
                'ItemTransactionID' => $data['ItemTransaction'],  
                'isTambah' => $data['isTambah'],  
                'SupplierID' => $data['Supplier'],  
                'PurchaseOrderID' => $data['PurchaseOrder'],  
                'MGudangIDAwal' => $data['MGudangIDAwal'],  
                'MGudangIDTujuan' => $data['MGudangIDTujuan'],  
                'hapus' => 0,  
                'updated_by'=> $user->id,
                'updated_on'=> date("Y-m-d h:i:sa"),
            )
        ); 

        DB::table('ItemInventoryTransaction')
            ->where('NTBID', $transactionGudangBarang->id)
            ->update(array(
                'Description' => $data['keteranganPengiriman'],  
                'tanggalDatang' => $data['tanggalDatang'],  
                'ItemTransactionID' => $data['ItemTransaction'],  
                'Date' => $data['tanggalDibuat'],  
                'SupplierID' => $data['Supplier'],  
                'EmployeeID' => $user->id,  
                'SalesOrderID' => $data['SalesOrderID'],  
                'SalesInvoiced' => $data['SalesInvoiced'],  
                'MGudangID' => $data['MGudangIDAwal'],  
                'TransferID' => $data['MGudangIDTujuan'],  
                'AdjustmentID' => $data['AdjustmentID'],  
                'SuratJalanID' => $data['SuratJalanID'],  
                'KwitansiID' => $data['KwitansiID'],  
                'updated_by'=> $user->id,
                'updated_on'=> date("Y-m-d h:i:sa"),
            )
        ); 

        $dataTransactionID = DB::table('ItemInventoryTransaction')
            ->where('NTBID', $transactionGudangBarang->id)
            ->get();

        $dataDetailTotal = DB::table('transaction_gudang_barang_detail')
            ->where('idPurchaseOrder', $purchaseOrder->id)
            ->get();

        //pengurangan jumlah proses lalu diupdate
        foreach($dataDetailTotal as $data){
            DB::table('purchase_order_detail')
            ->where('id', $data['purchaseOrderDetailID'])
            ->update([
                'jumlahProses' => DB::raw('jumlahProses' - $data['jumlah']),
            ]);
        } 

        DB::table('transaction_gudang_barang_detail')
            ->where('transactionID', $transactionGudangBarang->id)
            ->delete();
        //keluarkan kabeh item, baru bukak pemilihan PO ne sg mana, PO gk ush dipilih misalkan transfer atau kirim barang
        for($i = 0; $i < count($data['itemId']); $i++){
            $idtransaksigudangdetail = DB::table('transaction_gudang_barang_detail')->insertGetId(array(
                'transactionID' => $idpo,
                'purchaseOrderDetailID' => $data['podID'][$i],
                'idItem' => $data['itemId'][$i],
                'jumlah' => $data['itemJumlah'][$i],
                'keterangan' => $data['itemKeterangan'][$i],
                'harga' => $data['itemHarga'][$i],//didapat dri hidden ketika milih barang di PO
                )
            ); 

            if($data['Supplier'] == "0" || $data['Supplier'] == "" || $data['Supplier'] == null){
                $totalNow = DB::table('purchase_order_detail')->select('jumlah', 'jumlahProses')->where('id', $data['podID'][$i])->get();
                DB::table('purchase_order_detail')
                ->where('id', $data['podID'][$i])
                ->update([
                    'jumlahProses' => $totalNow[0]->jumlahProses + $data['itemJumlah'][$i],
                ]);
            }    

            //Item Inventory Transaction line
            if($data['isMenerima'] == "1"){
                DB::table('ItemInventoryTransactionLine')
                    ->where('TransactionID', $dataTransactionID[0]->TransactionID)
                    ->update(array(
                        'ItemID' => $data['itemId'][$i],  
                        'MGudangID' => $data['MGudangIDAwal'],  
                        //'UnitID' => $data['unitID'][$i],  
                        'UnitPrice' => $data['itemHarga'][$i],  
                        'Quantity' => $data['itemJumlah'][$i],  
                        'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
                    )
                );
            } 
            else if($data['isMenerima'] == "0"){
                DB::table('ItemInventoryTransactionLine')
                    ->where('TransactionID', $dataTransactionID[0]->TransactionID)
                    ->update(array(
                        'ItemID' => $data['itemId'][$i],  
                        'MGudangID' => $data['MGudangIDAwal'],  
                        //'UnitID' => $data['unitID'][$i],  
                        'UnitPrice' => $data['itemHarga'][$i],  
                        'Quantity' => $data['itemJumlah'][$i] * -1,  
                        'TotalUnitPrice' => $data['itemHarga'][$i] * $data['itemJumlah'][$i],  
                    )
                );
            }          
        }

        return redirect()->route('transactionGudang.index')->with('status','Success!!');
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

        return redirect()->route('transactionGudang.index')->with('status','Success!!');
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
            ->where('transaction_gudang_barang.name','like','%'.$name.'%')
            ->where('MGudangIDAwal',$user->MGudangID)
            ->orWhere('MGudangIDTujuan',$user->MGudangID)
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        return view('master.transactionGudang.index',[
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
            ->whereBetween('transaction_gudang_barang.tanggalDibuat',[$date[0], $date[1]])
            ->where('MGudangIDAwal',$user->MGudangID)
            ->orWhere('MGudangIDTujuan',$user->MGudangID)
            ->paginate(10);
        //->get();
        $dataDetail = DB::table('transaction_gudang_barang_detail')
            ->get();
        return view('master.transactionGudang.index',[
            'data' => $data,
            'dataDetail' => $dataDetail,
        ]);
    }
}
