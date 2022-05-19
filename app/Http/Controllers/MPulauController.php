<?php

namespace App\Http\Controllers;

use App\Models\MPulau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            ->paginate(10);
        //->get();
        
        $user = Auth::user();
        $check = $this->checkAccess('mPulau.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.mPulau.index',[
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Pulau');
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
        $check = $this->checkAccess('mPulau.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.mPulau.tambah');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Pulau');
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
        
        $user = Auth::user();
        $check = $this->checkAccess('mPulau.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.mPulau.detail',[
                'mPulau' => $mPulau,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Pulau');
        }
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
        

        $user = Auth::user();
        $check = $this->checkAccess('mPulau.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.mPulau.edit',[
                'mPulau' => $mPulau,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Pulau');
        }
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

    public function searchPulauName(Request $request)
    {
        //
        $name = $request->input('searchname');
        $data = DB::table('MPulau')
            ->where('cname','like','%'.$name.'%')
            ->paginate(10);
        //->get();
        
        $user = Auth::user();
        $check = $this->checkAccess('mPulau.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.mPulau.index',[
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Pulau');
        }
    }
}
