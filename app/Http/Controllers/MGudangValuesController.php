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
    public function edit(MGudang $tagValuesMGudang)
    {
        //
        $data = DB::table('MGudangAreaSimpan')
            ->get();
        $dataTag = DB::table('MGudangValues')
            ->rightjoin('MGudangAreaSimpan', 'MGudangValues.MGudangAreaSimpanID', '=', 'MGudangAreaSimpan.MGudangAreaSimpanID')
            ->where('MGudangValues.MGudangID',$tagValuesMGudang->MGudangID)
            ->get();
        //dd($dataTag);
        return view('master.tag.mGudang.edit',[
            'data' =>$data,
            'dataTag' => $dataTag,
            'mGudang' => $tagValuesMGudang,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MGudang $tagValuesMGudang)
    {
        //
        $data=$request->collect();
        //dd($data);
        $dataGudangValues = DB::table('MGudangValues')
            ->where('MGudangID', $tagValuesMGudang->MGudangID)
            ->get();

        if(count($dataGudangValues) > count($data['gudangAreaSimpan'])){
            DB::table('MGudangValues')
                ->where('MGudangID','=',$tagValuesMGudang->MGudangID)
                ->delete();

            for($i = 0; $i < count($data['gudangAreaSimpan']); $i++){
            DB::table('MGudangValues')
                ->insert(array(
                    'MGudangID' => $tagValuesMGudang->MGudangID,
                    'MGudangAreaSimpanID' => $data['gudangAreaSimpan'][$i],
                    )
                ); 
            }
        }
        else{
            for($i = 0; $i < count($data['gudangAreaSimpan']); $i++){
                if($i < count($dataGudangValues)){
                    DB::table('MGudangValues')
                        ->where('MGudangID', $tagValuesMGudang->MGudangID)
                        ->update(array(
                            'MGudangAreaSimpanID' => $data['gudangAreaSimpan'][$i],
                        )
                    );
                }
                else{
                    DB::table('MGudangValues')
                        ->insert(array(
                            'MGudangID' => $tagValuesMGudang->MGudangID,
                            'MGudangAreaSimpanID' => $data['gudangAreaSimpan'][$i],
                        )
                    ); 
                }
            }
        }
        return redirect()->route('tagValuesMGudang.index')->with('status','Success!!');
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

    public function searchGudangName(Request $request)
    {
        //
        $name = $request->input('searchname');
        $data = DB::table('MGudang')
            ->where('cname','like','%'.$name.'%')
            ->get();
        $dataTag = DB::table('MGudangValues')
            ->leftjoin('MGudangAreaSimpan', 'MGudangValues.MGudangAreaSimpanID', '=', 'MGudangAreaSimpan.MGudangAreaSimpanID')
            ->get();
        return view('master.tag.mGudang.index',[
            'data' => $data,
            'dataTag' => $dataTag,
        ]);
    }
}
