<?php

namespace App\Http\Controllers;

use App\Models\COAHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            ->paginate(10);
            //->get();
        return view('master.COAHead.index',[
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
        return view('master.COAHead.tambah');
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
        return redirect()->route('coaHead.index')->with('status','Success!!');
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
    public function edit(COAHead $coaHead)
    {
        //
        return view('master.COAHead.edit',[
            'cOAHead'=>$coaHead,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\COAHead  $cOAHead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, COAHead $coaHead)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('COAHead')
            ->where('CH_ID', $coaHead['CH_ID'])
            ->update(array(
                'Nama' => $data['nama'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('coaHead.index')->with('status','Success!!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\COAHead  $cOAHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(COAHead $coaHead)
    {
        //
        $coaHead->delete();
        return redirect()->route('coaHead.index')->with('status','Success!!');

    }

    public function searchCoaHeadName(Request $request)
    {
        //
        $name=$request->input('searchname');

        $data = DB::table('COAHead')
            ->where('Nama','like','%'.$name.'%')
            ->paginate(10);
            //->get();
        return view('master.COAHead.index',[
            'data' => $data,
        ]);
    }
}
