<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
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
        $data = DB::table('Payment')
            ->paginate(10);
        //->get();
        
        $user = Auth::user();
        $check = $this->checkAccess('payment.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.payment.index',[
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Jenis Pembayaran');
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
        
        $user = Auth::user();
        $check = $this->checkAccess('payment.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.payment.tambah');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Jenis Pembayaran');
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
        $user = Auth::user();
        
        DB::table('Payment')
            ->insert(array(
                'Name' => $data['name'],
                'Deskripsi' => $data['deskripsi'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('payment.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
        
        $user = Auth::user();
        $check = $this->checkAccess('payment.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.payment.detail',[
                'payment' => $payment,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Jenis Pembayaran');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
        

        $user = Auth::user();
        $check = $this->checkAccess('payment.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.payment.edit',[
                'payment' => $payment,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Jenis Pembayaran');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('Payment')
            ->where('PaymentID', $payment['PaymentID'])
            ->update(array(
                'Name' => $data['name'],
                'Deskripsi' => $data['deskripsi'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
                )
        );  
        return redirect()->route('payment.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
        $payment->delete();
        return redirect()->route('payment.index')->with('status','Success!!');
    }

    public function searchPaymentName(Request $request)
    {
        $name = $request->input('searchname');
        $data = DB::table('Payment')
            ->where('Name','like','%'.$name.'%')
            ->paginate(10);
        //->get();
        
        $user = Auth::user();
        $check = $this->checkAccess('payment.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.payment.index',[
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Jenis Pembayaran');
        }
    }
}
