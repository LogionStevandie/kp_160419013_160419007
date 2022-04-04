<?php

namespace App\Http\Controllers;

use App\Models\ItemTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

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
            ->get();
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
        return redirect()->route('ItemTag.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemTag  $itemTag
     * @return \Illuminate\Http\Response
     */
    public function show(ItemTag $ItemTag)
    {
        //
         return view('master.itemTag.detail',[
            'ItemTag'=>$ItemTag
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemTag  $ItemTag
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemTag $ItemTag)
    {
        //
        return view('master.itemTag.edit',[
            'ItemTag'=>$ItemTag
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemTag  $ItemTag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemTag $ItemTag)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('ItemTag')
            ->where('ItemTagID', $ItemTag['ItemTagID'])
            ->update(array(
                'Name' => $data['Name'],
                'Desc' => $data['Desc'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );

       return redirect()->route('ItemTag.index')->with('status','Success!!');           
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemTag  $ItemTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemTag $ItemTag)
    {
        //
        $ItemTag->delete();
       return redirect()->route('ItemTag.index')->with('status','Success!!');
    }

    public function searchItemTagName($tagName)
    {
        //
        $data = DB::table('ItemTag')
            ->where('Name','like','%'.$tagName.'%')
            ->get();
        return view('master.itemTag',[
            'data' => $data,
        ]);
    }
}
