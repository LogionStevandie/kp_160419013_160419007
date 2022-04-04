<?php

namespace App\Http\Controllers;

use App\Models\MGudangAreaSimpan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MGudangAreaSimpanController extends Controller
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
        $data = DB::table('MGudangAreaSimpan')
            ->where('Hapus','=',0)
            ->get();
        return view('master.mGudangAreaSimpan',[
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
         return view('master.mGudangAreaSimpan_tambah');
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
        
        DB::table('MGudangAreaSimpan')->insert(array(
                'cname' => $data['name'],
                'hapus' => 0,
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
             )
        );
        return redirect()->route('mGudangAreaSimpan.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MGudangAreaSimpan  $mGudangAreaSimpan
     * @return \Illuminate\Http\Response
     */
    public function show(MGudangAreaSimpan $mGudangAreaSimpan)
    {
        //
        $data = DB::table('MGudangAreaSimpan')
            ->get();
        return view('master.mGudangAreaSimpan_detail',[
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MGudangAreaSimpan  $mGudangAreaSimpan
     * @return \Illuminate\Http\Response
     */
    public function edit(MGudangAreaSimpan $mGudangAreaSimpan)
    {
        //
        return view('master.mGudangAreaSimpan_edit',[
            'mGudangAreaSimpan' => $mGudangAreaSimpan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MGudangAreaSimpan  $mGudangAreaSimpan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MGudangAreaSimpan $mGudangAreaSimpan)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('MGudangAreaSimpan')
            ->where('MGudangAreaSimpanID', $mGudangAreaSimpan['id'])
            ->update(array(
                'cname' => $data['name'],   
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('mGudangAreaSimpan.index')->with('status','Success!!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MGudangAreaSimpan  $mGudangAreaSimpan
     * @return \Illuminate\Http\Response
     */
    public function destroy(MGudangAreaSimpan $mGudangAreaSimpan)
    {
        //
        $user = Auth::user();
        DB::table('MGudangAreaSimpan')
            ->where('MGudangAreaSimpanID', $mGudangAreaSimpan['id'])
            ->update(array(
                'hapus' => 1,   
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('mGudangAreaSimpan.index')->with('status','Success!!');

    }

    public function searchGudangAreaSimpanName($gudangAreaSimpanName)
    {
        //
        $data = DB::table('MGudangAreaSimpan')
            ->where('Hapus','=',0)
            ->where('cname','like','%'.$gudangAreaSimpanName.'%')
            ->get();
        return view('master.mGudangAreaSimpan',[
            'data' => $data,
        ]);
    }
}
