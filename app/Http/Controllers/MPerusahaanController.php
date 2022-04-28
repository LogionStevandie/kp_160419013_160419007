<?php

namespace App\Http\Controllers;

use App\Models\MPerusahaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

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
        return view('master.mPerusahaan.index',[
            'data' => $data,
            'dataUser' => $dataUser,
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
        $users = DB::table('users')
            ->get();    
        return view('master.mPerusahaan.tambah',[
            'users' => $users,
        ]);
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
        
        DB::table('MPerusahaan')
            ->insert(array(
                'cname' => $data['name'],
                'cnames' => $data['names'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:s"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:s"),
                'UserIDManager1' => $data['manager1'],
                'UserIDManager2' => $data['manager2'],
                'NomorNPWP' => $data['NomorNPWP'],
                'AlamatNPWP' => $data['AlamatNPWP'],
                //'Gambar' => $request->image->storeAs('Gambar', $imageName);,
            )
        );
        return redirect()->route('mPerusahaan.index')->with('status','Success!!');
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
        return view('master.mPerusahaan.detail',[
            'mPerusahaan' => $mPerusahaan,
            'data' => $data,
        ]);
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
        return view('master.mPerusahaan.edit',[
            'mPerusahaan' => $mPerusahaan,
            'users' => $users,
        ]);
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
        
        DB::table('MPerusahaan')
            ->where('MPerusahaanID', $mPerusahaan['MPerusahaanID'])
            ->update(array(
                'cname' => $data['name'],
                'cnames' => $data['names'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:s"),
                'UserIDManager' => $data['manager'],
                'NomorNPWP' => $data['NomorNPWP'],
                'AlamatNPWP' => $data['AlamatNPWP'],
            )
        );
        return redirect()->route('mPerusahaan.index')->with('status','Success!!');
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
        $mPerusahaan->delete();
        return redirect()->route('mPerusahaan.index')->with('status','Success!!');
    }

    public function searchPerusahaanName(Request $request)
    {
        //
        $name = $request->input('searchname');
        $data = DB::table('MPerusahaan')
            ->where('cname','like','%'.$name.'%')
            ->paginate(10);
        //->get();
        return view('master.mPerusahaan',[
            'data' => $data,
        ]);
    }
}
