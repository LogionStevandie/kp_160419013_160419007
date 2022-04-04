<?php

namespace App\Http\Controllers;

use App\Models\ItemTracing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ItemTracingController extends Controller
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
        $data = DB::table('ItemTracing')
            ->get();
        return view('master.itemTracing.index',[
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
        return view('master.itemTracing.tambah');
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
        
        DB::table('ItemTracing')
            ->insert(array(
                'Name' => $data['Name'],
                'Notes' => $data['Notes'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
         return redirect()->route('itemTracing.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemTracing  $itemTracing
     * @return \Illuminate\Http\Response
     */
    public function show(ItemTracing $itemTracing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemTracing  $itemTracing
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemTracing $itemTracing)
    {
        //
        return view('master.itemTracing.edit',[
            'itemTracing'=>$itemTracing
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemTracing  $itemTracing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemTracing $itemTracing)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('ItemTracing')
            ->where('ItemTracingID', $itemTracing['ItemTracingID'])
            ->update(array(
                'Name' => $data['Name'],
                'Notes' => $data['Notes'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('itemTracing.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemTracing  $itemTracing
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemTracing $itemTracing)
    {
        //
        $itemTracing->delete();
        return redirect()->route('itemTracing.index')->with('status','Success!!');
    }


    public function searchItemTracingName($tracingName)
    {
        //
        $data = DB::table('ItemTracing')
            ->where('Name','like','%'.$tracingName.'%')
            ->get();
        return view('master.itemTracing',[
            'data' => $data,
        ]);
    }
}
