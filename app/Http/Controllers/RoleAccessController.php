<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RoleAccessController extends Controller
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
        $data = DB::table('roles')->paginate(10);;
        $dataAccess = DB::table('role_access')
            ->join('menu', 'role_access.idMenu', '=', 'menu.MenuID')
            ->get();
        //->get();    

        $user = Auth::user();
        $check = $this->checkAccess('roleAccess.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.roleAccess.index', [
                'data' => $data,
                'dataAccess' => $dataAccess,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Role Access');
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
    public function show(Role $roleAccess)
    {
        //
        /*$dataAccess = DB::table('role_access')
            ->join('menu','role_access.idMenu','=','menu.MenuID')
            ->get();    
        
        $user = Auth::user();
        $check = $this->checkAccess('roleAccess.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.roleAccess.show',[
                'role' => $roleAccess,
                'dataAccess' => $dataAccess,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Role Access');
        }*/
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $roleAccess)
    {
        //
        //
        $dataMenu = DB::table('menu')
            ->get();

        $dataAccess = DB::table('role_access')
            ->rightjoin('menu', 'role_access.idMenu', '=', 'menu.MenuID')
            ->where('role_access.idRole', $roleAccess->id)
            ->get();
        //dd($dataAccess);
        /*$dataAccess = DB::table('role_access')
            ->select('menu.Name as name', 'menu.MenuID as MenuID')
            ->join('menu','role_access.idMenu','=','menu.MenuID')
            ->where('role_access.idRole',$roleAccess->idRole)
            ->get();  
            dd($dataAccess);*/

        $user = Auth::user();
        $check = $this->checkAccess('roleAccess.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.roleAccess.edit', [
                'roleAccess' => $roleAccess,
                'dataMenu' => $dataMenu,
                'dataAccess' => $dataAccess,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Role Access');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $roleAccess)
    {
        //
        $data = $request->collect();
        //dd($data);
        //$dataRoleAccess = DB::table('role_access')->where('idRole', $role->id)->get();

        DB::table('role_access')
            ->where('idRole', '=', $roleAccess->id)
            ->delete();

        if (isset($data['menu'])) {
            for ($i = 0; $i < count($data['menu']); $i++) {
                DB::table('role_access')
                    ->insert(
                        array(
                            'idRole' => $roleAccess->id,
                            'idMenu' => $data['menu'][$i],
                        )
                    );
            }
        }

        return redirect()->route('roleAccess.index')->with('status', 'Berhasil Mengupdate Data Akses Role !!');
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
        $name = $request->input('searchname');

        $data = DB::table('roles')->where('name', 'like', '%' . $name . '%')->paginate(10);
        $dataAccess = DB::table('role_access')
            ->join('menu', 'role_access.idMenu', '=', 'menu.MenuID')
            ->paginate(10);
        //->get();    

        $user = Auth::user();
        $check = $this->checkAccess('roleAccess.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.roleAccess.index', [
                'data' => $data,
                'dataAccess' => $dataAccess,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Role Access');
        }
    }
}
