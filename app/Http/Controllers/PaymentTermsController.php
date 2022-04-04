<?php

namespace App\Http\Controllers;

use App\Models\PaymentTerms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class PaymentTermsController extends Controller
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
        $data = DB::table('PaymentTerms')
            ->select('PaymentTerms.*','Payment.Name as paymentName', 'Payment.Deskripsi as paymentDeskripsi')
            ->leftjoin('Payment', 'PaymentTerms.PaymentID','=','Payment.PaymentID')
            ->where('PaymentTerms.Hapus','=',0)
            ->get();
        return view('master.paymentTerms',[
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
        $dataPayment = DB::table('Payment')
            ->get();
        return view('master.paymentTerms_tambah',[
            'dataPayment' => $dataPayment,
        ]);
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
        $user = Auth::user();
        
        DB::table('PaymentTerms')
            ->insert(array(
                'Name' => $data['name'],
                'Deskripsi' => $data['deskripsi'],
                'Days' => $data['days'],
                'IsPembelian' => $data['isPembelian'],
                'IsPenjualan' => $data['isPenjualan'],
                'PaymentID' => $data['paymentID'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
                'Hapus' => 0,
            )
        );


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentTerms  $paymentTerms
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentTerms $paymentTerms)
    {
        //
        return view('master.paymentTerms_detail',[
            'paymentTerms' => $paymentTerms,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PaymentTerms  $paymentTerms
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentTerms $paymentTerms)
    {
        //
        $dataPayment = DB::table('Payment')
            ->get();
        return view('master.paymentTerms_edit',[
            'paymentTerms' => $paymentTerms,
            'dataPayment' => $dataPayment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentTerms  $paymentTerms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentTerms $paymentTerms)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('PaymentTerms')
            ->where('PaymentTermsID', $paymentTerms['id'])
            ->update(array(
                'Name' => $data['name'],
                'Deskripsi' => $data['deskripsi'],
                'Days' => $data['days'],
                'IsPembelian' => $data['isPembelian'],
                'IsPenjualan' => $data['isPenjualan'],
                'PaymentID' => $data['paymentID'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentTerms  $paymentTerms
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentTerms $paymentTerms)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('PaymentTerms')
            ->where('PaymentTermsID', $paymentTerms['id'])
            ->update(array(
                'Hapus' => 1,
            )
        ); 
    }

    public function searchPaymentTermsName($termsName)
    {
        //
        $data = DB::table('PaymentTerms')
            ->select('PaymentTerms.*','Payment.Name as paymentName', 'Payment.Deskripsi as paymentDeskripsi')
            ->leftjoin('Payment', 'PaymentTerms.PaymentID','=','Payment.PaymentID')
            ->where('PaymentTerms.Hapus','=',0)
            ->where('PaymentTerms.Name','like','%'.$termsName.'%')
            ->get();
        return view('master.paymentTerms',[
            'data' => $data,
        ]);
    }

    public function searchPaymentTermsDay($termsDay)
    {
        //
        $data = DB::table('PaymentTerms')
            ->select('PaymentTerms.*','Payment.Name as paymentName', 'Payment.Deskripsi as paymentDeskripsi')
            ->leftjoin('Payment', 'PaymentTerms.PaymentID','=','Payment.PaymentID')
            ->where('PaymentTerms.Hapus','=',0)
            ->where('PaymentTerms.Days','like','%'.$termsDay.'%')
            ->get();
        return view('master.paymentTerms',[
            'data' => $data,
        ]);
    }

}
