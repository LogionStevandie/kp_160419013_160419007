<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\Auth;

class ApprovedPRController extends Controller
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

        $kepalaGudang = DB::table('MGudang')
            ->where('UserIDKepalaDivisi', $user->id)
            ->get();

        $managerPerusahaan1 = DB::table('MPerusahaan')
            ->where('UserIDManager1', $user->id)
            ->get();
        $prKeluar = null;
        if (count($managerPerusahaan1) > 0 && count($kepalaGudang) > 0) {
            $arrkepala = array();
            foreach ($kepalaGudang as $val) {
                array_push($arrkepala, $val->MGudangID);
            }
            $arrmanager = array();
            foreach ($managerPerusahaan1 as $val) {
                array_push($arrmanager, $val->MPerusahaanID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', "!=", 2)
                ->where('approvedAkhir', 0)
                ->where('hapus', 0)
                ->whereIn('purchase_request.MGudangID', $arrkepala)
                ->WhereIn('MGudang.cidp', $arrmanager)
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
        

            if($prKeluar == null || count($prKeluar) <= 0){
                $prKeluar = DB::table('purchase_request')
                    ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                    ->where('approved', 0)
                    ->where('hapus', 0)
                    ->whereIn('purchase_request.MGudangID', $arrkepala)
                    ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                    ->paginate(10);
            }
        } else if (count($kepalaGudang) > 0) {
            $arrkepala = array();
            foreach ($kepalaGudang as $val) {
                array_push($arrkepala, $val->MGudangID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', 0)
                ->where('hapus', 0)
                ->whereIn('purchase_request.MGudangID', $arrkepala)
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //->get();
        } else if (count($managerPerusahaan1) > 0) {
            $arrmanager = array();
            foreach ($managerPerusahaan1 as $val) {
                array_push($arrmanager, $val->MPerusahaanID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', 1)
                ->where('approvedAkhir', 0)
                ->where('hapus', 0)
                ->whereIn('MGudang.cidp', $arrmanager)
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //->get();
        }
        $prd = DB::table('purchase_request_detail')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->get();



        //dd($prKeluar);
        $user = Auth::user();

        $check = $this->checkAccess('approvedPurchaseRequest.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.approved.PurchaseRequest.index', [
                'prKeluar' => $prKeluar,
                'prd' => $prd,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Persetujuan Nota Permintaan Pembelian');
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
    public function show(PurchaseRequest $purchaseRequest)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseRequest $approvedPurchaseRequest)
    {
        //
        /*$user = Auth::user();*/
        $dataGudang = DB::table('MGudang')
            ->get();
        $prd = DB::table('purchase_request_detail')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->get();
        //dd($approvedPurchaseRequest['id']);
        if ($approvedPurchaseRequest->approved != 2 || $approvedPurchaseRequest->approvedAkhir == 0) {


            $user = Auth::user();

            $check = $this->checkAccess('approvedPurchaseRequest.edit', $user->id, $user->idRole);
            if ($check) {
                return view('master.approved.PurchaseRequest.approve', [
                    'purchaseRequest' => $approvedPurchaseRequest,
                    'dataGudang' => $dataGudang,
                    'prd' => $prd,
                ]);
            } else {
                return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Persetujuan Nota Permintaan Pembelian');
            }
        } else {
            return redirect()->route('approvedPurchaseRequest.index')->with('status', 'Nota Permintaan Pembelian sudah disetujui');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseRequest $approvedPurchaseRequest)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        //dd($approvedPurchaseRequest['id']);
        if ($approvedPurchaseRequest['approved'] == 0) {

            //dd("masu sini");
            DB::table('purchase_request')
                ->where('id', $approvedPurchaseRequest['id'])
                ->update(array(
                    'approved' => $data['approve'],
                    'approved_by' => $user->id,
                ));

            if ($data['approve'] == 1) {
                DB::table('purchase_request')
                    ->where('id', $approvedPurchaseRequest['id'])
                    ->update(array(
                        'proses' => 1,
                        'keterangan' => $data['keterangan'],
                    ));
            } else {
                DB::table('purchase_request')
                    ->where('id', $approvedPurchaseRequest['id'])
                    ->update(array(
                        'proses' => 2,
                    ));
            }
        } else if ($approvedPurchaseRequest['approved'] == 1 && $approvedPurchaseRequest['approvedAkhir'] == 0) {
            DB::table('purchase_request')
                ->where('id', $approvedPurchaseRequest['id'])
                ->update(array(
                    'approvedAkhir' => $data['approve'],
                    'approvedAkhir_by' => $user->id,
                    /*'alias' => $data['keterangan'],*/
                ));

            if ($data['approve'] == 1) {
                DB::table('purchase_request')
                    ->where('id', $approvedPurchaseRequest['id'])
                    ->update(array(
                        'proses' => 1,
                        'keterangan' => $data['keterangan'],
                    ));
            } else {
                DB::table('purchase_request')
                    ->where('id', $approvedPurchaseRequest['id'])
                    ->update(array(
                        'proses' => 2,
                    ));
            }
        }

        return redirect()->route('approvedPurchaseRequest.index')->with('status', 'Berhasilkan melakukan Approved pada nota Permintaan Pembelian');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        //
    }

    public function searchNamePR(Request $request)
    {
        $name = $request->input('searchname');
        $user = Auth::user();

        $kepalaGudang = DB::table('MGudang')
            ->where('UserIDKepalaDivisi', $user->id)
            ->get();

        $managerPerusahaan1 = DB::table('MPerusahaan')
            ->where('UserIDManager1', $user->id)
            ->get();
        $prKeluar = null;
        if (count($managerPerusahaan1) > 0 && count($kepalaGudang) > 0) {
            $arrkepala = array();
            foreach ($kepalaGudang as $val) {
                array_push($arrkepala, $val->MGudangID);
            }
            $arrmanager = array();
            foreach ($managerPerusahaan1 as $val) {
                array_push($arrmanager, $val->MPerusahaanID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', "!=", 2)
                ->where('approvedAkhir', 0)
                ->where('hapus', 0)
                ->whereIn('purchase_request.MGudangID', $arrkepala)
                ->WhereIn('MGudang.cidp', $arrmanager)
                ->where('purchase_request.name', 'like', '%' . $name . '%')
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //dd($prKeluar);
        } else if (count($kepalaGudang) > 0) {
            $arrkepala = array();
            foreach ($kepalaGudang as $val) {
                array_push($arrkepala, $val->MGudangID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', 0)
                ->where('hapus', 0)
                ->whereIn('purchase_request.MGudangID', $arrkepala)
                ->where('purchase_request.name', 'like', '%' . $name . '%')
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //->get();
        } else if (count($managerPerusahaan1) > 0) {
            $arrmanager = array();
            foreach ($managerPerusahaan1 as $val) {
                array_push($arrmanager, $val->MPerusahaanID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', 1)
                ->where('approvedAkhir', 0)
                ->where('hapus', 0)
                ->whereIn('MGudang.cidp', $arrmanager)
                ->where('purchase_request.name', 'like', '%' . $name . '%')
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //->get();
        }
        $prd = DB::table('purchase_request_detail')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->get();



        $user = Auth::user();

        $check = $this->checkAccess('approvedPurchaseRequest.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.approved.PurchaseRequest.index', [
                'prKeluar' => $prKeluar,
                'prd' => $prd,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Persetujuan Nota Permintaan Pembelian');
        }
    }

    public function searchDatePR(Request $request)
    {
        $date = $request->input('dateRangeSearch');
        $user = Auth::user();
        $date = explode("-", $date);
        $kepalaGudang = DB::table('MGudang')
            ->where('UserIDKepalaDivisi', $user->id)
            ->get();

        $managerPerusahaan1 = DB::table('MPerusahaan')
            ->where('UserIDManager1', $user->id)
            ->get();
        $prKeluar = null;
        if (count($managerPerusahaan1) > 0 && count($kepalaGudang) > 0) {
            $arrkepala = array();
            foreach ($kepalaGudang as $val) {
                array_push($arrkepala, $val->MGudangID);
            }
            $arrmanager = array();
            foreach ($managerPerusahaan1 as $val) {
                array_push($arrmanager, $val->MPerusahaanID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', "!=", 2)
                ->where('approvedAkhir', 0)
                ->where('hapus', 0)
                ->whereIn('purchase_request.MGudangID', $arrkepala)
                ->WhereIn('MGudang.cidp', $arrmanager)
                ->whereBetween('purchase_request.tanggalDibuat', [$date[0], $date[1]])
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //dd($prKeluar);
        } else if (count($kepalaGudang) > 0) {
            $arrkepala = array();
            foreach ($kepalaGudang as $val) {
                array_push($arrkepala, $val->MGudangID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', 0)
                ->where('hapus', 0)
                ->whereIn('purchase_request.MGudangID', $arrkepala)
                ->whereBetween('purchase_request.tanggalDibuat', [$date[0], $date[1]])
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //->get();
        } else if (count($managerPerusahaan1) > 0) {
            $arrmanager = array();
            foreach ($managerPerusahaan1 as $val) {
                array_push($arrmanager, $val->MPerusahaanID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', 1)
                ->where('approvedAkhir', 0)
                ->where('hapus', 0)
                ->whereIn('MGudang.cidp', $arrmanager)
                ->whereBetween('purchase_request.tanggalDibuat', [$date[0], $date[1]])
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //->get();
        }
        $prd = DB::table('purchase_request_detail')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->get();



        $user = Auth::user();

        $check = $this->checkAccess('approvedPurchaseRequest.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.approved.PurchaseRequest.index', [
                'prKeluar' => $prKeluar,
                'prd' => $prd,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Persetujuan Nota Permintaan Pembelian');
        }
    }

    public function searchNameDatePR(Request $request)
    {
        $name = $request->input('searchname');
        $date = $request->input('dateRangeSearch');
        $user = Auth::user();
        $date = explode("-", $date);
        $kepalaGudang = DB::table('MGudang')
            ->where('UserIDKepalaDivisi', $user->id)
            ->get();

        $managerPerusahaan1 = DB::table('MPerusahaan')
            ->where('UserIDManager1', $user->id)
            ->get();
        $prKeluar = null;
        if (count($managerPerusahaan1) > 0 && count($kepalaGudang) > 0) {
            $arrkepala = array();
            foreach ($kepalaGudang as $val) {
                array_push($arrkepala, $val->MGudangID);
            }
            $arrmanager = array();
            foreach ($managerPerusahaan1 as $val) {
                array_push($arrmanager, $val->MPerusahaanID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', "!=", 2)
                ->where('approvedAkhir', 0)
                ->where('hapus', 0)
                ->whereIn('purchase_request.MGudangID', $arrkepala)
                ->WhereIn('MGudang.cidp', $arrmanager)
                ->where('purchase_request.name', 'like', '%' . $name . '%')
                ->whereBetween('purchase_request.tanggalDibuat', [$date[0], $date[1]])
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //dd($prKeluar);
        } else if (count($kepalaGudang) > 0) {
            $arrkepala = array();
            foreach ($kepalaGudang as $val) {
                array_push($arrkepala, $val->MGudangID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', 0)
                ->where('hapus', 0)
                ->whereIn('purchase_request.MGudangID', $arrkepala)
                ->where('purchase_request.name', 'like', '%' . $name . '%')
                ->whereBetween('purchase_request.tanggalDibuat', [$date[0], $date[1]])
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //->get();
        } else if (count($managerPerusahaan1) > 0) {
            $arrmanager = array();
            foreach ($managerPerusahaan1 as $val) {
                array_push($arrmanager, $val->MPerusahaanID);
            }
            $prKeluar = DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID', '=', 'MGudang.MGudangID')
                ->where('approved', 1)
                ->where('approvedAkhir', 0)
                ->where('hapus', 0)
                ->whereIn('MGudang.cidp', $arrmanager)
                ->where('purchase_request.name', 'like', '%' . $name . '%')
                ->whereBetween('purchase_request.tanggalDibuat', [$date[0], $date[1]])
                ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
                ->paginate(10);
            //->get();
        }
        $prd = DB::table('purchase_request_detail')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->get();



        $user = Auth::user();

        $check = $this->checkAccess('approvedPurchaseRequest.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.approved.PurchaseRequest.index', [
                'prKeluar' => $prKeluar,
                'prd' => $prd,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Persetujuan Nota Permintaan Pembelian');
        }
    }



    public function print(PurchaseRequest $approvedPurchaseRequest)
    {
        //
        /*$user = Auth::user();*/
        $dataGudang = DB::table('MGudang')
            ->get();
        $prd = DB::table('purchase_request_detail')
            ->join('Item', 'purchase_request_detail.ItemID', '=', 'Item.ItemID')
            ->get();
        //dd($approvedPurchaseRequest['id']);
        if ($approvedPurchaseRequest->approved != 2 || $approvedPurchaseRequest->approvedAkhir == 0) {

            $user = Auth::user();

            $check = $this->checkAccess('approvedPurchaseRequest.index', $user->id, $user->idRole);
            if ($check) {
                return view('master.approved.PurchaseRequest.print', [
                    'purchaseRequest' => $approvedPurchaseRequest,
                    'dataGudang' => $dataGudang,
                    'prd' => $prd,
                ]);
            } else {
                return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Persetujuan Permintaan Pembelian');
            }
        } else {
            return redirect()->route('approvedPurchaseRequest.index')->with('status', 'Failed');
        }
    }
}
