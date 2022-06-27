<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Symfony\Component\ErrorHandler\Debug;

class UserController extends Controller
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
        $data = DB::table('users')
            ->select(
                'users.*',
                'roles.name as roleName',
                'MGudang.cname as gudangName',
                'MGudang.UserIDKepalaDivisi',
                'MPerusahaan.cname as perusahaanName',
                'MPerusahaan.UserIDManager1',
                'MPerusahaan.UserIDManager2',
            )
            ->leftjoin('roles', 'users.idRole', '=', 'roles.id')
            ->leftjoin('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->paginate(10);
        //dd($data);
        //->get();
        $dataGudang = DB::table('MGudang')->get();
        $dataPerusahaan = DB::table('MPerusahaan')->get();

        $check = $this->checkAccess('users.index', $user->id, $user->idRole);

        if ($check) {
            //dd($data);
            return view('master.users.index', [
                'dataaa' => $data,
                'dataGudang' => $dataGudang,
                'dataPerusahaan' => $dataPerusahaan,
            ]);
            //return view('master.users.index',compact('data', 'dataGudang','dataPerusahaan'));
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Users Master');
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
        $dataGudang = DB::table('MGudang')
            ->select('MGudang.*', 'MPerusahaan.cname as perusahaanName')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->get();
        $dataRole = DB::table('roles')
            ->get();


        $check = $this->checkAccess('users.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.users.tambah', [
                'dataGudang' => $dataGudang,
                'dataRole' => $dataRole,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Users Master');
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

        $dataUserAll = DB::table('users')
            ->where('email', $data['email'])
            ->get();

        if(count($dataUserAll) > 0){
           return redirect()->route('users.create')->with('message',  'NIP '.$data['email'].' sudah digunakan');
        }

        $idUsers = DB::table('users')
            ->insertGetId(
                array(
                    'Name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'idRole' => $data['role'],
                    'MGudangID' => $data['MGudangID'],
                    'CreatedBy' => $user->id,
                    'CreatedOn' => date("Y-m-d h:i:sa"),
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                )
            );

        $dataPerusahaan = DB::table('MPerusahaan')
            ->select('MPerusahaan.*')
            ->join('MGudang', 'MPerusahaan.MPerusahaanID', '=', 'MGudang.cidp')
            ->where('MGudangID', $data['MGudangID'])
            ->get();

        if ($data['UserIDKepalaDivisi'] == "1") {
            DB::table('MGudang')
                ->where('MGudangID', $data['MGudangID'])
                ->update(
                    array(
                        //'UpdatedBy' => $user->id,
                        //'UpdatedOn' => date("Y-m-d h:i:s"),
                        'UserIDKepalaDivisi' => $idUsers,
                    )
                );
        }
        if ($data['UserIDManager1'] == "1") {
            DB::table('MPerusahaan')
                ->where('MPerusahaanID', $dataPerusahaan[0]->MPerusahaanID)
                ->update(
                    array(
                        //'UpdatedBy' => $user->id,
                        //'UpdatedOn' => date("Y-m-d h:i:s"),
                        'UserIDManager1' => $idUsers,
                    )
                );
        }
        if ($data['UserIDManager2'] == "1") {
            DB::table('MPerusahaan')
                ->where('MPerusahaanID', $dataPerusahaan[0]->MPerusahaanID)
                ->update(
                    array(
                        //'UpdatedBy' => $user->id,
                        //'UpdatedOn' => date("Y-m-d h:i:s"),
                        'UserIDManager2' => $idUsers,
                    )
                );
        }

        return redirect()->route('users.index')->with('status', 'Berhasil menambah Data User!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        $usero = Auth::user();
        $userdata = DB::table('users')
            ->select(
                'users.*',
                'roles.name as roleName',
                'MGudang.cname as gudangName',
                'MGudang.UserIDKepalaDivisi',
                'MPerusahaan.cname as perusahaanName',
                'MPerusahaan.UserIDManager1',
                'MPerusahaan.UserIDManager2',
            )
            ->leftjoin('roles', 'users.idRole', '=', 'roles.id')
            ->leftjoin('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('users.id', $user['id'])
            ->get();

        $dataGudang = DB::table('MGudang')
            ->select('MGudang.*', 'MPerusahaan.cname as perusahaanName')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->get();
        $dataRole = DB::table('roles')
            ->get();



        $check = $this->checkAccess('users.show', $usero->id, $usero->idRole);
        if ($check) {
            //dd($data);
            //dd($user);
            return view('master.users.detail', [
                'dataGudang' => $dataGudang,
                'dataRole' => $dataRole,
                'userdata' => $userdata,
                'userss' => $user,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Users Master');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        $usero = Auth::user();
        $userdata = DB::table('users')
            ->select(
                'users.*',
                'roles.name as roleName',
                'MGudang.cname as gudangName',
                'MGudang.UserIDKepalaDivisi',
                'MPerusahaan.cname as perusahaanName',
                'MPerusahaan.UserIDManager1',
                'MPerusahaan.UserIDManager2',
            )
            ->leftjoin('roles', 'users.idRole', '=', 'roles.id')
            ->leftjoin('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('users.id', $user['id'])
            ->get();

        $dataGudang = DB::table('MGudang')
            ->select('MGudang.*', 'MPerusahaan.cname as perusahaanName')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->get();
        $dataRole = DB::table('roles')
            ->get();



        $check = $this->checkAccess('users.edit', $usero->id, $usero->idRole);
        if ($check) {
            //dd($data);
            //dd($user);
            return view('master.users.edit', [
                'dataGudang' => $dataGudang,
                'dataRole' => $dataRole,
                'userdata' => $userdata,
                'users' => $user,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Users Master');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        $data = $request->collect();
        $usero = Auth::user();

        $dataUserAll = DB::table('users')
            ->where('email', $data['email'])
            ->where('email', '!=', $user['email'])
            ->get();

        if(count($dataUserAll) > 0){
           return redirect('/users/'.$user['id'].'/edit/')->with('message', 'NIP '.$data['email'].' sudah digunakan');
        }


        //dd($users);
        if ($data['password'] == "" || $data['password'] == null) {
            DB::table('users')
                ->where('id', $user['id'])
                ->update(
                    array(
                        'Name' => $data['name'],
                        'email' => $data['email'],
                        'idRole' => $data['role'],
                        'MGudangID' => $data['MGudangID'],
                        'UpdatedBy' => $usero->id,
                        'UpdatedOn' => date("Y-m-d h:i:sa"),
                    )
                );
        } else {
            DB::table('users')
                ->where('id', $user['id'])
                ->update(
                    array(
                        'Name' => $data['name'],
                        'email' => $data['email'],
                        'password' => Hash::make($data['password']),
                        'idRole' => $data['role'],
                        'MGudangID' => $data['MGudangID'],
                        'UpdatedBy' => $usero->id,
                        'UpdatedOn' => date("Y-m-d h:i:sa"),
                    )
                );
        }


        $dataPerusahaan = DB::table('MPerusahaan')
            ->select('MPerusahaan.*')
            ->join('MGudang', 'MPerusahaan.MPerusahaanID', '=', 'MGudang.cidp')
            ->where('MGudangID', $data['MGudangID'])
            ->get();

        if ($data['UserIDKepalaDivisi'] == "1") {
            DB::table('MGudang')
                ->where('MGudangID', $data['MGudangID'])
                ->update(
                    array(
                        //'UpdatedBy' => $user->id,
                        //'UpdatedOn' => date("Y-m-d h:i:s"),
                        'UserIDKepalaDivisi' => $user['id'],
                    )
                );
        }
        if ($data['UserIDManager1'] == "1") {
            DB::table('MPerusahaan')
                ->where('MPerusahaanID', $dataPerusahaan[0]->MPerusahaanID)
                ->update(
                    array(
                        //'UpdatedBy' => $user->id,
                        //'UpdatedOn' => date("Y-m-d h:i:s"),
                        'UserIDManager1' => $user['id'],
                    )
                );
        }
        if ($data['UserIDManager2'] == "1") {
            DB::table('MPerusahaan')
                ->where('MPerusahaanID', $dataPerusahaan[0]->MPerusahaanID)
                ->update(
                    array(
                        //'UpdatedBy' => $user->id,
                        //'UpdatedOn' => date("Y-m-d h:i:s"),
                        'UserIDManager2' => $user['id'],
                    )
                );
        }

        return redirect()->route('users.index')->with('status', 'Berhasil Mengupdate Data User!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $users)
    {
        //

        $usero = Auth::user();
        $check = $this->checkAccess('users.edit', $usero->id, $usero->idRole);
        if ($check) {
            DB::table('MGudang')
                ->where('MGudangID', $users['MGudangID'])
                ->update(
                    array(
                        'UserIDKepalaDivisi' => null,
                    )
                );
            $dataPerusahaan = DB::table('MPerusahaan')
                ->select('MPerusahaan.*')
                ->join('MGudang', 'MPerusahaan.MPerusahaanID', '=', 'MGudang.cidp')
                ->where('MGudangID', $users['MGudangID'])
                ->get();

            DB::table('MPerusahaan')
                ->where('MPerusahaanID', $dataPerusahaan[0]->MPerusahaanID)
                ->update(
                    array(
                        'UserIDManager1' => null,
                        'UserIDManager2' => null,
                    )
                );

            DB::table('users')
                ->where('id', $users['id'])
                ->delete();

            return redirect()->route('users.index')->with('status', 'Berhasil Menghapus User!!');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Users Master');
        }
    }

    public function searchNameUsers(Request $request)
    {
        //
        $user = Auth::user();
        $name = $request->input('searchname');
        $data = DB::table('users')
            ->select(
                'users.*',
                'roles.name as roleName',
                'MGudang.cname as gudangName',
                'MGudang.UserIDKepalaDivisi',
                'MPerusahaan.cname as perusahaanName',
                'MPerusahaan.UserIDManager1',
                'MPerusahaan.UserIDManager2',
            )
            ->leftjoin('roles', 'users.idRole', '=', 'roles.id')
            ->leftjoin('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('users.name', 'like', '%' . $name . '%')
            //->get();
            ->paginate(10);
        //dd($data);
        //->get();
        $dataGudang = DB::table('MGudang')->get();
        $dataPerusahaan = DB::table('MPerusahaan')->get();
        $check = $this->checkAccess('users.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.users.index', [
                'dataaa' => $data,
                'dataGudang' => $dataGudang,
                'dataPerusahaan' => $dataPerusahaan,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Users Master');
        }
    }

    public function searchGudangUsers(Request $request)
    {
        //
        $user = Auth::user();
        $gudang = $request->input('searchgudang');
        $data = DB::table('users')
            ->select(
                'users.*',
                'roles.name as roleName',
                'MGudang.cname as gudangName',
                'MGudang.UserIDKepalaDivisi',
                'MPerusahaan.cname as perusahaanName',
                'MPerusahaan.UserIDManager1',
                'MPerusahaan.UserIDManager2',
            )
            ->leftjoin('roles', 'users.idRole', '=', 'roles.id')
            ->leftjoin('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('users.MGudangID', 'like', '%' . $gudang . '%')
            //->get();
            ->paginate(10);
        //dd($data);
        //->get();
        $dataGudang = DB::table('MGudang')->get();
        $dataPerusahaan = DB::table('MPerusahaan')->get();
        $check = $this->checkAccess('users.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.users.index', [
                'dataaa' => $data,
                'dataGudang' => $dataGudang,
                'dataPerusahaan' => $dataPerusahaan,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Users Master');
        }
    }

    public function searchPerusahaanUsers(Request $request)
    {
        //
        $user = Auth::user();
        $perusahaan = $request->input('searchperusahaan');
        $data = DB::table('users')
            ->select(
                'users.*',
                'roles.name as roleName',
                'MGudang.cname as gudangName',
                'MGudang.UserIDKepalaDivisi',
                'MPerusahaan.cname as perusahaanName',
                'MPerusahaan.UserIDManager1',
                'MPerusahaan.UserIDManager2',
            )
            ->leftjoin('roles', 'users.idRole', '=', 'roles.id')
            ->leftjoin('MGudang', 'users.MGudangID', '=', 'MGudang.MGudangID')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->where('MPerusahaan.MPerusahaanID', 'like', '%' . $perusahaan . '%')
            //->get();
            ->paginate(10);
        //dd($data);
        //->get();
        $dataGudang = DB::table('MGudang')->get();
        $dataPerusahaan = DB::table('MPerusahaan')->get();
        $check = $this->checkAccess('users.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.users.index', [
                'dataaa' => $data,
                'dataGudang' => $dataGudang,
                'dataPerusahaan' => $dataPerusahaan,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Users Master');
        }
    }
}
