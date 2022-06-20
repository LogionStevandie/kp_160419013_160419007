<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class ItemTagValuesController extends Controller
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

        $dataItem = DB::table('Item')
            //->limit(100)
            
            ->select('Item.*', 'ItemType.Name as typeName' ,'ItemType.Notes as typeNotes', 'Unit.Name as unitName', 
            'ItemCategory.Name as categoryName', 'ItemTracing.Name as tracingName')
            //, 'ItemTag.ItemTagID as tagID', 'ItemTag.Name as tagName')
            
            ->leftjoin('ItemType', 'Item.ItemTypeID', '=', 'ItemType.ItemTypeID')
            ->leftjoin('Unit', 'Item.UnitID', '=', 'Unit.UnitID') 
            ->leftjoin('ItemCategory', 'Item.ItemCategoryID', '=', 'ItemCategory.ItemCategoryID')  
            ->leftjoin('ItemTracing', 'Item.ItemTracingID', '=', 'ItemTracing.ItemTracingID')
            //->leftjoin('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            //->leftjoin('ItemTag', 'ItemTagValues.ItemTagID', '=', 'ItemTag.ItemTagID')
            ->where('Item.Hapus', '=', 0)
            ->paginate(10);
        //dd($dataItem);
        $dataTag = DB::table('ItemTag')
            ->leftjoin('ItemTagValues', 'ItemTag.ItemTagID', '=', 'ItemTagValues.ItemTagID')
            ->get();


        /*$access = DB::table('menu')
            ->select('menu.url')
            ->leftjoin('role_access', 'menu.MenuID', '=', 'role_access.idMenu')
            ->leftjoin('user_access', 'menu.MenuID', '=', 'user_access.idMenu')
            ->where('role_access.idRole',$user->idRole)
            ->orWhere('user_access.idUsers',$user->id)
            ->get();
        */

        $user = Auth::user();
        $check = $this->checkAccess('itemTagValues.index', $user->id, $user->idRole);
        
        if($check){
            return view('master.tag.item.index',[
                'dataItem' => $dataItem, //ke close ga gik lek tak close gak tab e sek
                'dataTag' => $dataTag
            ]);
        }
        else{
            return redirect()->route('home')->with('message','Anda tidak memiliki akses kedalam Index Tag Barang Values');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemTagValues  $itemTagValues
     * @return \Illuminate\Http\Response
     */
    public function show(Item $itemTagValue)
    {
        //
        $data = DB::table('ItemTag')
            ->get();
        $dataTag = DB::table('ItemTagValues')
            ->rightjoin('ItemTag', 'ItemTagValues.ItemTagID', '=', 'ItemTag.ItemTagID')
            ->where('ItemTagValues.ItemID',$itemTagValue->ItemID)
            ->get();
        //dd($dataTag);
        return view('master.tag.item.detail',[
            'data' =>$data,
            'dataTag' => $dataTag,
            'itemTagValues' => $itemTagValue,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemTagValues  $itemTagValues
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $itemTagValue)
    {
        //
        $data = DB::table('ItemTag')
            ->get();
        $dataTag = DB::table('ItemTagValues')
            ->rightjoin('ItemTag', 'ItemTagValues.ItemTagID', '=', 'ItemTag.ItemTagID')
            ->where('ItemTagValues.ItemID',$itemTagValue->ItemID)
            ->get();
        //dd($dataTag);
        
        $user = Auth::user();
        $check = $this->checkAccess('itemTagValues.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.tag.item.edit',[
                'data' =>$data,
                'dataTag' => $dataTag,
                'itemTagValues' => $itemTagValue,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Ubah Tag Barang Values');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemTagValues  $itemTagValues
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $itemTagValue)
    {
        //
        $user = Auth::user();

        $data=$request->collect();
        //dd($data);
        $dataItemValues = DB::table('ItemTagValues')
            ->where('ItemID', $itemTagValue->ItemID)
            ->get();

        DB::table('ItemTagValues')
            ->where('ItemID','=',$itemTagValue->ItemID)
            ->delete();
        for($i = 0; $i < count($data['itemTag']); $i++){
        DB::table('ItemTagValues')
            ->insert(array(
                'ItemID' => $itemTagValue->ItemID,
                'ItemTagID' => $data['itemTag'][$i],
                )
            ); 
        }
        return redirect()->route('itemTagValues.index')->with('status','Berhasil mengubah tag barang values');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemTagValues  $itemTagValues
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $itemTagValues)
    {
        //
    }

    public function searchItemName(Request $request)
    {
        //
        //dd($request);
        $name=$request->input('searchname');
        $user = Auth::user();

        $dataItem = DB::table('Item')
            //->limit(100)
            
            ->select('Item.*', 'ItemType.Name as typeName' ,'ItemType.Notes as typeNotes', 'Unit.Name as unitName', 
            'ItemCategory.Name as categoryName', 'ItemTracing.Name as tracingName')
            //, 'ItemTag.ItemTagID as tagID', 'ItemTag.Name as tagName')
            
            ->leftjoin('ItemType', 'Item.ItemTypeID', '=', 'ItemType.ItemTypeID')
            ->leftjoin('Unit', 'Item.UnitID', '=', 'Unit.UnitID') 
            ->leftjoin('ItemCategory', 'Item.ItemCategoryID', '=', 'ItemCategory.ItemCategoryID')  
            ->leftjoin('ItemTracing', 'Item.ItemTracingID', '=', 'ItemTracing.ItemTracingID')
            //->leftjoin('ItemTagValues', 'Item.ItemID', '=', 'ItemTagValues.ItemID')
            //->leftjoin('ItemTag', 'ItemTagValues.ItemTagID', '=', 'ItemTag.ItemTagID')
            ->where('Item.Hapus', '=', 0)
            ->where('Item.ItemName', 'like', '%'.$name.'%')
            ->paginate(10);
        //dd($dataItem);
        $dataTag = DB::table('ItemTag')
            ->leftjoin('ItemTagValues', 'ItemTag.ItemTagID', '=', 'ItemTagValues.ItemTagID')
            ->get();


        /*$access = DB::table('menu')
            ->select('menu.url')
            ->leftjoin('role_access', 'menu.MenuID', '=', 'role_access.idMenu')
            ->leftjoin('user_access', 'menu.MenuID', '=', 'user_access.idMenu')
            ->where('role_access.idRole',$user->idRole)
            ->orWhere('user_access.idUsers',$user->id)
            ->get();
        */
        $check = $this->checkAccess('itemTagValues.index', $user->id, $user->idRole);
        
        if($check){
            return view('master.tag.item.index',[
                'dataItem' => $dataItem, //ke close ga gik lek tak close gak tab e sek
                'dataTag' => $dataTag
            ]);
        }
        else{
            return redirect()->route('home')->with('message','Anda tidak memiliki akses kedalam Index Tag Barang Values');
        }
    }
}
