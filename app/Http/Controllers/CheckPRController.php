<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            ->where('purchase_request.approved','=', 1)    
            ->where('purchase_request.approvedAkhir','=', 1)    
            ->where('purchase_request.proses','=', 1)    
            ->orderByDesc('purchase_request.tanggalDibuat','purchase_request.id')
            ->paginate(10);

        $getPerusahaan = DB::table('MPerusahaan')
                    ->where('UserIDManager1', $user->id)
                    ->orWhere('UserIDManager2', $user->id)
                    ->get();
        

          $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseRequest.index', $user->id, $user->idRole);
        if ($check) {  
             return view('master.checkPurchaseRequest.index',[
            'data' => $data,
        ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Permintaan Pembelian');
        }

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
    public function edit(PurchaseRequest $checkPurchaseRequest)
    {
        //\\
        $dataGudang = DB::table('MGudang')
            ->get();
        $prd = DB::table('purchase_request_detail')
            ->join('Item','purchase_request_detail.ItemID','=','Item.ItemID')
            ->get();
       // dd($checkPurchaseRequest);
        if($checkPurchaseRequest->approved == 1 && $checkPurchaseRequest->approvedAkhir == 1){
           
            
          $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseRequest.edit', $user->id, $user->idRole);
        if ($check) {  
              return view('master.checkPurchaseRequest.check',[
                'purchaseRequest' => $checkPurchaseRequest,
                'prd' => $prd,
                'dataGudang' => $dataGudang,
            ]);

        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Permintaan Pembelian');
        }
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
    public function update(Request $request, PurchaseRequest $checkPurchaseRequest)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        //dd($approvedPurchaseRequest['id']);
        if($checkPurchaseRequest['proses'] == 1){
            DB::table('purchase_request')
            ->where('id', $checkPurchaseRequest['id'])
            ->update(array(
                'updated_on' => date("Y-m-d h:i:sa"),
                'updated_by' => $user->id,
            ));

            if($data['proses'] == 2){
                //selesai
                DB::table('purchase_request')
                ->where('id', $checkPurchaseRequest['id'])
                ->update(array(
                    'proses' => 2,
                    'keteranganProses' => $data['keterangan'],
                ));
            }
            else{
                //selesai
                DB::table('purchase_request')
                ->where('id', $checkPurchaseRequest['id'])
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
            ->where('purchase_request.approved','=', 1)    
            ->where('purchase_request.approvedAkhir','=', 1)    
            ->where('purchase_request.proses','=', 1)   
            ->where('purchase_request.name','like','%'.$name.'%')
                ->orderByDesc('purchase_request.tanggalDibuat','purchase_request.id')
                //->paginate(10);
            ->paginate(10);

       

         $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseRequest.index', $user->id, $user->idRole);
        if ($check) {  
              return view('master.checkPurchaseRequest.index',[
            'data' => $data,
        ]);

        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Permintaan Pembelian');
        }

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
            ->where('purchase_request.approved','=', 1)    
            ->where('purchase_request.approvedAkhir','=', 1)    
            ->where('purchase_request.proses','=', 1)   
            ->whereBetween('purchase_request.tanggalDibuat', [ date($date[0]), date($date[1]) ])
                ->orderByDesc('purchase_request.tanggalDibuat','purchase_request.id')
                //->paginate(10);
            ->paginate(10);

        

          $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseRequest.index', $user->id, $user->idRole);
        if ($check) {  
             return view('master.checkPurchaseRequest.index',[
            'data' => $data,
        ]);

        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Permintaan Pembelian');
        }

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
            ->where('purchase_request.approved','=', 1)    
            ->where('purchase_request.approvedAkhir','=', 1)    
            ->where('purchase_request.proses','=', 1)   
            ->where('purchase_request.name','like','%'.$name.'%')
            ->whereBetween('purchase_request.tanggalDibuat', [ date($date[0]), date($date[1]) ])
                ->orderByDesc('purchase_request.tanggalDibuat','purchase_request.id')
                //->paginate(10);
            ->paginate(10);

    
        
          $user = Auth::user();

        $check = $this->checkAccess('checkPurchaseRequest.index', $user->id, $user->idRole);
        if ($check) {  
             return view('master.checkPurchaseRequest.index',[
            'data' => $data,
        ]);

        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Check Permintaan Pembelian');
        }

    }
}
