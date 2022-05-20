<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class checkPOController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        //
        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $data = DB::table('purchase_order')
            ->select('purchase_order.*', 'MGudang.cname as gudangName', 'MSupplier.name as supplierName', 'MSupplier.Alamat as supplierAlamat')
            ->join('users', 'purchase_order.created_by', '=', 'users.id')
            ->join('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->join('MSupplier', 'purchase_order.idSupplier', '=', 'MSupplier.SupplierID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->where('purchase_order.hapus', '=', 0)
            ->where('purchase_order.approved', '=', 1)
            ->where('purchase_order.proses', '=', 1)
            ->orderByDesc('purchase_order.tanggalDibuat', 'purchase_order.id')
            ->paginate(10);

        $getPerusahaan = DB::table('MPerusahaan')
            ->where('UserIDManager1', $user->id)
            ->orWhere('UserIDManager2', $user->id)
            ->get();


        $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseOrder.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.checkPurchaseOrder.index', [
                'poKeluar' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Purchase Order');
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
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $checkPurchaseOrder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $checkPurchaseOrder)
    {
        //
        $dataGudang = DB::table('MGudang')
            ->get();
        $pod = DB::table('purchase_order_detail')
            ->select('purchase_order_detail.*', 'Item.ItemName as namaItem', 'purchase_request.name','Tax.name as namaTax')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->leftjoin('Tax', 'purchase_order_detail.idTax', '=', 'Tax.TaxID')
            ->join('purchase_request_detail', 'purchase_order_detail.idPurchaseRequestDetail', '=', 'purchase_request_detail.id')
            ->join('purchase_request', 'purchase_request_detail.idPurchaseRequest', '=', 'purchase_request.id')
            ->get();
        // dd($checkPurchaseRequest);


        $user = Auth::user();

        $dataSupplier = DB::table('MSupplier') //
            ->get();

        $dataPayment = DB::table('PaymentTerms') //
            ->select('PaymentTerms.*', 'Payment.Name as PaymentName', 'Payment.Deskripsi as PaymentDeskripsi')
            ->leftjoin('Payment', 'PaymentTerms.PaymentID', '=', 'Payment.PaymentID')
            ->get();

        $dataBarang = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('Item.Hapus', 0)
            ->get();

        //data Purchase Request yang disetujui
        $dataPurchaseRequestDetail = DB::table('purchase_request_detail')
            ->select('purchase_request_detail.*', 'purchase_request.name as prName', 'Item.ItemName as ItemName', 'Unit.Name as UnitName') //
            ->join('purchase_request', 'purchase_request_detail.idPurchaseRequest', '=', 'purchase_request.id')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->where('purchase_request_detail.jumlahProses', '<', DB::raw('purchase_request_detail.jumlah')) //errorr disini
            ->get();

        $dataDetail = DB::table('purchase_order_detail')
            ->select('purchase_order_detail.*', 'Item.ItemName as itemName', 'Tax.TaxPercent as TaxPercent')
            //->leftjoin('purchase_request_detail', 'purchase_order_detail.idPurchaseRequestDetail','=','purchase_request_detail.id')
            ->leftjoin('Tax', 'purchase_order_detail.idTax', '=', 'Tax.TaxID')
            ->leftjoin('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->where('purchase_order_detail.idPurchaseOrder', '=', $checkPurchaseOrder->id)
            ->get();
        $dataTax = DB::table('Tax')
            ->get();

        $dataPerusahaan = DB::table('MPerusahaan') //
            ->get();

        $dataPurchaseRequest = DB::table('purchase_request')
            ->select('purchase_request.*', 'MPerusahaan.MPerusahaanID as cidp')
            ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('purchase_request.approved', 1)
            ->where('purchase_request.approvedAkhir', 1)
            ->where('purchase_request.hapus', 0)
            ->where('purchase_request.proses', 1)
            ->get();
        $dataUser = DB::table('users')
            ->get();


        if ($checkPurchaseOrder->approved == 1) {


            $user = Auth::user();

            $check = $this->checkAccess('checkPurchaseOrder.edit', $user->id, $user->idRole);
            if ($check) {
                return view('master.checkPurchaseOrder.check', [
                    'purchaseOrder' => $checkPurchaseOrder,
                    'dataDetail' => $dataDetail,
                    'dataSupplier' => $dataSupplier,
                    'dataPayment' => $dataPayment,
                    'dataBarang' => $dataBarang,
                    'dataTax' => $dataTax,
                    'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
                    'dataPurchaseRequest' => $dataPurchaseRequest,
                    'dataPerusahaan' => $dataPerusahaan,
                    'dataUser' => $dataUser,
                    'pod' => $pod,
                ]);
            } else {
                return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Permintaan Pembelian');
            }
        } else {
            return redirect()->route('checkPurchaseOrder.index')->with('status', 'Success!!');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $checkPurchaseOrder)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        //dd($approvedPurchaseRequest['id']);
        if ($checkPurchaseOrder['proses'] == 1) {
            DB::table('purchase_order')
                ->where('id', $checkPurchaseOrder['id'])
                ->update(array(
                    'updated_on' => date("Y-m-d h:i:sa"),
                    'updated_by' => $user->id,
                ));

            if ($data['proses'] == 2) {
                //selesai
                DB::table('purchase_order')
                    ->where('id', $checkPurchaseOrder['id'])
                    ->update(array(
                        'proses' => 2,
                        'keteranganProses' => $data['keterangan'],
                    ));
            } else {
                //selesai
                DB::table('purchase_order')
                    ->where('id', $checkPurchaseOrder['id'])
                    ->update(array(
                        'proses' => 1,
                    ));
            }
        }

        return redirect()->route('checkPurchaseOrder.index')->with('status', 'Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $checkPurchaseOrder)
    {
        //
    }

    public function searchNamePO(Request $request)
    {
        $name = $request->input('searchname');

        $user = Auth::user();
        //
        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $data = DB::table('purchase_order')
            ->select('purchase_order.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_order.created_by', '=', 'users.id')
            ->join('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->where('purchase_order.hapus', '=', 0)
            ->where('purchase_order.approved', '=', 1)
            ->where('purchase_order.proses', '=', 1)
            ->where('purchase_order.name', 'like', '%' . $name . '%')
            ->orderByDesc('purchase_order.tanggalDibuat', 'purchase_order.id')
            ->paginate(10);

        $getPerusahaan = DB::table('MPerusahaan')
            ->where('UserIDManager1', $user->id)
            ->orWhere('UserIDManager2', $user->id)
            ->get();


        $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseOrder.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.checkPurchaseOrder.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Purchase Order');
        }
    }

    public function searchDatePR(Request $request)
    {
        $date = $request->input('dateRangeSearch');
        $date = explode("-", $date);
        $user = Auth::user();
        //
        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $data = DB::table('purchase_order')
            ->select('purchase_order.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_order.created_by', '=', 'users.id')
            ->join('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->where('purchase_order.hapus', '=', 0)
            ->where('purchase_order.approved', '=', 1)
            ->where('purchase_order.proses', '=', 1)
            ->whereBetween('purchase_request.tanggalDibuat', [date($date[0]), date($date[1])])
            ->orderByDesc('purchase_order.tanggalDibuat', 'purchase_order.id')
            ->paginate(10);

        $getPerusahaan = DB::table('MPerusahaan')
            ->where('UserIDManager1', $user->id)
            ->orWhere('UserIDManager2', $user->id)
            ->get();


        $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseOrder.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.checkPurchaseOrder.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Purchase Order');
        }
    }

    public function searchNameDatePR(Request $request)
    {
        $name = $request->input('searchname');
        $date = $request->input('dateRangeSearch');
        $date = explode("-", $date);
        $user = Auth::user();
        //
        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $data = DB::table('purchase_order')
            ->select('purchase_order.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_order.created_by', '=', 'users.id')
            ->join('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->where('purchase_order.hapus', '=', 0)
            ->where('purchase_order.approved', '=', 1)
            ->where('purchase_order.proses', '=', 1)
            ->where('purchase_order.name', 'like', '%' . $name . '%')
            ->whereBetween('purchase_request.tanggalDibuat', [date($date[0]), date($date[1])])
            ->orderByDesc('purchase_order.tanggalDibuat', 'purchase_order.id')
            ->paginate(10);

        $getPerusahaan = DB::table('MPerusahaan')
            ->where('UserIDManager1', $user->id)
            ->orWhere('UserIDManager2', $user->id)
            ->get();


        $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseOrder.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.checkPurchaseOrder.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Purchase Order');
        }
    }
}
