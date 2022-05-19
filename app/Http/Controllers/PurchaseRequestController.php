<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

use Illuminate\Support\Facades\Auth;

class PurchaseRequestController extends Controller
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
        $user = Auth::user();
        //
        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $data = DB::table('purchase_request')
            ->select('purchase_request.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_request.created_by', '=', 'users.id')
            ->join('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->where('purchase_request.hapus', '=', 0)
            ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
            //->paginate(10);
            ->paginate(10);

        $getPerusahaan = DB::table('MPerusahaan')
            ->where('UserIDManager1', $user->id)
            ->orWhere('UserIDManager2', $user->id)
            ->get();
        /*if(count($getPerusahaan)>=0){
            $data = DB::table('purchase_request')
                ->select('purchase_request.*', 'MGudang.cname as gudangName')
                ->join('users', 'purchase_request.created_by', '=', 'users.id')
                ->join('MGudang','users.MGudangID','=','MGudang.MGudangID')  
                ->join('MKota','MGudang.cidkota', '=', 'MKota.cidkota')
                ->join('MPerusahaan', 'MGudang.cidp','=','MPerusahaan.MPerusahaanID')
                ->where('purchase_request.hapus','=', 0)    
                ->get();
        } dipakek kalo udah wenak*/

        $user = Auth::user();
        $check = $this->checkAccess('purchaseRequest.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.PurchaseRequest.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Permintaan Pembelian');
        }
    }

    public function detailPR(Request $request)
    {
        //multi variabel (?)
        $dataIn = $request->collect();
        $data = DB::table('purchase_request')
            ->join('purchase_request_detail', 'purchase_request.id', '=', 'purchase_request_detail.idPurchaseRequest')
            ->where('purchase_request.id', '=', $dataIn['id'])
            ->get();
        return view('master.PurchaseRequest', [
            'data' => $data,
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

        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();

        $dataGudang = DB::table('MGudang')
            ->select('MGudang.*')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->get();

        $dataPerusahaan = DB::table('MPerusahaan')->get();

        $dataBarang = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName')
            ->leftjoin('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('Item.Hapus', 0)
            ->get();

        //dd($dataBarang);

        $dataBarangTag = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName', 'ItemTagValues.ItemTagID')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->join('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            ->where('Item.Hapus', 0)
            ->get();
        //dd($dataBarangTag);
        $dataTag = DB::table('ItemTag')
            ->get();

        //nama npp create
        $dataLokasi = DB::table('MGudang')
            ->select('MKota.*', 'MPerusahaan.cnames as perusahaanCode')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $date = date("Y-m-d");
        $year = date("Y");
        $month = date("m");
        $dataPermintaan = DB::table('purchase_request')
            ->where('name', 'like', 'NPP/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/%")
            ->get();
        $totalIndex = str_pad(strval(count($dataPermintaan) + 1), 4, '0', STR_PAD_LEFT);
        $namaNpp = 'NPP/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/" . $totalIndex;



        $user = Auth::user();
        $check = $this->checkAccess('purchaseRequest.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.PurchaseRequest.tambah', [
                'dataGudang' => $dataGudang,
                'dataBarangTag' => $dataBarangTag,
                'dataBarang' => $dataBarang,
                'namaNpp' => $namaNpp,
                'date' => $date,
                'dataTag' => $dataTag,
                'dataPerusahaan' => $dataPerusahaan,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Permintaan Pembelian');
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
        $data = $request->collect();
        //dd($data);
        //echo $data['barang'][0];
        //echo count($data['jumlah']);
        $user = Auth::user();
        $year = date("Y");
        $month = date("m");

        /*$getLokasi = DB::table('gudang')
            ->where('gudang.id', '=', $user->idGudang)
            ->get();
        */

        $dataLokasi = DB::table('MGudang')
            ->select('MKota.*', 'MPerusahaan.cnames as perusahaanCode')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();

        $dataPermintaan = DB::table('purchase_request')
            ->where('name', 'like', 'NPP/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/%")
            ->get();


        $totalIndex = str_pad(strval(count($dataPermintaan) + 1), 4, '0', STR_PAD_LEFT);

        $dateDibutuhkan = explode("-", $data['tanggalDibutuhkan']);

        $idpr = DB::table('purchase_request')->insertGetId(
            array(
                'name' => 'NPP/' . $dataLokasi[0]->perusahaanCode . '/' . $dataLokasi[0]->ckode . '/' . $year . '/' . $month . "/" . $totalIndex,
                'MGudangID' => $data['gudang'],
                'approved' => 0,
                'approvedAkhir' => 0,
                'hapus' => 0,
                'tanggalDibuat' => $data['tanggalDibuat'],
                'tanggalDibutuhkan' => $dateDibutuhkan[0],
                'tanggalAkhirDibutuhkan' => $dateDibutuhkan[1],
                'jenisProses' => $data['jenisProses'],
                'created_by' => $user->id,
                'created_on' => date("Y-m-d h:i:sa"),
                'updated_by' => $user->id,
                'updated_on' => date("Y-m-d h:i:sa"),
            )
        );

        $totalHarga = 0;

        for ($i = 0; $i < count($data['itemId']); $i++) {
            DB::table('purchase_request_detail')->insert(
                array(
                    'idPurchaseRequest' => $idpr,
                    'jumlah' => $data['itemTotal'][$i],
                    'ItemID' => $data['itemId'][$i],
                    'harga' => $data['itemHarga'][$i],
                    'keterangan_jasa' => $data['itemKeterangan'][$i],
                )
            );
            $totalHarga += $data['itemHarga'][$i] * $data['itemTotal'][$i];
            /*if(isset($data['barang'][$i])){
                DB::table('purchase_request_detail')->insert(array(
                    'idPurchaseRequest' => $idpr,
                    'jumlah' => $data['jumlah'][$i],
                    'ItemID' => $data['barang'][$i],
                    'jasa' => 0,
                    'harga' => $data['harga'][$i],
                    )
               ); 
            }
            elseif(isset($data['jasa'][$i])){
                DB::table('purchase_request_detail')->insert(array(
                    'idPurchaseRequest' => $idpr,
                    'jasa' => 1,
                    'jumlah' =>1,
                    'keterangan_jasa' => $data['keterangan'][$i],
                    'harga' => $data['harga'][$i],
                    )
               ); 
            }*/
        }

        DB::table('purchase_request')
            ->where('id', $idpr)
            ->update([
                'totalHarga' =>  $totalHarga,
            ]);

        return redirect()->route('purchaseRequest.index')->with('status', 'Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        //
        $user = Auth::user();

        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();

        $dataGudang = DB::table('MGudang')
            ->select('MGudang.*')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->get();

        $dataBarang = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('Item.Hapus', 0)
            ->get();

        $dataDetail = DB::table('purchase_request_detail')
            ->where('purchase_request_detail.idPurchaseRequest', '=', $purchaseRequest->id)
            ->get();




        $user = Auth::user();
        $check = $this->checkAccess('purchaseRequest.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.PurchaseRequest.detail', [
                'purchaseRequest' => $purchaseRequest,
                'dataDetail' => $dataDetail,
                'dataGudang' => $dataGudang,
                'dataBarang' => $dataBarang,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Permintaan Pembelian');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseRequest $purchaseRequest)
    {
        //
        $user = Auth::user();

        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();

        $dataGudang = DB::table('MGudang')
            ->select('MGudang.*')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->get();

        $dataBarang = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
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

        $dataDetail = DB::table('purchase_request_detail')
            ->where('purchase_request_detail.idPurchaseRequest', '=', $purchaseRequest->id)
            ->get();



        $user = Auth::user();
        $check = $this->checkAccess('purchaseRequest.edit', $user->id, $user->idRole);
        if ($check) {
            if ($purchaseRequest->approved == 1 || $purchaseRequest->approved == 2) {
                return redirect()->route('purchaseRequest.index')->with('status', 'Tidak dapat mengubah data');
            } else {
                return view('master.PurchaseRequest.edit', [
                    'purchaseRequest' => $purchaseRequest,
                    'dataDetail' => $dataDetail,
                    'dataGudang' => $dataGudang,
                    'dataBarang' => $dataBarang,
                    'dataBarangTag' => $dataBarangTag,
                    'dataTag' => $dataTag,
                ]);
            }
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Permintaan Pembelian');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseRequest $purchaseRequest)
    {
        //belommm
        $data = $request->collect();
        $user = Auth::user();
        $year = date("Y");
        $month = date("m");
        $dateDibutuhkan = explode("-", $data['tanggalDibutuhkan']);

        DB::table('purchase_request')
            ->where('id', $purchaseRequest->id)
            ->update([
                'MGudangID' => $data['gudang'],
                'tanggalDibutuhkan' => $dateDibutuhkan[0],
                'tanggalAkhirDibutuhkan' => $dateDibutuhkan[1],
                'jenisProses' => $data['jenisProses'],
                'updated_by' => $user->id,
                'updated_on' => date("Y-m-d h:i:sa"),
            ]);

        $dataDetailTotal = DB::table('purchase_request_detail')
            ->where('idPurchaseRequest', $purchaseRequest->id)
            ->get();
        $totalHarga = 0;
        if (count($dataDetailTotal) > count($data['itemId'])) {
            DB::table('purchase_request_detail')
                ->where('idPurchaseRequest', $purchaseRequest->id)
                ->delete();

            for ($i = 0; $i < count($data['itemId']); $i++) {
                DB::table('purchase_request_detail')->insert(
                    array(
                        'idPurchaseRequest' => $purchaseRequest->id,
                        'jumlah' => $data['itemTotal'][$i],
                        'ItemID' => $data['itemId'][$i],
                        'harga' => $data['itemHarga'][$i],
                        'keterangan_jasa' => $data['itemKeterangan'][$i],
                    )
                );
                $totalHarga += $data['itemHarga'][$i] * $data['itemTotal'][$i];
            }
        } else {
            for ($i = 0; $i < count($data['itemId']); $i++) {
                if ($i < count($dataDetailTotal)) {
                    DB::table('purchase_request_detail')
                        ->where('idPurchaseRequest', $purchaseRequest->id)
                        ->update(
                            array(
                                'jumlah' => $data['itemTotal'][$i],
                                'ItemID' => $data['itemId'][$i],
                                'harga' => $data['itemHarga'][$i],
                                'keterangan_jasa' => $data['itemKeterangan'][$i],
                            )
                        );
                    $totalHarga += $data['itemHarga'][$i] * $data['itemTotal'][$i];
                } else {
                    DB::table('purchase_request_detail')->insert(
                        array(
                            'idPurchaseRequest' => $purchaseRequest->id,
                            'jumlah' => $data['itemTotal'][$i],
                            'ItemID' => $data['itemId'][$i],
                            'harga' => $data['itemHarga'][$i],
                            'keterangan_jasa' => $data['itemKeterangan'][$i],
                        )
                    );
                    $totalHarga += $data['itemHarga'][$i] * $data['itemTotal'][$i];
                }
            }
        }
        DB::table('purchase_request')
            ->where('id', $purchaseRequest->id)
            ->update([
                'totalHarga' =>  $totalHarga,
            ]);

        return redirect()->route('purchaseRequest.index')->with('status', 'Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseRequest $purchaseRequest)
    {
        //
        // echo $purchaseRequest->id;
        //dd($purchaseRequest->id);
        if ($purchaseRequest->approved == 1 || $purchaseRequest->approved == 2) {
            return redirect()->route('purchaseRequest.index')->with('status', 'Tidak dapat mengubah data');
        } else {
            DB::table('purchase_request')
                ->where('id', $purchaseRequest->id)
                ->update([
                    'hapus' => 1,
                ]);
            return redirect()->route('purchaseRequest.index')->with('status', 'Success!!');
        }
    }

    public function print(PurchaseRequest $purchaseRequest)
    {
        //
        $user = Auth::user();

        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();

        $dataGudang = DB::table('MGudang')
            ->select('MGudang.*')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->get();

        $dataBarang = DB::table('Item')
            ->select('Item.*', 'Unit.Name as unitName')
            ->join('Unit', 'Item.UnitID', '=', 'Unit.UnitID')
            ->where('Item.Hapus', 0)
            ->get();

        $dataDetail = DB::table('purchase_request_detail')
            ->where('purchase_request_detail.idPurchaseRequest', '=', $purchaseRequest->id)
            ->get();


        return view('master.PurchaseRequest.print', [
            'purchaseRequest' => $purchaseRequest,
            'dataDetail' => $dataDetail,
            'dataGudang' => $dataGudang,
            'dataBarang' => $dataBarang,
        ]);
    }

    public function searchNamePR(Request $request)
    {
        $name = $request->input('searchname');
        $user = Auth::user();
        //->whereBetween('votes', [1, 100])
        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $data = DB::table('purchase_request')
            ->select('purchase_request.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_request.created_by', '=', 'users.id')
            ->join('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->where('purchase_request.hapus', '=', 0)
            ->where('purchase_request.name', 'like', '%' . $name . '%')
            ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
            ->paginate(10);
        //->get();

        $user = Auth::user();
        $check = $this->checkAccess('purchaseRequest.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.PurchaseRequest.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Permintaan Pembelian');
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
        $data = DB::table('purchase_request')
            ->select('purchase_request.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_request.created_by', '=', 'users.id')
            ->join('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->where('purchase_request.hapus', '=', 0)
            ->whereBetween('purchase_request.tanggalDibuat', [date($date[0]), date($date[1])])
            ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
            ->paginate(10);
        //->get();
        //dd($data);
        $user = Auth::user();
        $check = $this->checkAccess('purchaseRequest.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.PurchaseRequest.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Permintaan Pembelian');
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
        $data = DB::table('purchase_request')
            ->select('purchase_request.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_request.created_by', '=', 'users.id')
            ->join('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID', '=', $getLokasi[0]->cidp)
            ->where('purchase_request.hapus', '=', 0)
            ->where('purchase_request.name', 'like', '%' . $name . '%')
            ->whereBetween('purchase_request.tanggalDibuat', [date($date[0]), date($date[1])])
            ->orderByDesc('purchase_request.tanggalDibuat', 'purchase_request.id')
            ->paginate(10);
        //->get();
        //dd($data);
        $user = Auth::user();
        $check = $this->checkAccess('purchaseRequest.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.PurchaseRequest.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Permintaan Pembelian');
        }
    }
}
