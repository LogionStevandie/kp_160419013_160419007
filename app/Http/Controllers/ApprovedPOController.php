<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;

class ApprovedPOController extends Controller
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

        $managerPerusahaan2 = DB::table('MPerusahaan')
            ->where('UserIDManager2', $user->id)
            ->get();

        $poKeluar = null;
        //belum
        $arrPerusahaan = array();
        foreach ($managerPerusahaan2 as $val) {
            array_push($arrPerusahaan, $val->MPerusahaanID);
        }
        //dd($arrPerusahaan);
        if (count($managerPerusahaan2) > 0) {
            $poKeluar = DB::table('purchase_order')
                ->select('purchase_order.*', 'MSupplier.Name as supplierName', 'MSupplier.Alamat as supplierAlamat')
                ->leftjoin('MSupplier', 'purchase_order.idSupplier', '=', 'MSupplier.SupplierID')
                ->where('purchase_order.approved', 0)
                ->where('purchase_order.hapus', 0)
                ->whereIn('purchase_order.MPerusahaanID', $arrPerusahaan)
                ->orderByDesc('purchase_order.tanggalDibuat', 'purchase_order.id')
                ->paginate(10);
        }
        //dd($poKeluar);
        $pod = DB::table('purchase_order_detail')
            ->select("purchase_order_detail.*", 'Item.ItemName as namaItem', 'Tax.Name as namaTax', 'Unit.Name as namaUnit')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('Tax', 'purchase_order_detail.idTax', '=', 'Tax.TaxID')
            //->paginate(10);
            ->get();




        $user = Auth::user();

        $check = $this->checkAccess('approvedPurchaseOrder.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.approved.PurchaseOrder.index', [
                'poKeluar' => $poKeluar,
                'pod' => $pod,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Persetujuan Order Pembelian');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseOrder $approvedPurchaseOrder)
    {
        //
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
            ->where('purchase_order_detail.idPurchaseOrder', '=', $approvedPurchaseOrder->id)
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


        if ($approvedPurchaseOrder->approved == 0) {
            $user = Auth::user();

            $check = $this->checkAccess('approvedPurchaseOrder.edit', $user->id, $user->idRole);
            if ($check) {
                return view('master.approved.PurchaseOrder.approve', [
                    'purchaseOrder' => $approvedPurchaseOrder,
                    'dataDetail' => $dataDetail,
                    'dataSupplier' => $dataSupplier,
                    'dataPayment' => $dataPayment,
                    'dataBarang' => $dataBarang,
                    'dataTax' => $dataTax,
                    'dataPurchaseRequestDetail' => $dataPurchaseRequestDetail,
                    'dataPurchaseRequest' => $dataPurchaseRequest,
                    'dataPerusahaan' => $dataPerusahaan,
                    'dataUser' => $dataUser,

                ]);
            } else {
                return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Order Pembelian');
            }
        } else {
            return redirect()->route('approvedPurchaseOrder.index')->with('status', 'Failed');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $approvedPurchaseOrder)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        //dd($approvedPurchaseRequest['id']);
        if ($approvedPurchaseOrder['approve'] == 0) {
            DB::table('purchase_order')
                ->where('id', $approvedPurchaseOrder['id'])
                ->update(array(
                    'approved' => $data['approve'],
                    'approved_by' => $user->id,
                ));

            if ($data['approve'] == 1) {
                DB::table('purchase_order')
                    ->where('id', $approvedPurchaseOrder['id'])
                    ->update(array(
                        'proses' => 1,
                        'keterangan' => $data['keterangan'],
                    ));
            } else {
                DB::table('purchase_order')
                    ->where('id', $approvedPurchaseOrder['id'])
                    ->update(array(
                        'proses' => 2,
                    ));


                $podet = DB::table('purchase_order_detail')->where('idPurchaseOrder', $approvedPurchaseOrder['id'])->get();
                foreach ($podet as $data) {
                    DB::table('purchase_request_detail')
                        ->where('id', $data->idPurchaseRequestDetail)
                        ->decrement('jumlahProses', $data->jumlah);
                }
            }
        }

        return redirect()->route('approvedPurchaseOrder.index')->with('status', 'Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function searchNamePO(Request $request)
    {
        $name = $request->input('searchname');
        $user = Auth::user();

        $managerPerusahaan2 = DB::table('MPerusahaan')
            ->where('UserIDManager2', $user->id)
            ->get();

        $poKeluar = null;
        //belum
        $arrPerusahaan = array();
        foreach ($managerPerusahaan2 as $val) {
            array_push($arrPerusahaan, $val->MPerusahaanID);
        }
        //dd($arrPerusahaan);
        if (count($managerPerusahaan2) > 0) {
            $poKeluar = DB::table('purchase_order')
                ->select('purchase_order.*', 'MSupplier.Name as supplierName', 'MSupplier.Alamat as supplierAlamat')
                ->leftjoin('MSupplier', 'purchase_order.idSupplier', '=', 'MSupplier.SupplierID')
                ->where('purchase_order.approved', 0)
                ->where('purchase_order.hapus', 0)
                ->whereIn('purchase_order.MPerusahaanID', $arrPerusahaan)
                ->where('purchase_order.name', 'like', '%' . $name . '%')
                ->orderByDesc('purchase_order.tanggalDibuat', 'purchase_order.id')
                ->paginate(10);
        }
        //dd($poKeluar);
        $pod = DB::table('purchase_order_detail')
            ->select("purchase_order_detail.*", 'Item.ItemName as namaItem', 'Tax.Name as namaTax', 'Unit.Name as namaUnit')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('Tax', 'purchase_order_detail.idTax', '=', 'Tax.TaxID')
            //->paginate(10);
            ->get();



        $user = Auth::user();

        $check = $this->checkAccess('approvedPurchaseOrder.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.approved.PurchaseOrder.index', [
                'poKeluar' => $poKeluar,
                'pod' => $pod,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Persetujuan Order Pembelian');
        }
    }

    public function searchDatePO(Request $request)
    {
        $date = $request->input('dateRangeSearch');
        $user = Auth::user();
        $date = explode("-", $date);
        $managerPerusahaan2 = DB::table('MPerusahaan')
            ->where('UserIDManager2', $user->id)
            ->get();

        $poKeluar = null;
        //belum
        $arrPerusahaan = array();
        foreach ($managerPerusahaan2 as $val) {
            array_push($arrPerusahaan, $val->MPerusahaanID);
        }
        //dd($arrPerusahaan);
        if (count($managerPerusahaan2) > 0) {
            $poKeluar = DB::table('purchase_order')
                ->select('purchase_order.*', 'MSupplier.Name as supplierName', 'MSupplier.Alamat as supplierAlamat')
                ->leftjoin('MSupplier', 'purchase_order.idSupplier', '=', 'MSupplier.SupplierID')
                ->where('purchase_order.approved', 0)
                ->where('purchase_order.hapus', 0)
                ->whereIn('purchase_order.MPerusahaanID', $arrPerusahaan)
                ->whereBetween('purchase_order.tanggalDibuat', [date($date[0]), date($date[1])])
                ->orderByDesc('purchase_order.tanggalDibuat', 'purchase_order.id')
                ->paginate(10);
        }
        //dd($poKeluar);
        $pod = DB::table('purchase_order_detail')
            ->select("purchase_order_detail.*", 'Item.ItemName as namaItem', 'Tax.Name as namaTax', 'Unit.Name as namaUnit')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('Tax', 'purchase_order_detail.idTax', '=', 'Tax.TaxID')
            //->paginate(10);
            ->get();



        $user = Auth::user();

        $check = $this->checkAccess('approvedPurchaseOrder.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.approved.PurchaseOrder.index', [
                'poKeluar' => $poKeluar,
                'pod' => $pod,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Persetujuan Order Pembelian');
        }
    }

    public function searchNameDatePO(Request $request)
    {
        $name = $request->input('searchname');
        $date = $request->input('dateRangeSearch');
        $user = Auth::user();
        $date = explode("-", $date);
        $managerPerusahaan2 = DB::table('MPerusahaan')
            ->where('UserIDManager2', $user->id)
            ->get();

        $poKeluar = null;
        //belum
        $arrPerusahaan = array();
        foreach ($managerPerusahaan2 as $val) {
            array_push($arrPerusahaan, $val->MPerusahaanID);
        }
        //dd($arrPerusahaan);
        if (count($managerPerusahaan2) > 0) {
            $poKeluar = DB::table('purchase_order')
                ->select('purchase_order.*', 'MSupplier.Name as supplierName', 'MSupplier.Alamat as supplierAlamat')
                ->leftjoin('MSupplier', 'purchase_order.idSupplier', '=', 'MSupplier.SupplierID')
                ->where('purchase_order.approved', 0)
                ->where('purchase_order.hapus', 0)
                ->whereIn('MPerusahaanID', $arrPerusahaan)
                ->where('purchase_order.name', 'like', '%' . $name . '%')
                ->whereBetween('purchase_order.tanggalDibuat', [date($date[0]), date($date[1])])
                ->orderByDesc('purchase_order.tanggalDibuat', 'purchase_order.id')
                ->paginate(10);
        }
        //dd($poKeluar);
        $pod = DB::table('purchase_order_detail')
            ->select("purchase_order_detail.*", 'Item.ItemName as namaItem', 'Tax.Name as namaTax', 'Unit.Name as namaUnit')
            ->join('Item', 'purchase_order_detail.idItem', '=', 'Item.ItemID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('Tax', 'purchase_order_detail.idTax', '=', 'Tax.TaxID')
            //->paginate(10);
            ->get();

        $user = Auth::user();

        $check = $this->checkAccess('approvedPurchaseOrder.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.approved.PurchaseOrder.index', [
                'poKeluar' => $poKeluar,
                'pod' => $pod,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Persetujuan Order Pembelian');
        }
    }
}
