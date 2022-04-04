<?php

namespace App\Http\Controllers;

use App\Models\MSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
class MSupplierController extends Controller
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
        $data = DB::table('MSupplier')
            ->leftjoin('infoSupplier', 'MSupplier.InfoSupplierID','=','infoSupplier.InfoSupplierID')
            ->leftjoin('MCurrency','MSupplier.MCurrencyID','=','MCurrency.MCurrencyID')
            ->leftjoin('Tax','MSupplier.TaxID','=','Tax.TaxID')
            ->leftjoin('PaymentTerms','MSupplier.PaymentTermsID','=','PaymentTerms.PaymentTermsID')
            ->leftjoin('MKota','MSupplier.MKotaID','=','MKota.MKotaID')
            ->leftjoin('COA','MSupplier.COAID','=','COA.COAID')
            ->where('MSupplier.Hapus','=',0)
            ->get();
        return view('master.msupplier.index',[
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
        $infoSupplier = DB::table('infoSupplier')
            ->get();
        $MCurrency = DB::table('MCurrency')
            ->get();
        $Tax = DB::table('Tax')
            ->get();
        $PaymentTerms = DB::table('PaymentTerms')
            ->get();
        $MKota = DB::table('MKota')
            ->get();
        $COA = DB::table('COA')
            ->get();
        return view('master.msupplier.tambah',[
                'infoSupplier' => $infoSupplier,
                'MCurrency' => $MCurrency,
                'Tax' => $Tax,
                'PaymentTerms' => $PaymentTerms,
                'MKota' => $MKota,
                'COA' => $COA,
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
        
        DB::table('MSupplier')
            ->insert(array(
                'InfoSupplierID' => $data['infoSupplierID'],//combobox
                'PaymentTermsID' => $data['PaymentTermsID'],//combobox
                'MCurrencyID' => $data['mCurrencyID'],//combobox
                'TaxID' => $data['taxID'],//combobox
                'COAID' => $data['COAID'],//combobox
                'Name' => $data['name'],
                'Alamat' => $data['alamat'],
                'Kota' => $data['kota'],
                'KodePos' => $data['kodePos'],
                'Phone1' => $data['phone1'],
                'Phone2' => $data['phone2'],
                'Fax1' => $data['fax1'],
                'Fax2' => $data['fax2'],//
                'ContactPerson' => $data['contactPerson'],
                'Email' => $data['email'],
                'NPWP' => $data['NPWP'],
                'RekeningBank' => $data['rekeningBank'],
                'NoRekening' => $data['noRekening'],
                'Note' => $data['note'],
                'AtasNama' => $data['atasNama'],
                'Lokasi' => $data['lokasi'],
                'Kode' => $data['kode'],
                'Hapus' => 0,//
                'Keterangan' => $data['keterangan'],
                'SaldoDP' => $data['saldoDP'],
                'NamaNPWP' => $data['namaNPWP'],
                'SKT' => $data['SKT'],
                'SPPKP' => $data['SPPKP'],
                'KTP' => $data['KTP'],
                'MKotaID' => $data['mKotaID'],//combobox
                'Petani'=>$data['Petani'],
                'Khusus'=>$data['Khusus'],
                'CreatedBy'=> $user->id,//
                'CreatedOn'=> date("Y-m-d h:i:sa"),//
                'UpdatedBy'=> $user->id,//
                'UpdatedOn'=> date("Y-m-d h:i:sa"),//
                
            )
        );
         return redirect()->route('msupplier.index')->with('status','Success!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MSupplier  $mSupplier
     * @return \Illuminate\Http\Response
     */
    public function show(MSupplier $mSupplier)
    {
        //
        return view('master.msupplier.detail',[
            'mSupplier' => $mSupplier,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MSupplier  $mSupplier
     * @return \Illuminate\Http\Response
     */
    public function edit(MSupplier $msupplier)
    {
        //
        $infoSupplier = DB::table('infoSupplier')
            ->get();
        $MCurrency = DB::table('MCurrency')
            ->get();
        $Tax = DB::table('Tax')
            ->get();
        $PaymentTerms = DB::table('PaymentTerms')
            ->get();
        $MKota = DB::table('MKota')
            ->get();
        $COA = DB::table('COA')
            ->get();
        return view('master.msupplier.edit',[
                'infoSupplier' => $infoSupplier,
                'MCurrency' => $MCurrency,
                'Tax' => $Tax,
                'PaymentTerms' => $PaymentTerms,
                'MKota' => $MKota,
                'COA' => $COA,
                'msupplier' => $msupplier,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MSupplier  $mSupplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MSupplier $msupplier)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('MSupplier')
            ->where('SupplierID', $msupplier['SupplierID'])
            ->update(array(
                'InfoSupplierID' => $data['infoSupplierID'],
                'MCurrencyID' => $data['mCurrencyID'],
                'TaxID' => $data['taxID'],
                'COAID' => $data['COAID'],
                'Name' => $data['name'],
                'Alamat' => $data['alamat'],
                'Kota' => $data['kota'],
                'KodePos' => $data['kodePos'],
                'Phone1' => $data['phone1'],
                'Phone2' => $data['phone2'],
                'Fax1' => $data['fax1'],
                'Fax2' => $data['fax2'],
                'ContactPerson' => $data['contactPerson'],
                'Email' => $data['email'],
                'NPWP' => $data['NPWP'],
                'RekeningBank' => $data['rekeningBank'],
                'NoRekening' => $data['noRekening'],
                'Note' => $data['note'],
                'AtasNama' => $data['atasNama'],
                'Lokasi' => $data['lokasi'],
                'Kode' => $data['kode'],
                'Hapus' => 0,//
                'Keterangan' => $data['keterangan'],
                'SaldoDP' => $data['saldoDP'],
                'NamaNPWP' => $data['namaNPWP'],
                'SKT' => $data['SKT'],
                'SPPKP' => $data['SPPKP'],
                'KTP' => $data['KTP'],
                'MKotaID' => $data['mKotaID'],
                'UpdatedBy'=> $user->id,//
                'UpdatedOn'=> date("Y-m-d h:i:sa"),//
            )
        );
        return redirect()->route('msupplier.index')->with('status','Success!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MSupplier  $mSupplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(MSupplier $mSupplier)
    {
        //
        //$data = $request->collect();
        $user = Auth::user();
        dd($mSupplier);
        DB::table('MSupplier')
            ->where('SupplierID', $mSupplier->SupplierID)
            ->update(array(
                'Hapus' => 1,
                'UpdatedBy'=> $user->id,
                'UpdatedOn'=> date("Y-m-d h:i:sa"),
            )
        );
        return redirect()->route('msupplier.index')->with('status','Success!!');
    }
}
