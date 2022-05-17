<?php

namespace App\Http\Controllers;

use App\Models\MCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
            ->paginate(10);
        //->get();
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
        
        DB::table('MCurrency')
            ->insert(array(
                'name' => $data['name'],
                'code' => $data['code'],
                'country' => $data['country'],
                'price' => $data['price'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdateOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
        return redirect()->route('mCurrency.index')->with('status','Success!!');
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
            ->where('MCurrencyID', $mCurrency['MCurrencyID'])
            ->update(array(
                'name' => $data['name'],
                'code' => $data['code'],
                'country' => $data['country'],
                'price' => $data['price'],
                'UpdatedBy'=> $user->id,
                'UpdateOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('mCurrency.index')->with('status','Success!!');
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
        $mCurrency->delete();
        return redirect()->route('mCurrency.index')->with('status','Success!!');
    }

    public function searhMCurrencyName(Request $request)
    {
        //
        $name = $request->input('searchname');
        $data = DB::table('MCurrency')
            ->where('name','like','%'.$name.'%')
            ->paginate(10);
        //->get();
        return view('master.mcurrency.index',[
            'data' => $data,
        ]);
    }

    public function searhMCurrencyCountry(Request $request)
    {
        //
        $country = $request->input('searchcountry');
        $data = DB::table('MCurrency')
            ->where('country','like','%'.$country.'%')
            ->paginate(10);
        //->get();
        return view('master.mcurrency.index',[
            'data' => $data,
        ]);
    }

}
