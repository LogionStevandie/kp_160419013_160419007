<?php

namespace App\Http\Controllers;

use App\Models\COADetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class COADetailController extends Controller
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
        $data = DB::table('COADetail')
            ->select('COADetail.*', 'COAHead.Nama as COAHeadName')
            ->leftjoin('COAHead','COADetail.CoaHead','=','COAHead.CH_ID')
            ->paginate(10);
            //->get();
        return view('master.COADetail.index',[
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
        $dataCOAHead = DB::table('COAHead')
            ->get();
        return view('master.COADetail.tambah',[
            'dataCOAHead' => $dataCOAHead,
        ]);
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
        
        DB::table('COADetail')
            ->insert(array(
                //'Cdet' => $data['cdet'],
                'CoaHead' => $data['coahead'],
                'CDet_Name' => $data['cdet_name'],
                'Keterangan' => $data['keterangan'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('coaDetail.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\COADetail  $cOADetail
     * @return \Illuminate\Http\Response
     */
    public function show(COADetail $coaDetail)
    {
        //
        /*$data = DB::table('COADetail')
            ->select('COADetail.*', 'COAHead.Nama as COAHeadName')
            ->leftjoin('COAHead','COADetail.CoaHead','=','COAHead.CH_ID')
            ->get();*/
        return view('master.COADetail.detail',[
            'coaDetail' => $coaDetail,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\COADetail  $cOADetail
     * @return \Illuminate\Http\Response
     */
    public function edit(COADetail $coaDetail)
    {
        //
        $dataCOAHead = DB::table('COAHead')
            ->get();
        return view('master.COADetail.edit',[
            'coaDetail'=>$coaDetail,
            'dataCOAHead'=>$dataCOAHead,

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\COADetail  $cOADetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, COADetail $coaDetail)
    {
        //
        $data = $request->collect();
        //d//d($data);
        $user = Auth::user();
        DB::table('COADetail')
            ->where('Cdet', $coaDetail['Cdet'])
            ->update(array(
                //'Cdet' => $data['nama'],
                'CoaHead' => $data['coahead'],
                'CDet_Name' => $data['cdet_name'],
                'Keterangan' => $data['keterangan'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('coaDetail.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\COADetail  $cOADetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(COADetail $coaDetail)
    {
        //
        //dd($coaDetail);
        $coaDetail->delete();      
        return redirect()->route('coaDetail.index')->with('status','Success!!');
    }

    public function searchCoaDetailName(Request $request)
    {
        //
        $name=$request->input('searchname');

        $data = DB::table('COADetail')
            ->select('COADetail.*', 'COAHead.Nama as COAHeadName')
            ->leftjoin('COAHead','COADetail.CoaHead','=','COAHead.CH_ID')
            ->where('COADetail.CDet_Name','like','%'.$name.'%')
            ->paginate(10);
            //->get();
        return view('master.COADetail.index',[
            'data' => $data,
        ]);
    }

    public function searchCoaDetailKeterangan(Request $request)
    {
        //
        $ket=$request->input('searchketerangan');

        $data = DB::table('COADetail')
            ->select('COADetail.*', 'COAHead.Nama as COAHeadName')
            ->leftjoin('COAHead','COADetail.CoaHead','=','COAHead.CH_ID')
            ->where('COADetail.Keterangan','like','%'.$ket.'%')
            ->paginate(10);
            //->get();
        return view('master.COADetail.index',[
            'data' => $data,
        ]);
    }

}
