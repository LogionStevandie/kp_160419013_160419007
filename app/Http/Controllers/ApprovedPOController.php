<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PurchaseOrder;
use Auth;

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

        $prKeluar = null;
        //belum
        if(count($managerPerusahaan2)>0){
            $poKeluar= DB::table('purchase_order')
                ->where('approved',0)
                ->where('hapus',0)
                ->where('MPerusahaanID', $managerPerusahaan2->MPerusahaanID)
                ->get();
        }
        $pod = DB::table('purchase_order_detail')
            ->select("purchase_order_detail.*",'Item.ItemName as namaItem','Tax.Name as namaTax','Unit.Name as namaUnit')
            ->join('Item','purchase_order_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->join('Tax','purchase_order_detail.TaxID','=','Tax.TaxID')
            ->get();
        
        return view('master.approved.PurchaseOrder.index',[
            'poKeluar' => $poKeluar,
            'pod' => $pod,
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
        $dataPerusahaan = DB::table('MPerusahaan')
            ->get();
        $pod = DB::table('purchase_order_detail')
            ->select("purchase_order_detail.*",'Item.ItemName as namaItem','Tax.Name as namaTax','Unit.Name as namaUnit')
            ->join('Item','purchase_order_detail.ItemID','=','Item.ItemID')
            ->join('Unit','Item.UnitID','=','Unit.UnitID')
            ->join('Tax','purchase_order_detail.TaxID','=','Tax.TaxID')
            ->get();
        //dd($approvedPurchaseRequest['id']);
        return view('master.approved.PurchaseOrder.approve',[
            'purchaseOrder'=>$approvedPurchaseOrder,
            'dataPerusahaan'=>$dataPerusahaan,
            'pod'=>$pod,
        ]);
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
        if($approvedPurchaseOrder['approve'] == 0){
            DB::table('purchase_order')
            ->where('id', $approvedPurchaseOrder['id'])
            ->update(array(
                'approved' => $data['approve'],
                'approved_by' => $user->id,
            ));

            if($data['approve'] == 1){
                DB::table('purchase_order')
                ->where('id', $approvedPurchaseOrder['id'])
                ->update(array(
                    'proses' => 1,
                ));
            }
            else{
                DB::table('purchase_order')
                ->where('id', $approvedPurchaseOrder['id'])
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
    public function destroy($id)
    {
        //
    }
}
