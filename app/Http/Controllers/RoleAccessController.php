<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = DB::table('roles')->get();
        $dataAccess = DB::table('role_access')
            ->join('menu','role_access.idMenu','=','menu.MenuID')
            ->paginate(10);
        //->get();    
        return view('master.roleAccess.index',[
            'data' => $data,
            'dataAccess' => $dataAccess,
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
        $dataAccess = DB::table('role_access')
            ->join('menu','role_access.idMenu','=','menu.MenuID')
            ->get();    
        return view('master.roleAccess.show',[
            'role' => $role,
            'dataAccess' => $dataAccess,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        //
        $dataAccess = DB::table('role_access')
            ->join('menu','role_access.idMenu','=','menu.MenuID')
            ->get();    
        return view('master.roleAccess.edit',[
            'role' => $role,
            'dataAccess' => $dataAccess,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        //
        $data=$request->collect();
        //dd($data);
        //$dataRoleAccess = DB::table('role_access')->where('idRole', $role->id)->get();

        DB::table('role_access')
            ->where('idRole','=',$role->id)
            ->delete();

        for($i = 0; $i < count($data['menu']); $i++){
        DB::table('role_access')
            ->insert(array(
                'idRole' => $role->id,
                'idMenu' => $data['menu'][$i],
                )
            ); 
        }
        return redirect()->route('roleAccess.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }

    public function searchRoleName(Request $request)
    {
        $name=$request->input('searchname');

        $data = DB::table('roles')->where('name','like','%'.$name.'%')->get();
        $dataAccess = DB::table('role_access')
            ->join('menu','role_access.idMenu','=','menu.MenuID')
            ->paginate(10);
        //->get();    
        return view('master.roleAccess.index',[
            'data' => $data,
            'dataAccess' => $dataAccess,
        ]);
    }
}
