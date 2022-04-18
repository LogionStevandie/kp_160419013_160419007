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
            ->get();
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
    }
}
