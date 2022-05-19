<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
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
        $user = Auth::user();
        $data = DB::table('Unit')
            ->paginate(10);
        //->get();

        $check = $this->checkAccess('unit.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.unit.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Unit Master');
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
        $check = $this->checkAccess('unit.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.unit.tambah');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Unit Master');
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

        DB::table('Unit')
            ->insert(
                array(
                    'Name' => $data['name'],
                    'Deskripsi' => $data['deskripsi'],
                    'CreatedBy' => $user->id,
                    'CreatedOn' => date("Y-m-d h:i:sa"),
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                )
            );
        return redirect()->route('unit.index')->with('status', 'Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
        $user = Auth::user();
        $check = $this->checkAccess('unit.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.unit.detail', [
                'unit' => $unit,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Unit Master');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        //
        
        $user = Auth::user();
        $check = $this->checkAccess('unit.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.unit.edit', [
                'unit' => $unit,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Unit Master');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('Unit')
            ->where('UnitID', $unit['UnitID'])
            ->update(
                array(
                    'Name' => $data['name'],
                    'Deskripsi' => $data['deskripsi'],
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                )
            );
        return redirect()->route('unit.index')->with('status', 'Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        //
        $unit->delete();
        return redirect()->route('unit.index')->with('status', 'Success!!');
    }

    public function searchUnitName(Request $request)
    {
        $user = Auth::user();
        $name = $request->input('searchname');
        $data = DB::table('Unit')
            ->where('Name', 'like', '%' . $name . '%')
            ->paginate(10);

        $check = $this->checkAccess('unit.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.unit.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Unit Master');
        }
    }

    public function searchUnitDeskripsi(Request $request)
    {
        $user = Auth::user();
        $desc = $request->input('searchdeskripsi');
        $data = DB::table('Unit')
            ->where('Deskripsi', 'like', '%' . $desc . '%')
            ->paginate(10);
        //->get();

        $check = $this->checkAccess('unit.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.unit.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Unit Master');
        }
    }
}
