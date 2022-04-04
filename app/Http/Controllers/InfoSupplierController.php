<?php

namespace App\Http\Controllers;

use App\Models\InfoSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InfoSupplierController extends Controller
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
        $data = DB::table('infoSupplier')
            ->get();
        return view('master.infoSupplier.index',[
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
        return view('master.infoSupplier.tambah');
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
        
        DB::table('infoSupplier')->insert(array(
             'name' => $data['name'],
             'keterangan' => $data['keterangan'],
             )
        ); 
        return redirect()->route('infoSupplier.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InfoSupplier  $infoSupplier
     * @return \Illuminate\Http\Response
     */
    public function show(InfoSupplier $infoSupplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InfoSupplier  $infoSupplier
     * @return \Illuminate\Http\Response
     */
    public function edit(InfoSupplier $infoSupplier)
    {
        //
        return view('master.infoSupplier.edit',[
            'infoSupplier'=>$infoSupplier
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InfoSupplier  $infoSupplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InfoSupplier $infoSupplier)
    {
        //
        $data = $request->collect(); //la teros iki
        
        DB::table('infoSupplier')
            ->where('InfoSupplierID', $infoSupplier['InfoSupplierID'])
            ->update(array(
                'name' => $data['name'],
                'keterangan' => $data['keterangan'],
            ));

        return redirect()->route('infoSupplier.index')->with('status','Success!!');   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InfoSupplier  $infoSupplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(InfoSupplier $infoSupplier)
    {
        //
        $infoSupplier->delete();
        return redirect()->route('infoSupplier.index')->with('status','Success!!');
    }
}
