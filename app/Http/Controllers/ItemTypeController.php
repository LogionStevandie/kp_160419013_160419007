<?php

namespace App\Http\Controllers;

use App\Models\ItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ItemTypeController extends Controller
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
        $data = DB::table('ItemType')
        ->paginate(10);
        //->get();
        
        $user = Auth::user();
        $check = $this->checkAccess('itemType.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.itemType.index',[
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Tipe Barang');
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
        return view('master.itemType.tambah');
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
        
        DB::table('ItemType')
            ->insert(array(
                'Name' => $data['Name'],
                'Notes' => $data['Notes'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
        return redirect()->route('itemType.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemType  $itemType
     * @return \Illuminate\Http\Response
     */
    public function show(ItemType $itemType)
    {
        //
        return view('master.itemType.detail',[
            'itemType'=>$itemType
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemType  $itemType
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemType $itemType)
    {
        //
        return view('master.itemType.edit',[
            'itemType'=>$itemType
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemType  $itemType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemType $itemType)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('ItemType')
            ->where('ItemTypeID', $itemType['ItemTypeID'])
            ->update(array(
                'Name' => $data['Name'],
                'Notes' => $data['Notes'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('itemType.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemType  $itemType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemType $itemType)
    {
        //
        //$itemType->delete();
         DB::table('ItemType')->where('ItemTypeID', $itemType['ItemTypeID'])->delete();
        return redirect()->route('itemType.index')->with('status','Success!!');
    }

    public function searhItemTypeName(Request $request)
    {
        //
        $name = $request->input('searchname');
        $data = DB::table('ItemType')
            ->where('Name','like','%'.$name.'%')
            ->paginate(10);
        //->get();
        return view('master.itemType.index',[
            'data' => $data,
        ]);
    }
}
