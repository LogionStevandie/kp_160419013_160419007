<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class RoleController extends Controller
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
        $data = DB::table('roles')
            ->select('roles.*', 'menu.Name','menu.Url')
            ->leftjoin('role_access','roles.id','=','role_access.idRole')
            ->leftjoin('menu','role_access.idMenu','=','menu.MenuID')
            ->get();
        return view('master.roles.index',[
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
        $dataMenu = DB::table('menu')->get();
        return view('master.roles.tambah',[
            'dataMenu' => $dataMenu,
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
        
        $idRole = DB::table('roles')
            ->insertGetId(array(
                'name' => $data['name'],
                'deskripsi' => $data['deskripsi'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );

        for($i = 0; $i < count($data['menuTotal']); $i++){
            DB::table('role_access')->insert(array(
                'idRole' => $idRole,
                'idMenu' => $data['menuID'][$i],
                )
           ); 
        }

        return redirect()->route('role.index')->with('status','Success!!');
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
        return view('master.roles.tambah',[
            'role' => $role,
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
        $dataMenu = DB::table('menu')->get();
        return view('master.role.edit',[
            'role'=>$role,
            'dataMenu'=>$dataMenu,
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
        $data = $request->collect();
        $user = Auth::user();

        DB::table('roles')
            ->where('id', $role->id)
            ->update(array(
                'name' => $data['name'],
                'deskripsi' => $data['deskripsi'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
        ));

        $dataRoleAccess = DB::table('role_access')
            ->where('idRole', $role->id)
            ->get();

        if(count($dataRoleAccess) > count($data['menuTotal'])){
            DB::table('role_access')
                ->where('idRole', $role->id)
                ->delete();

            for($i = 0; $i < count($data['menuTotal']); $i++){
            DB::table('role_access')
                ->insert(array(
                    'idRole' => $role->id,
                    'idMenu' => $data['menuID'][$i],
                    )
                ); 
            }
        }
        else{
            for($i = 0; $i < count($data['menuTotal']); $i++){
                if($i < count($dataRoleAccess)){
                    DB::table('role_access')
                        ->where('idRole', $role->id)
                        ->update(array(
                            'idMenu' => $data['menuID'][$i],
                        )
                    );
                }
                else{
                    DB::table('role_access')->insert(array(
                        'idRole' => $idRole,
                        'idMenu' => $data['menuID'][$i],
                        )
                    ); 
                }
            }
        }

        return redirect()->route('role.index')->with('status','Success!!');    
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
        $role->delete();
        DB::table('role_access')
            ->where('idRole', $role->id)
            ->delete();

        return redirect()->route('role.index')->with('status','Success!!');
    }
}
