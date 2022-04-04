<?php

namespace App\Http\Controllers;

use App\Models\MGudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MGudangController extends Controller
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
        $data = DB::table('MGudang')
            ->select('MGudang.*', 'MPerusahaan.cname as perusahaanName','MPerusahaan.cnames as perusahaanNames','users.name as manager', 
            'MKota.cname as kotaName','MProvinsi.cname as provinsiName', 'MPulau.cname as pulauName', 
            'MGudangAreaSimpan.MGudangAreaSimpanID as gudangAreaSimpanID', 'MGudangAreaSimpan.cname as gudangAreaSimpanName')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->leftjoin('users','MPerusahaan.UserIDManager','=','users.id')
            ->leftjoin('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->leftjoin('MPulau','MKota.cidpulau','=','MPulau.cidpulau')
            ->leftjoin('MProvinsi','MKota.cidprov','=','MProvinsi.cidprov')
            ->leftjoin('MGudangValues', 'MGudang.MGudangID', '=', 'MGudangValues.MGudangID')
            ->leftjoin('MGudangAreaSimpan', 'MGudangValues.MGudangAreaSimpanID', '=', 'MGudangAreaSimpan.MGudangAreaSimpanID')
            ->get();

        return view('master.mGudang',[
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
        $dataMKota = DB::table('MKota')
            ->get();
        $dataMPerusahaan = DB::table('MPerusahaan')
            ->get();    
        $dataMGudangAreaSimpan = DB::table('MGudangAreaSimpan')
            ->get();     
        return view('master.mGudang.tambah',[
            'dataMKota' => $dataMKota,
            'dataMPerusahaan' => $dataMPerusahaan,
            'dataMGudangAreaSimpan' => $dataMGudangAreaSimpan,
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
        
        $idGudang = DB::table('MGudang')
            ->insertGetId(array(
                'ccode' => $data['code'],
                'cname' => $data['name'],   
                'cidp' => $data['perusahaan'],
                'cidkota' => $data['kota'],
                'CreatedBy'=> $user->id,
                'CreatedOn'=> date("Y-m-d h:i:sa"),
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        ); 

        for($i = 0; $i < count($data['gudangAreaSimpanTotal']); $i++){
            DB::table('MGudangValues')->insert(array(
                'MGudangID' => $idGudang,
                'MGudangAreaSimpanID' => $data['gudangAreaSimpanID'][$i],
                )
            ); 
        }

        return redirect()->route('gudang.index')->with('status','Success!!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MGudang  $mGudang
     * @return \Illuminate\Http\Response
     */
    public function show(MGudang $mGudang)
    {
        //
        /*$data = DB::table('MGudang')
            ->join('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->join('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->get();*/
        return view('master.mGudang.detail',[
            'mGudang' => $mGudang,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MGudang  $mGudang
     * @return \Illuminate\Http\Response
     */
    public function edit(MGudang $mGudang)
    {
        //
        $dataMKota = DB::table('MKota')
            ->get();
        $dataMPerusahaan = DB::table('MPerusahaan')
            ->get();    
        $dataMGudangAreaSimpan = DB::table('MGudangAreaSimpan')
            ->get();  
        return view('master.mGudang_edit',[
            'mGudang' =>$mGudang,
            'dataMKota' => $dataMKota,
            'dataMPerusahaan' => $dataMPerusahaan,
            'dataMGudangAreaSimpan' => $dataMGudangAreaSimpan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MGudang  $mGudang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MGudang $mGudang)
    {   
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('MGudang')
            ->where('MGudangID', $mGudang['id'])
            ->update(array(
                'ccode' => $data['code'],
                'cname' => $data['name'],   
                'cidp' => $data['perusahaan'],
                'cidkota' => $data['kota'],
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );

        $dataGudangValues = DB::table('MGudangValues')
            ->where('MGudangID', $mGudang->MGudangID)
            ->get();

        if(count($dataTagValues) > count($data['gudangAreaSimpanTotal'])){
            DB::table('MGudangValues')
                ->where('MGudangID','=',$mGudang->MGudangID)
                ->delete();

            for($i = 0; $i < count($data['gudangAreaSimpanTotal']); $i++){
            DB::table('MGudangValues')
                ->insert(array(
                    'MGudangID' => $mGudang->MGudangID,
                    'MGudangAreaSimpanID' => $data['mGudangAreaSimpanID'][$i],
                    )
                ); 
            }
        }
        else{
            for($i = 0; $i < count($data['gudangAreaSimpanTotal']); $i++){
                if($i < count($dataTagValues)){
                    DB::table('MGudangValues')
                        ->where('MGudangID', $mGudang->MGudangID)
                        ->update(array(
                            'MGudangAreaSimpanID' => $data['mGudangAreaSimpanID'][$i],
                        )
                    );
                }
                else{
                    DB::table('MGudangValues')
                        ->insert(array(
                            'MGudangID' => $mGudang->MGudangID,
                            'MGudangAreaSimpanID' => $data['mGudangAreaSimpanID'][$i],
                        )
                    ); 
                }
            }
        }
        return redirect()->route('gudang.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MGudang  $mGudang
     * @return \Illuminate\Http\Response
     */
    public function destroy(MGudang $mGudang)
    {
        //
        $mGudang->destroy();
        DB::table('MGudangValues')
            ->where('MGudangID','=',$mGudang->MGudangID)
            ->delete();

        return redirect()->route('gudang.index')->with('status','Success!!');
    }

    public function searchGudangName($gudangName)
    {
        //
        $data = DB::table('MGudang')
            ->select('MGudang.*', 'MPerusahaan.cname as perusahaanName','MPerusahaan.cnames as perusahaanNames','users.name as manager', 
            'MKota.cname as kotaName','MProvinsi.cname as provinsiName', 'MPulau.cname as pulauName', 
            'MGudangAreaSimpan.MGudangAreaSimpanID as gudangAreaSimpanID', 'MGudangAreaSimpan.cname as gudangAreaSimpanName')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->leftjoin('users','MPerusahaan.UserIDManager','=','users.id')
            ->leftjoin('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->leftjoin('MPulau','MKota.cidpulau','=','MPulau.cidpulau')
            ->leftjoin('MProvinsi','MKota.cidprov','=','MProvinsi.cidprov')
            ->leftjoin('MGudangValues', 'MGudang.MGudangID', '=', 'MGudangValues.MGudangID')
            ->leftjoin('MGudangAreaSimpan', 'MGudangValues.MGudangAreaSimpanID', '=', 'MGudangAreaSimpan.MGudangAreaSimpanID')
            ->where('MGudang.cname','like','%'.$gudangName.'%')
            ->get();

        return view('master.mGudang',[
            'data' => $data,
        ]);
    }

    public function searchGudangCode($gudangCode)
    {
        //
        $data = DB::table('MGudang')
            ->select('MGudang.*', 'MPerusahaan.cname as perusahaanName','MPerusahaan.cnames as perusahaanNames','users.name as manager', 
            'MKota.cname as kotaName','MProvinsi.cname as provinsiName', 'MPulau.cname as pulauName', 
            'MGudangAreaSimpan.MGudangAreaSimpanID as gudangAreaSimpanID', 'MGudangAreaSimpan.cname as gudangAreaSimpanName')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->leftjoin('users','MPerusahaan.UserIDManager','=','users.id')
            ->leftjoin('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->leftjoin('MPulau','MKota.cidpulau','=','MPulau.cidpulau')
            ->leftjoin('MProvinsi','MKota.cidprov','=','MProvinsi.cidprov')
            ->leftjoin('MGudangValues', 'MGudang.MGudangID', '=', 'MGudangValues.MGudangID')
            ->leftjoin('MGudangAreaSimpan', 'MGudangValues.MGudangAreaSimpanID', '=', 'MGudangAreaSimpan.MGudangAreaSimpanID')
            ->where('MGudang.ccode','like','%'.$gudangCode.'%')
            ->get();

        return view('master.mGudang',[
            'data' => $data,
        ]);
    }

    public function searchGudangKota($kotaName)
    {
        //
        $data = DB::table('MGudang')
            ->select('MGudang.*', 'MPerusahaan.cname as perusahaanName','MPerusahaan.cnames as perusahaanNames','users.name as manager', 
            'MKota.cname as kotaName','MProvinsi.cname as provinsiName', 'MPulau.cname as pulauName', 
            'MGudangAreaSimpan.MGudangAreaSimpanID as gudangAreaSimpanID', 'MGudangAreaSimpan.cname as gudangAreaSimpanName')
            ->leftjoin('MPerusahaan', 'MGudang.cidp', '=', 'MPerusahaan.MPerusahaanID')
            ->leftjoin('users','MPerusahaan.UserIDManager','=','users.id')
            ->leftjoin('MKota', 'MGudang.cidkota', '=', 'MKota.cidkota')
            ->leftjoin('MPulau','MKota.cidpulau','=','MPulau.cidpulau')
            ->leftjoin('MProvinsi','MKota.cidprov','=','MProvinsi.cidprov')
            ->leftjoin('MGudangValues', 'MGudang.MGudangID', '=', 'MGudangValues.MGudangID')
            ->leftjoin('MGudangAreaSimpan', 'MGudangValues.MGudangAreaSimpanID', '=', 'MGudangAreaSimpan.MGudangAreaSimpanID')
            ->where('MKota.cname','like','%'.$kotaName.'%')
            ->get();

        return view('master.mGudang',[
            'data' => $data,
        ]);
    }

}


