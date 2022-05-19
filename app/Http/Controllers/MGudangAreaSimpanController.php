<?php

namespace App\Http\Controllers;

use App\Models\MGudangAreaSimpan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            ->paginate(10);
        //->get();

        $user = Auth::user();
        $check = $this->checkAccess('mGudangAreaSimpan.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.mGudangAreaSimpan.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Gudang Area Simpan');
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
        $check = $this->checkAccess('mGudangAreaSimpan.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.mGudangAreaSimpan.tambah');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Gudang Area Simpan');
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

        DB::table('MGudangAreaSimpan')->insert(
            array(
                'cname' => $data['name'],
                'CreatedBy' => $user->id,
                'CreatedOn' => date("Y-m-d h:i:sa"),
                'UpdatedBy' => $user->id,
                'UpdatedOn' => date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('mGudangAreaSimpan.index')->with('status', 'Success!!');
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
        
        $user = Auth::user();
        $check = $this->checkAccess('mGudangAreaSimpan.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.mGudangAreaSimpan.detail', [
                'mGudangAreaSimpan' => $mGudangAreaSimpan,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Gudang Area Simpan');
        }
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
        

        $user = Auth::user();
        $check = $this->checkAccess('mGudangAreaSimpan.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.mGudangAreaSimpan.edit', [
                'mGudangAreaSimpan' => $mGudangAreaSimpan,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Gudang Area Simpan');
        }
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
            ->where('MGudangAreaSimpanID', $mGudangAreaSimpan['MGudangAreaSimpanID'])
            ->update(
                array(
                    'cname' => $data['name'],
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                )
            );
        return redirect()->route('mGudangAreaSimpan.index')->with('status', 'Success!!');
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
        /*DB::table('MGudangAreaSimpan')
            ->where('MGudangAreaSimpanID', $mGudangAreaSimpan['id'])
            ->update(array(
                'hapus' => 1,   
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );*/
        $mGudangAreaSimpan->delete();
        return redirect()->route('mGudangAreaSimpan.index')->with('status', 'Success!!');
    }

    public function searchGudangAreaSimpanName(Request $request)
    {
        //
        $name = $request->input('searchname');
        $data = DB::table('MGudangAreaSimpan')
            ->where('cname', 'like', '%' . $name . '%')
            ->paginate(10);
        //->get();
        

        $user = Auth::user();
        $check = $this->checkAccess('mGudangAreaSimpan.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.mGudangAreaSimpan.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Gudang Area Simpan');
        }
    }
}
