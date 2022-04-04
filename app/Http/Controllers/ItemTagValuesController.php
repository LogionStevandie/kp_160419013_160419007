<?php

namespace App\Http\Controllers;

use App\Models\ItemTagValues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
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
            ->simplePaginate(10);
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
       // $check = $this->checkAccess('itemtagvalues.index', $user->id, $user->idRole);
        
       /* if($check){*/
            return view('master.itemtagvalues.index',[
                'dataItem' => $dataItem, //ke close ga gik lek tak close gak tab e sek
                'dataTag' => $dataTag
            ]);
       /* }
        else{
            return redirect()->route('home')->with('message','Anda tidak memiliki akses kedalam Item Master');
        }*/
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
        //
        $dataItem = DB::table('Item')
            ->get();
        $dataTag = DB::table('ItemTag')
            ->get();

        
       // $check = $this->checkAccess('item.index', $user->id, $user->idRole);
       /* if($check){*/
            return view('master.itemTagValues.tambah',[
                'dataItem' => $dataItem,
                'dataTag'=>$dataTag
            ]);
        //}
        /*else{
            return redirect()->route('home')->with('message','Anda tidak memiliki akses kedalam Item Master');
        }*/
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
        
        $dataTag = DB::table('ItemTag')
            ->where('Name', $data['Name'])
            ->get();
        if(count($dataTag) > 0){
            DB::table('ItemTagValues')->insert(array(
                'ItemID' => $data->idItem, //nanti di combobox nya ini
                'ItemTagID' => $dataTag->ItemTagID,
                )
           );
        }
        else{
            $idTag = DB::table('ItemTag')
                ->insertGetId(array(
                    'Name' => $data['Name'],
                    //'Desc' => $data['Desc'],
                    'CreatedBy'=> $user->id,
                    'CreatedOn'=> date("Y-m-d h:i:sa"),
                    'UpdatedBy'=> $user->id,
                    'UpdatedOn'=> date("Y-m-d h:i:sa"),
                )
            ); 
            DB::table('ItemTagValues')->insert(array(
                'ItemID' => $data->idItem, //nanti di combobox nya ini
                'ItemTagID' => $idTag,
                )
            );
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemTagValues  $itemTagValues
     * @return \Illuminate\Http\Response
     */
    public function show(ItemTagValues $itemTagValues)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemTagValues  $itemTagValues
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemTagValues $itemTagValues)
    {
        //

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemTagValues  $itemTagValues
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemTagValues $itemTagValues)
    {
        //
        $data = $request->collect();
        $user = Auth::user();

        DB::table('ItemTagValues')->insert(array(
            'ItemID' => $data->idItem, //nanti di combobox nya ini
            'ItemTagID' => $idTag,
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemTagValues  $itemTagValues
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemTagValues $itemTagValues)
    {
        //
        DB::table('ItemTagValues')
            ->where('ItemID', $data->idItem)
            ->where('ItemTagID', $data->idTag)
            ->delete();
    }
}
