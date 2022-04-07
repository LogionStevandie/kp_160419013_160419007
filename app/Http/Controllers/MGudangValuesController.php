<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MGudang;
use Illuminate\Support\Facades\DB;
use Auth;

class MGudangValuesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $data = DB::table('MGudang')
            ->get();
        $dataTag = DB::table('MGudangValues')
            ->leftjoin('MGudangAreaSimpan', 'MGudangValues.MGudangAreaSimpanID', '=', 'MGudangAreaSimpan.MGudangAreaSimpanID')
            ->get();
        return view('master.tag.mGudang.index',[
            'data' => $data,
            'dataTag' => $dataTag,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(MGudang $tagMGudang)
    {
        //
        $data = DB::table('MGudangAreaSimpan')
            ->get();
        $dataTag = DB::table('MGudangValues')
            ->rightjoin('MGudangAreaSimpan', 'MGudangValues.MGudangAreaSimpanID', '=', 'MGudangAreaSimpan.MGudangAreaSimpanID')
            ->where('MGudangValues.MGudangID',$tagMGudang->MGudangID)
            ->get();
        //dd($dataTag);
        return view('master.tag.mGudang.edit',[
            'data' =>$data,
            'dataTag' => $dataTag,
            'mGudang' => $tagMGudang,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MGudang $tagMGudang)
    {
        //
        $data=$request->collect();
        //dd($data);
        $dataGudangValues = DB::table('MGudangValues')
            ->where('MGudangID', $tagMGudang->MGudangID)
            ->get();

        if(count($dataGudangValues) > count($data['gudangAreaSimpan'])){
            DB::table('MGudangValues')
                ->where('MGudangID','=',$tagMGudang->MGudangID)
                ->delete();

            for($i = 0; $i < count($data['gudangAreaSimpan']); $i++){
            DB::table('MGudangValues')
                ->insert(array(
                    'MGudangID' => $tagMGudang->MGudangID,
                    'MGudangAreaSimpanID' => $data['gudangAreaSimpan'][$i],
                    )
                ); 
            }
        }
        else{
            for($i = 0; $i < count($data['gudangAreaSimpan']); $i++){
                if($i < count($dataGudangValues)){
                    DB::table('MGudangValues')
                        ->where('MGudangID', $tagMGudang->MGudangID)
                        ->update(array(
                            'MGudangAreaSimpanID' => $data['gudangAreaSimpan'][$i],
                        )
                    );
                }
                else{
                    DB::table('MGudangValues')
                        ->insert(array(
                            'MGudangID' => $tagMGudang->MGudangID,
                            'MGudangAreaSimpanID' => $data['gudangAreaSimpan'][$i],
                        )
                    ); 
                }
            }
        }
        return redirect()->route('tagMGudang.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
