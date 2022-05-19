<?php

namespace App\Http\Controllers;

use App\Models\MKota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MKotaController extends Controller
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
        $data = DB::table('MKota')
            ->select('MKota.*', 'MProvinsi.cname as provinsiName', 'MPulau.cname as pulauName')
            ->leftjoin('MPulau','MKota.cidpulau','=','MPulau.cidpulau')
            ->leftjoin('MProvinsi','MKota.cidprov','=','MProvinsi.cidprov')
            ->paginate(10);
        //->get();
        
        $user = Auth::user();
        $check = $this->checkAccess('mKota.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.mKota.index',[
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kota');
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
        $dataMPulau = DB::table('MPulau')
            ->get();
        $dataMProvinsi = DB::table('MProvinsi')
            ->get();    
        
        $user = Auth::user();
        $check = $this->checkAccess('mKota.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.mKota.tambah',[
                'dataMPulau' => $dataMPulau,
                'dataMProvinsi' => $dataMProvinsi,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kota');
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
        
        DB::table('MKota')
            ->insert(array(
                'cidkota' => $data['cid'],
                'ckode' => $data['kode'],
                'cname' => $data['name'],
                'cidprov' => $data['prov'],
                'cidpulau' => $data['pulau'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
         return redirect()->route('mKota.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MKota  $mKota
     * @return \Illuminate\Http\Response
     */
    public function show(MKota $mKotum)
    {
        //
        /*$data = DB::table('MKota')
            ->select('MKota.*', 'MProvinsi.cname as provinsiName', 'MPulau.cname as pulauName')
            ->leftjoin('MPulau','MKota.cidpulau','=','MPulau.cidpulau')
            ->leftjoin('MProvinsi','MKota.cidprov','=','MProvinsi.cidprov')
            ->get();*/
        $dataMPulau = DB::table('MPulau')
            ->get();
        $dataMProvinsi = DB::table('MProvinsi')
            ->get();    
        

        $user = Auth::user();
        $check = $this->checkAccess('mKota.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.mKota.detail',[
                'mKota' => $mKotum,
                'dataMPulau' => $dataMPulau,
                'dataMProvinsi' => $dataMProvinsi,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kota');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MKota  $mKota
     * @return \Illuminate\Http\Response
     */
    public function edit(MKota $mKotum)
    {
        //
        $dataMPulau = DB::table('MPulau')
            ->get();
        $dataMProvinsi = DB::table('MProvinsi')
            ->get();    
        
        $user = Auth::user();
        $check = $this->checkAccess('mKota.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.mKota.edit',[
                'mKota' => $mKotum,
                'dataMPulau' => $dataMPulau,
                'dataMProvinsi' => $dataMProvinsi,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kota');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MKota  $mKota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MKota $mKotum)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('MKota')
            ->where('MKotaID', $mKotum['MKotaID'])
            ->update(array(
                'cidkota' => $data['cid'],
                'ckode' => $data['kode'],
                'cname' => $data['name'],
                'cidprov' => $data['prov'],
                'cidpulau' => $data['pulau'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('mKota.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MKota  $mKota
     * @return \Illuminate\Http\Response
     */
    public function destroy(MKota $mKotum)
    {
        //
        $mKotum->delete();
         return redirect()->route('mKota.index')->with('status','Success!!');
    }

    public function searchKotaName(Request $request)
    {
        //
        $name = $request->input('searchname');

        $data = DB::table('MKota')
            ->select('MKota.*', 'MProvinsi.cname as provinsiName', 'MPulau.cname as pulauName')
            ->leftjoin('MPulau','MKota.cidpulau','=','MPulau.cidpulau')
            ->leftjoin('MProvinsi','MKota.cidprov','=','MProvinsi.cidprov')
            ->where('MKota.cname','like','%'.$name.'%')
            ->paginate(10);
        //->get();
        

        $user = Auth::user();
        $check = $this->checkAccess('mKota.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.mKota.index',[
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Kota');
        }
    }
}
