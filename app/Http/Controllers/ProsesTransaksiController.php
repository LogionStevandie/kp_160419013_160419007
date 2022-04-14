<?php

namespace App\Http\Controllers;

use App\Models\ProsesTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ProsesTransaksiController extends Controller
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
        $data = DB::table('proses_transaksi')
            ->get();
        return view('master.prosesTransaksi.index',[
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
        return view('master.prosesTransaksi.tambah');
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
        DB::table('proses_transaksi')->insert(array(
             'name' => $data['name'],
             'deskripsi' => $data['deskripsi'],
             'CreatedBy'=> $user->id,
             'CreatedOn'=> date("Y-m-d h:i:sa"),
             'UpdatedBy'=> $user->id,
             'UpdatedOn'=> date("Y-m-d h:i:sa"),
             )
        ); 
        return redirect()->route('prosesTransaksi.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProsesTransaksi  $prosesTransaksi
     * @return \Illuminate\Http\Response
     */
    public function show(ProsesTransaksi $prosesTransaksi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProsesTransaksi  $prosesTransaksi
     * @return \Illuminate\Http\Response
     */
    public function edit(ProsesTransaksi $prosesTransaksi)
    {
        //
        return view('master.prosesTransaksi.edit',[
            'prosesTransaksi'=>$prosesTransaksi
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProsesTransaksi  $prosesTransaksi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProsesTransaksi $prosesTransaksi)
    {
        //
        $user = Auth::user();
        $data = $request->collect(); //la teros iki
        
        DB::table('proses_transaksi')
            ->where('id', $prosesTransaksi['id'])
            ->update(array(
                'name' => $data['name'],
                'deskripsi' => $data['deskripsi'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            ));

        return redirect()->route('prosesTransaksi.index')->with('status','Success!!');      
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProsesTransaksi  $prosesTransaksi
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProsesTransaksi $prosesTransaksi)
    {
        //
        $prosesTransaksi->delete();
        return redirect()->route('prosesTransaksi.index')->with('status','Success!!');
    }
}
