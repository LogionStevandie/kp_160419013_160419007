<?php

namespace App\Http\Controllers;

use App\Models\MProvinsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MProvinsiController extends Controller
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
        $data = DB::table('MProvinsi')
            ->paginate(10);
        //->get();

        $user = Auth::user();
        $check = $this->checkAccess('mProvinsi.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.mProvinsi.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Provinsi');
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
        $check = $this->checkAccess('mProvinsi.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.mProvinsi.tambah');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Provinsi');
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

        DB::table('MProvinsi')
            ->insert(
                array(
                    'cidprov' => $data['cid'],
                    'cname' => $data['name'],
                    'CreatedBy' => $user->id,
                    'CreatedOn' => date("Y-m-d h:i:s"),
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:s"),
                )
            );
        return redirect()->route('mProvinsi.index')->with('status', 'Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MProvinsi  $mProvinsi
     * @return \Illuminate\Http\Response
     */
    public function show(MProvinsi $mProvinsi)
    {
        //
        //$data = DB::table('MProvinsi')->get();

        $user = Auth::user();
        $check = $this->checkAccess('mProvinsi.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.mProvinsi.detail', [
                'mProvinsi' => $mProvinsi,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Provinsi');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MProvinsi  $mProvinsi
     * @return \Illuminate\Http\Response
     */
    public function edit(MProvinsi $mProvinsi)
    {
        //

        $user = Auth::user();
        $check = $this->checkAccess('mProvinsi.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.mProvinsi.edit', [
                'mProvinsi' => $mProvinsi,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Provinsi');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MProvinsi  $mProvinsi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MProvinsi $mProvinsi)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('MProvinsi')
            ->where('MProvinsiID', $mProvinsi['MProvinsiID'])
            ->update(
                array(
                    'cidprov' => $data['cid'],
                    'cname' => $data['name'],
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:s"),
                )
            );
        return redirect()->route('mProvinsi.index')->with('status', 'Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MProvinsi  $mProvinsi
     * @return \Illuminate\Http\Response
     */
    public function destroy(MProvinsi $mProvinsi)
    {
        //
        $user = Auth::user();
        $check = $this->checkAccess('mProvinsi.edit', $user->id, $user->idRole);
        if ($check) {
            $mProvinsi->delete();
            return redirect()->route('mProvinsi.index')->with('status', 'Success!!');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Provinsi');
        }
    }

    public function searchProvinsiName(Request $request)
    {
        //
        $name = $request->input('searchname');

        $data = DB::table('MProvinsi')
            ->where('cname', 'like', '%' . $name . '%')
            ->paginate(10);
        //->get();

        $user = Auth::user();
        $check = $this->checkAccess('mProvinsi.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.mProvinsi.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Provinsi');
        }
    }
}
