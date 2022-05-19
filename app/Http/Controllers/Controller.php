<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkAccess($route, $userId, $roleId)
    {
        $access = DB::table('menu')
            ->select('menu.url')
            ->leftjoin('role_access', 'menu.MenuID', '=', 'role_access.idMenu')
            ->leftjoin('user_access', 'menu.MenuID', '=', 'user_access.idMenu')
            ->where('role_access.idRole',$roleId)
            ->orWhere('user_access.idUsers',$userId)
            ->get();
        $check = false;
        for($i = 0; $i < count($access); $i++ ){
            if($access[$i]->url == $route){
                $check = true;
            }
        }

        return ($check);
    }

}
