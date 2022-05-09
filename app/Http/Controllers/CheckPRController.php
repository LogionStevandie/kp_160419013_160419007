<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use Illuminate\Http\Request;

class CheckPRController extends Controller
{
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
            ->join('MGudang','users.MGudangID','=','MGudang.MGudangID')  
            ->join('MKota','MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID','=', $getLokasi[0]->cidp)
            ->where('purchase_request.hapus','=', 0)    
            //->paginate(10);
            ->paginate(10);

        $getPerusahaan = DB::table('MPerusahaan')
                    ->where('UserIDManager1', $user->id)
                    ->orWhere('UserIDManager2', $user->id)
                    ->get();
        return view('master.check.PurchaseRequest.index',[
            'data' => $data,
        ]);

        /*$user = Auth::user();

        $prKeluar= DB::table('purchase_request')
            ->join('MGudang', 'purchase_request.MGudangID','=','MGudang.MGudangID')
            ->where('approved',2)
            ->where('approvedAkhir',2)
            ->where('hapus',0)
            ->where('proses',1)
            ->paginate(10);
        
        $prd = DB::table('purchase_request_detail')
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->get();
        
        return view('master.check.PurchaseRequest.index',[
            'prKeluar' => $prKeluar,
            'prd' => $prd,
        ]);*/

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
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseRequest $purchaseRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PurchaseRequest  $purchaseRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseRequest $purchaseRequest)
    {
        //\\
        $prd = DB::table('purchase_request_detail')
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->get();
        
        if($purchaseRequest->approved == 2 && $purchaseRequest->approvedAkhir == 2){
            return view('master.check.PurchaseRequest.index',[
                'purchaseRequest' => $purchaseRequest,
                'prd' => $prd,
            ]);
        }
        else{
            return redirect()->route('checkPurchaseRequest.index')->with('status','Success!!');
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
        //
        $data = $request->collect();
        $user = Auth::user();
        //dd($approvedPurchaseRequest['id']);
        if($purchaseRequest['proses'] == 1){
            DB::table('purchase_request')
            ->where('id', $purchaseRequest['id'])
            ->update(array(
                'proses' => $data['proses'],
                'updated_by' => $data['approve'],
            ));

            if($data['proses'] == 2){
                DB::table('purchase_request')
                ->where('id', $purchaseRequest['id'])
                ->update(array(
                    'proses' => 2,
                ));
            }
            else{
                DB::table('purchase_request')
                ->where('id', $purchaseRequest['id'])
                ->update(array(
                    'proses' => 1,
                ));
                
            }

        }

        return redirect()->route('checkPurchaseRequest.index')->with('status','Success!!');
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
    }

    public function searchNamePR(Request $request)
    {
        $name=$request->input('searchname');
        $user = Auth::user();
        //
        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $data = DB::table('purchase_request')
            ->select('purchase_request.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_request.created_by', '=', 'users.id')
            ->join('MGudang','users.MGudangID','=','MGudang.MGudangID')  
            ->join('MKota','MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID','=', $getLokasi[0]->cidp)
            ->where('purchase_request.hapus','=', 0)    
            ->where('purchase_request.name','like','%'.$name.'%')
            //->paginate(10);
            ->paginate(10);

        return view('master.check.PurchaseRequest.index',[
            'data' => $data,
        ]);

    }

    public function searchDatePR(Request $request)
    {
        $date=$request->input('dateRangeSearch');
        $user = Auth::user();
        $date = explode("-", $date);
        //
        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $data = DB::table('purchase_request')
            ->select('purchase_request.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_request.created_by', '=', 'users.id')
            ->join('MGudang','users.MGudangID','=','MGudang.MGudangID')  
            ->join('MKota','MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID','=', $getLokasi[0]->cidp)
            ->where('purchase_request.hapus','=', 0)    
            ->whereBetween('purchase_request.tanggalDibuat', [ date($date[0]), date($date[1]) ])
            //->paginate(10);
            ->paginate(10);

        return view('master.check.PurchaseRequest.index',[
            'data' => $data,
        ]);

    }

    public function searchNameDatePR(Request $request)
    {
        $name=$request->input('searchname');
        $date=$request->input('dateRangeSearch');
        $user = Auth::user();
        $date = explode("-", $date);
        //
        $getLokasi = DB::table('MGudang')
            ->where('MGudang.MGudangID', '=', $user->MGudangID)
            ->get();
        $data = DB::table('purchase_request')
            ->select('purchase_request.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_request.created_by', '=', 'users.id')
            ->join('MGudang','users.MGudangID','=','MGudang.MGudangID')  
            ->join('MKota','MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID','=', $getLokasi[0]->cidp)
            ->where('purchase_request.hapus','=', 0)    
            ->where('purchase_request.name','like','%'.$name.'%')
            ->whereBetween('purchase_request.tanggalDibuat', [ date($date[0]), date($date[1]) ])
            //->paginate(10);
            ->paginate(10);

        return view('master.check.PurchaseRequest.index',[
            'data' => $data,
        ]);

    }
}
