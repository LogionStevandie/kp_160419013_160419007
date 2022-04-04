<?php

namespace App\Http\Controllers;

use App\Models\MCurrency;
use Illuminate\Http\Request;

class MCurrencyController extends Controller
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
        $data = DB::table('MCurrency')
            ->get();
        return view('master.mcurrency.index',[
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
        return view('master.mcurrency.tambah');
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
        
        DB::table('ItemTransaction')
            ->insert(array(
                'name' => $data['name'],
                'code' => $data['code'],
                'country' => $data['country'],
                'price' => $data['price'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MCurrency  $mCurrency
     * @return \Illuminate\Http\Response
     */
    public function show(MCurrency $mCurrency)
    {
        //
        return view('master.mcurrency.detail',[
            'mCurrency'=>$mCurrency
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MCurrency  $mCurrency
     * @return \Illuminate\Http\Response
     */
    public function edit(MCurrency $mCurrency)
    {
        //
        return view('master.mcurrency.edit',[
            'mCurrency'=>$mCurrency
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MCurrency  $mCurrency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MCurrency $mCurrency)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('MCurrency')
            ->where('MCurrency', $mCurrency['MCurrencyID'])
            ->update(array(
                'name' => $data['name'],
                'code' => $data['code'],
                'country' => $data['country'],
                'price' => $data['price'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MCurrency  $mCurrency
     * @return \Illuminate\Http\Response
     */
    public function destroy(MCurrency $mCurrency)
    {
        //
        $mCurrency->destroy();
    }
}
