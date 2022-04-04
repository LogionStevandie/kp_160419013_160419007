<?php

namespace App\Http\Controllers;

use App\Models\COAHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class COAHeadController extends Controller
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
        $data = DB::table('COAHead')
            ->get();
        return view('master.coaHead',[
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
        return view('master.coaHead_tambah');
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
        
        DB::table('COAHead')
            ->insert(array(
                'Nama' => $data['nama'],
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
     * @param  \App\Models\COAHead  $cOAHead
     * @return \Illuminate\Http\Response
     */
    public function show(COAHead $cOAHead)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\COAHead  $cOAHead
     * @return \Illuminate\Http\Response
     */
    public function edit(COAHead $cOAHead)
    {
        //
        return view('master.coa_edit',[
            'cOAHead'=>$cOAHead,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\COAHead  $cOAHead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, COAHead $cOAHead)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('COAHead')
            ->where('CH_ID', $cOAHead['id'])
            ->update(array(
                'Nama' => $data['nama'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\COAHead  $cOAHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(COAHead $cOAHead)
    {
        //
        $cOAHead->destroy();
    }

    public function searchCoaHeadName($coaHeadName)
    {
        //
        $data = DB::table('COAHead')
            ->where('Nama','like','%'.$coaHeadName.'%')
            ->get();
        return view('master.coaHead',[
            'data' => $data,
        ]);
    }
}
