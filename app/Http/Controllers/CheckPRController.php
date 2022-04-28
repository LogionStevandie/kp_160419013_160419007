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
}
