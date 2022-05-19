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
            ->select('purchase_order.*', 'MGudang.cname as gudangName')
            ->join('users', 'purchase_order.created_by', '=', 'users.id')
            ->join('MGudang','users.MGudangID','=','MGudang.MGudangID')  
            ->join('MKota','MGudang.cidkota', '=', 'MKota.cidkota')
            ->join('MPerusahaan', 'MGudang.cidp','=','MPerusahaan.MPerusahaanID')
            ->where('MKota.cidkota', '=', $getLokasi[0]->cidkota)
            ->where('MPerusahaan.MPerusahaanID','=', $getLokasi[0]->cidp)
            ->where('purchase_order.hapus','=', 0)    
            ->where('purchase_order.approved','=', 1)    
            ->where('purchase_order.proses','=', 1)    
            ->orderByDesc('purchase_order.tanggalDibuat','purchase_order.id')
            ->paginate(10);

        $getPerusahaan = DB::table('MPerusahaan')
                    ->where('UserIDManager1', $user->id)
                    ->orWhere('UserIDManager2', $user->id)
                    ->get();
        

          $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseOrder.index', $user->id, $user->idRole);
        if ($check) {  
             return view('master.checkPurchaseOrder.index',[
            'data' => $data,
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
            ->select('purchase_order_detail.*','Item.ItemName as itemName','purchase_request.name')
            ->join('Item','purchase_order_detail.ItemID','=','Item.ItemID')
            ->join('purchase_request_detail','purchase_order_detail.idPurchaseRequestDetail','=','purchase_request_detail.id')
            ->join('purchase_request','purchase_request_detail.idPurchaseRequest','=','purchase_request.id')
            ->get();
       // dd($checkPurchaseRequest);
        if($checkPurchaseOrder->approved == 1){
           
            
          $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseOrder.edit', $user->id, $user->idRole);
        if ($check) {  
              return view('master.checkPurchaseOrder.check',[
                'purchaseRequest' => $checkPurchaseOrder,
                'pod' => $pod,
                'dataGudang' => $dataGudang,
            ]);

        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Permintaan Pembelian');
        }
        }
        else{
            return redirect()->route('checkPurchaseOrder.index')->with('status','Success!!');
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
        if($checkPurchaseOrder['proses'] == 1){
            DB::table('purchase_order')
            ->where('id', $checkPurchaseOrder['id'])
            ->update(array(
                'updated_on' => date("Y-m-d h:i:sa"),
                'updated_by' => $user->id,
            ));

            if($data['proses'] == 2){
                //selesai
                DB::table('purchase_order')
                ->where('id', $checkPurchaseOrder['id'])
                ->update(array(
                    'proses' => 2,
                    'keteranganProses' => $data['keterangan'],
                ));
            }
            else{
                //selesai
                DB::table('purchase_order')
                ->where('id', $checkPurchaseOrder['id'])
                ->update(array(
                    'proses' => 1,
                ));
                
            }

        }

        return redirect()->route('checkPurchaseOrder.index')->with('status','Success!!');
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
}
