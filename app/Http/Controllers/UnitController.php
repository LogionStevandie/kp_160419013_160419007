<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

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
        $data = DB::table('Unit')
            ->paginate(10);
        //->get();
        return view('master.unit.index',[
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
        return view('master.unit.tambah');
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
            ->insert(array(
                'Name' => $data['name'],
                'Deskripsi' => $data['deskripsi'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('unit.index')->with('status','Success!!');
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
        return view('master.unit.detail',[
            'unit' => $unit,
        ]);
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
        return view('master.unit.edit',[
            'unit' => $unit,
        ]);
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
            ->update(array(
                'Name' => $data['name'],
                'Deskripsi' => $data['deskripsi'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('unit.index')->with('status','Success!!');
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
        return redirect()->route('unit.index')->with('status','Success!!');
    }

    public function searchUnitName(Request $request)
    {
        $name = $request->input('searchname');
        $data = DB::table('Unit')
            ->where('Name','like','%'.$name.'%')
            ->get();
        return view('master.unit.index',[
            'data' => $data,
        ]);
    }

    public function searchUnitDeskripsi(Request $request)
    {
        $desc = $request->input('searchdeskripsi');
        $data = DB::table('Unit')
            ->where('Deskripsi','like','%'.$desc.'%')
            ->paginate(10);
        //->get();
        return view('master.unit.index',[
            'data' => $data,
        ]);
    }
}
