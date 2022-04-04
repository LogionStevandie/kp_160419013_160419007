<?php

namespace App\Http\Controllers;

use App\Models\COA;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class COAController extends Controller
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
        $dataCOA = DB::table('COA')
            ->select('COA.*','COAHead.Nama as COAHeadName','COADetail.CDet_Name as COADetailName','COADetail.Keterangan as COADetailKeterangan')
            ->leftjoin('COAHead','COA.Chead','=','COAHead.CH_ID')
            ->leftjoin('COADetail','COA.Cdet','=','COADetail.COADetailID')
            ->get();
        return view('master.coa',[
            'dataCOA' => $dataCOA,
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
        $dataCOADetail = DB::table('COADetail')
            ->get();
        return view('master.itemCategory_tambah',[
            'dataCOAHead' => $dataCOAHead,
            'dataCOADetail' => $dataCOADetail,
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
        
        DB::table('COA')
            ->insert(array(
                'Nomor' => $data['nomor'],
                'Nama' => $data['nama'],
                'Chead' => $data['chead'],
                'Cdet' => $data['cdet'],
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
     * @param  \App\Models\COA  $cOA
     * @return \Illuminate\Http\Response
     */
    public function show(COA $cOA)
    {
        //
        /*$dataCOA = DB::table('COA')
            ->join('COADetail', 'COA.Cdet','=','COADetail.Cdet')
            ->join('COAHead', 'COA.Chead','=','COAHead.CH_ID')
            ->get();*/
        return view('master.coa_detail',[
            'cOA' => $cOA,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\COA  $cOA
     * @return \Illuminate\Http\Response
     */
    public function edit(COA $cOA)
    {
        //
        $dataCOAHead = DB::table('COAHead')
            ->get();
        $dataCOADetail = DB::table('COADetail')
            ->get();
        return view('master.coa_edit',[
            'cOA'=>$cOA,
            'dataCOAHead' => $dataCOAHead,
            'dataCOADetail' => $dataCOADetail,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\COA  $cOA
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, COA $cOA)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('COA')
            ->where('COAID', $cOA['id'])
            ->update(array(
                'Nomor' => $data['nomor'],
                'Nama' => $data['nama'],
                'Chead' => $data['chead'],
                'Cdet' => $data['cdet'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\COA  $cOA
     * @return \Illuminate\Http\Response
     */
    public function destroy(COA $cOA)
    {
        //
        //$cOA->delete();
        $user = Auth::user();
        DB::table('COADetail')
            ->where('CoaDetailID', $cOADetail['id'])
            ->update(array(
                'Hapus' => 1,
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
    }

    public function searchCoaName($namaCoa)
    {
        //
        $dataCOA = DB::table('COA')
            ->select('COA.*','COAHead.Nama as COAHeadName','COADetail.CDet_Name as COADetailName','COADetail.Keterangan as COADetailKeterangan')
            ->leftjoin('COAHead','COA.Chead','=','COAHead.CH_ID')
            ->leftjoin('COADetail','COA.Cdet','=','COADetail.COADetailID')
            ->where('COA.Nama','like','%'.$namaCoa.'%')
            ->get();
        return view('master.coa',[
            'dataCOA' => $dataCOA,
        ]);
    }
}