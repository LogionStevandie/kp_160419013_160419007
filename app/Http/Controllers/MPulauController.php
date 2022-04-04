<?php

namespace App\Http\Controllers;

use App\Models\MPulau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MPulauController extends Controller
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
        $data = DB::table('MPulau')
            ->get();
        return view('master.mPulau.index',[
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
        return view('master.mPulau.tambah');
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
        
        DB::table('MPulau')
            ->insert(array(
                'cidpulau' => $data['cid'],
                'cname' => $data['name'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
         return redirect()->route('mPulau.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MPulau  $mPulau
     * @return \Illuminate\Http\Response
     */
    public function show(MPulau $mPulau)
    {
        //
        //$data = DB::table('MPulau')->get();
        return view('master.mPulau.detail',[
            'mPulau' => $mPulau,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MPulau  $mPulau
     * @return \Illuminate\Http\Response
     */
    public function edit(MPulau $mPulau)
    {
        //
        return view('master.mPulau.edit',[
            'mPulau' => $mPulau,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MPulau  $mPulau
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MPulau $mPulau)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('MPulau')
            ->where('MPulauID', $mPulau['MPulauID'])
            ->update(array(
                'cidpulau' => $data['cid'],
                'cname' => $data['name'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('mPulau.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MPulau  $mPulau
     * @return \Illuminate\Http\Response
     */
    public function destroy(MPulau $mPulau)
    {
        //
        $mPulau->delete();
        return redirect()->route('mPulau.index')->with('status','Success!!');
    }

    public function searchPulauName($pulauName)
    {
        //
        $data = DB::table('MPulau')
            ->where('cname','like','%'.$pulauName.'%')
            ->get();
        return view('master.mPulau',[
            'data' => $data,
        ]);
    }
}
