<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseRequest;
use Auth;

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
        if(count($kepalaGudang)>0){
            $prKeluar= DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID','=','MGudang.MGudangID')
                ->where('approved',0)
                ->where('hapus',0)
                ->where('purchase_request.MGudangID',$user->MGudangID)
                ->get();
        }
        else if(count($managerPerusahaan1)>0){
            $prKeluar= DB::table('purchase_request')
                ->join('MGudang', 'purchase_request.MGudangID','=','MGudang.MGudangID')
                ->where('approved',1)
                ->where('approvedAkhir',0)
                ->where('hapus',0)
                ->where('MGudang.MPerusahaanID', $managerPerusahaan1->MPerusahaanID)
                ->get();
        }
        $prd = DB::table('purchase_request_detail')
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->get();
        
        return view('master.approved.PurchaseRequest.index',[
            'prKeluar' => $prKeluar,
            'prd' => $prd,
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
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->get();
        //dd($approvedPurchaseRequest['id']);
        return view('master.approved.PurchaseRequest.approve',[
            'purchaseRequest'=>$approvedPurchaseRequest,
            'dataGudang'=>$dataGudang,
            'prd'=>$prd,
        ]);
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
        if($approvedPurchaseRequest['approve'] == 0){
            DB::table('purchase_request')
            ->where('id', $approvedPurchaseRequest['id'])
            ->update(array(
                'approved' => $data['approve'],
                'approved_by' => $user->id,
            ));

            if($data['approve'] == 1){
                DB::table('purchase_request')
                ->where('id', $approvedPurchaseRequest['id'])
                ->update(array(
                    'proses' => 1,
                ));
            }
            else{
                DB::table('purchase_request')
                ->where('id', $approvedPurchaseRequest['id'])
                ->update(array(
                    'proses' => 0,
                ));
            }

        }
        else if($approvedPurchaseRequest['approve'] ==1 && $approvedPurchaseRequest['approveAkhir'] == 0){
            DB::table('purchase_request')
            ->where('id', $approvedPurchaseRequest['id'])
            ->update(array(
                'approvedAkhir' => $data['approve'],
                'approvedAkhir_by' => $user->id,
                /*'alias' => $data['keterangan'],*/
            ));

            if($data['approve'] == 1){
                DB::table('purchase_request')
                ->where('id', $approvedPurchaseRequest['id'])
                ->update(array(
                    'proses' => 1,
                ));
            }
            else{
                DB::table('purchase_request')
                ->where('id', $approvedPurchaseRequest['id'])
                ->update(array(
                    'proses' => 0,
                ));
            }
        }

        return redirect()->route('approvedPurchaseRequest.index')->with('status','Success!!');      
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
}
