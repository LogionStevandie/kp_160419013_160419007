<?php

namespace App\Http\Controllers;

use App\Models\MPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MPerusahaanController extends Controller
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
        $data = DB::table('MPerusahaan')
            ->paginate(10);
        //->get();
        $dataUser = DB::table('users')
            ->get();


        $user = Auth::user();
        $check = $this->checkAccess('mPerusahaan.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.mPerusahaan.index', [
                'data' => $data,
                'dataUser' => $dataUser,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Perusahaan');
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
        $users = DB::table('users')
            ->get();


        $user = Auth::user();
        $check = $this->checkAccess('mPerusahaan.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.mPerusahaan.tambah', [
                'users' => $users,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Tambah Perusahaan');
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
        //$dataFile = $request->file();
        $user = Auth::user();
        //dd($dataFile);

        ///image
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpg,png,jpeg,svg|max:2048',
        ]);
        $path = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $image->move(public_path('/images'), $image_name);

            $path = '/images/' . $image_name;
        }

        //$path = $request->file('image')->store('/images');
        ///

        DB::table('MPerusahaan')
            ->insert(
                array(
                    'cname' => $data['name'],
                    'cnames' => $data['names'],
                    'CreatedBy' => $user->id,
                    'CreatedOn' => date("Y-m-d h:i:s"),
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:s"),
                    'UserIDManager1' => $data['manager1'],
                    'UserIDManager2' => $data['manager2'],
                    'NomorNPWP' => $data['NomorNPWP'],
                    'AlamatNPWP' => $data['AlamatNPWP'],
                    'Gambar' => $path,
                )
            );
        return redirect()->route('mPerusahaan.index')->with('status', 'Berhasil menambahkan perusahaan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MPerusahaan  $mPerusahaan
     * @return \Illuminate\Http\Response
     */
    public function show(MPerusahaan $mPerusahaan)
    {
        $data = DB::table('MPerusahaan')
            ->get();
        $dataUser = DB::table('users')
            ->get();

        $user = Auth::user();
        $check = $this->checkAccess('mPerusahaan.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.mPerusahaan.detail', [
                'mPerusahaan' => $mPerusahaan,
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Detail Perusahaan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MPerusahaan  $mPerusahaan
     * @return \Illuminate\Http\Response
     */
    public function edit(MPerusahaan $mPerusahaan)
    {
        //
        $users = DB::table('users')
            ->get();

        $user = Auth::user();
        $check = $this->checkAccess('mPerusahaan.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.mPerusahaan.edit', [
                'mPerusahaan' => $mPerusahaan,
                'users' => $users,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Ubah Perusahaan');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MPerusahaan  $mPerusahaan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MPerusahaan $mPerusahaan)
    {
        //
        $data = $request->collect();
        $user = Auth::user();

        $validatedData = $request->validate([
            'image' => 'image|mimes:jpg,png,jpeg,svg|max:2048',
        ]);
        $path = $mPerusahaan->Gambar;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $image->move(public_path('/images'), $image_name);

            $path = '/images/' . $image_name;
        }

        DB::table('MPerusahaan')
            ->where('MPerusahaanID', $mPerusahaan['MPerusahaanID'])
            ->update(
                array(
                    'cname' => $data['name'],
                    'cnames' => $data['names'],
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:s"),
                    'UserIDManager1' => $data['manager1'],
                    'UserIDManager2' => $data['manager2'],
                    'NomorNPWP' => $data['NomorNPWP'],
                    'AlamatNPWP' => $data['AlamatNPWP'],
                    'Gambar' => $path,
                )
            );
        return redirect()->route('mPerusahaan.index')->with('status', 'Berhasil mengubah perusahaan');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MPerusahaan  $mPerusahaan
     * @return \Illuminate\Http\Response
     */
    public function destroy(MPerusahaan $mPerusahaan)
    {
        //
        $user = Auth::user();
        $check = $this->checkAccess('mPerusahaan.edit', $user->id, $user->idRole);
        if ($check) {
            $mPerusahaan->delete();
            return redirect()->route('mPerusahaan.index')->with('status', 'Berhasil menghapus perusahaan');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Hapus Perusahaan');
        }
    }

    public function searchPerusahaanName(Request $request)
    {
        //
        $name = $request->input('searchname');
        $data = DB::table('MPerusahaan')
            ->where('cname', 'like', '%' . $name . '%')
            ->paginate(10);
        //->get();
        $dataUser = DB::table('users')
            ->get();

        $user = Auth::user();
        $check = $this->checkAccess('mPerusahaan.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.mPerusahaan.index', [
                'data' => $data,
                'dataUser' => $dataUser,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Perusahaan');
        }
    }
}
