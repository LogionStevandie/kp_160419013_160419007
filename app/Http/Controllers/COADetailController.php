<?php

namespace App\Http\Controllers;

use App\Models\COADetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;


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
            ->get();
        return view('master.coaDetail',[
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
        return view('master.coaDetail_tambah',[
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
        
        DB::table('COAHead')
            ->insert(array(
                'Cdet' => $data['nama'],
                'CoaHead' => $data['coahead'],
                'CDet_Name' => $data['cdet_name'],
                'Keterangan' => $data['keterangan'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\COADetail  $cOADetail
     * @return \Illuminate\Http\Response
     */
    public function show(COADetail $cOADetail)
    {
        //
        /*$data = DB::table('COADetail')
            ->select('COADetail.*', 'COAHead.Nama as COAHeadName')
            ->leftjoin('COAHead','COADetail.CoaHead','=','COAHead.CH_ID')
            ->get();*/
        return view('master.coaDetail_detail',[
            'cOADetail' => $cOADetail,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\COADetail  $cOADetail
     * @return \Illuminate\Http\Response
     */
    public function edit(COADetail $cOADetail)
    {
        //
        $dataCOAHead = DB::table('COAHead')
            ->get();
        return view('master.coaDetail_edit',[
            'cOADetail'=>$cOADetail,
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
    public function update(Request $request, COADetail $cOADetail)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('COADetail')
            ->where('CoaDetailID', $cOADetail['id'])
            ->update(array(
                'Cdet' => $data['nama'],
                'CoaHead' => $data['coahead'],
                'CDet_Name' => $data['cdet_name'],
                'Keterangan' => $data['keterangan'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\COADetail  $cOADetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(COADetail $cOADetail)
    {
        //
        $cOADetail->destroy();
        
    }

    public function searchCoaDetailName($namaCoaDetail)
    {
        //
        $data = DB::table('COADetail')
            ->select('COADetail.*', 'COAHead.Nama as COAHeadName')
            ->leftjoin('COAHead','COADetail.CoaHead','=','COAHead.CH_ID')
            ->where('Cdet_Name','like','%'.$namaCoaDetail.'%')
            ->get();
        return view('master.coaDetail',[
            'data' => $data,
        ]);
    }
}
