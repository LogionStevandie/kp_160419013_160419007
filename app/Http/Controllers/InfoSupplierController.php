<?php

namespace App\Http\Controllers;

use App\Models\InfoSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            //->paginate(10);
            ->get();



        $user = Auth::user();

        $check = $this->checkAccess('infoSupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.infoSupplier.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Info Suppplier');
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

        $check = $this->checkAccess('infoSupplier.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.infoSupplier.tambah');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Tambah Info Suppplier');
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

        DB::table('infoSupplier')->insert(
            array(
                'name' => $data['name'],
                'keterangan' => $data['keterangan'],
            )
        );
        return redirect()->route('infoSupplier.index')->with('status', 'Berhasil menambahkan Info Supplier');
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


        $user = Auth::user();

        $check = $this->checkAccess('infoSupplier.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.infoSupplier.edit', [
                'infoSupplier' => $infoSupplier
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Ubah Info Suppplier');
        }
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

        return redirect()->route('infoSupplier.index')->with('status', 'Berhasil mengubah Info Supplier');
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
        $user = Auth::user();

        $check = $this->checkAccess('infoSupplier.edit', $user->id, $user->idRole);
        if ($check) {
            $infoSupplier->delete();
            return redirect()->route('infoSupplier.index')->with('status', 'Berhasil menghapus Info Supplier');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Hapus Info Suppplier');
        }
    }

    public function searchName(Request $request)
    {
        $name = $request->input('searchname');

        $data = DB::table('infoSupplier')
            ->where('name', 'like', '%' . $name . '%')
            ->paginate(10);
        //->get();


        $user = Auth::user();

        $check = $this->checkAccess('infoSupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.infoSupplier.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Info Suppplier');
        }
    }

    public function searchKeterangan(Request $request)
    {
        $ket = $request->input('searchketerangan');

        $data = DB::table('infoSupplier')
            ->where('keterangan', 'like', '%' . $ket . '%')
            ->paginate(10);
        //->get();


        $user = Auth::user();

        $check = $this->checkAccess('infoSupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.infoSupplier.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Info Suppplier');
        }
    }
}
