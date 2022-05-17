<?php

namespace App\Http\Controllers;

use App\Models\ItemTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ItemTagController extends Controller
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
        $data = DB::table('ItemTag')
            ->paginate(10);
            //->get();
        return view('master.itemTag.index',[
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
        return view('master.itemTag.tambah');
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
        
        DB::table('ItemTag')
            ->insert(array(
                'Name' => $data['Name'],
                'Desc' => $data['Desc'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
        return redirect()->route('itemTag.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemTag  $itemTag
     * @return \Illuminate\Http\Response
     */
    public function show(ItemTag $itemTag)
    {
        //
         return view('master.itemTag.detail',[
            'ItemTag'=>$itemTag
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemTag  $ItemTag
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemTag $itemTag)
    {
        //
        return view('master.itemTag.edit',[
            'ItemTag'=>$itemTag
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemTag  $ItemTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemTag $itemTag)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('ItemTag')
            ->where('ItemTagID', $itemTag['ItemTagID'])
            ->update(array(
                'Name' => $data['Name'],
                'Desc' => $data['Desc'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );

       return redirect()->route('itemTag.index')->with('status','Success!!');           
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemTag  $ItemTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemTag $itemTag)
    {
        //
        //$itemTag->delete();
        DB::table('ItemTag')->where('ItemTagID', $itemTag['ItemTagID'])->delete();
       return redirect()->route('itemTag.index')->with('status','Success!!');
    }

    public function searchItemTagName(Request $request)
    {
        //
        $name = $request->input('searchname');
        $data = DB::table('ItemTag')
            ->where('Name','like','%'.$name.'%')
            ->paginate(10);
            //->get();
        return view('master.itemTag.index',[
            'data' => $data,
        ]);
    }
}
