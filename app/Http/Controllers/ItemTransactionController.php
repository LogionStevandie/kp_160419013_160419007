<?php

namespace App\Http\Controllers;

use App\Models\ItemTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ItemTransactionController extends Controller
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
        $data = DB::table('ItemTransaction')
            ->get();
        return view('master.itemTransaction.index',[
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
        return view('master.itemTransaction.tambah');
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
        
        DB::table('ItemTransaction')
            ->insert(array(
                'Name' => $data['Name'],
                'Description' => $data['Description'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 
        return redirect()->route('itemTransaction.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemTransaction  $itemTransaction
     * @return \Illuminate\Http\Response
     */
    public function show(ItemTransaction $itemTransaction)
    {
        //
        return view('master.itemTransaction.detail',[
            'itemTransaction'=>$itemTransaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemTransaction  $itemTransaction
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemTransaction $itemTransaction)
    {
        //
          return view('master.itemTransaction.edit',[
            'itemTransaction'=>$itemTransaction
        ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemTransaction  $itemTransaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemTransaction $itemTransaction)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('ItemTransaction')
            ->where('ItemTransactionID', $itemTransaction['ItemTransactionID'])
            ->update(array(
                'Name' => $data['Name'],
                'Description' => $data['Description'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('itemTransaction.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemTransaction  $itemTransaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemTransaction $itemTransaction)
    {
        //
        $itemTransaction->delete();
        return redirect()->route('itemTransaction.index')->with('status','Success!!');
    }

    public function searchItemTransactionName($transactionName)
    {
        //
        $data = DB::table('ItemTransaction')
            ->where('Name','like','%'.$transactionName.'%')
            ->get();
        return view('master.itemTransaction',[
            'data' => $data,
        ]);
    }
}
