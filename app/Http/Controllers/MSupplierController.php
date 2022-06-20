<?php

namespace App\Http\Controllers;

use App\Models\MSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $data = DB::table('MSupplier') //menkrap
            ->select(
                'MSupplier.*',
                'infoSupplier.name as infoSupplierName',
                'MCurrency.name as currencyName',
                'Tax.Name as taxName',
                'PaymentTerms.Name as paymentTermsName',
                'MKota.cname as kotaName',
                'COA.Nama as coaName'
            )
            ->leftjoin('infoSupplier', 'MSupplier.InfoSupplierID', '=', 'infoSupplier.InfoSupplierID')
            ->leftjoin('MCurrency', 'MSupplier.MCurrencyID', '=', 'MCurrency.MCurrencyID')
            ->leftjoin('Tax', 'MSupplier.TaxID', '=', 'Tax.TaxID')
            ->leftjoin('PaymentTerms', 'MSupplier.PaymentTermsID', '=', 'PaymentTerms.PaymentTermsID')
            ->leftjoin('MKota', 'MSupplier.MKotaID', '=', 'MKota.MKotaID')
            ->leftjoin('COA', 'MSupplier.COAID', '=', 'COA.COAID')
            ->where('MSupplier.Hapus', '=', 0)
            ->paginate(10);
        //->get();

        $user = Auth::user();
        $check = $this->checkAccess('msupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.msupplier.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Supplier');
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
        $Bank = DB::table('bank')
            ->get();


        $user = Auth::user();
        $check = $this->checkAccess('msupplier.create', $user->id, $user->idRole);
        if ($check) {
            return view('master.msupplier.tambah', [
                'infoSupplier' => $infoSupplier,
                'MCurrency' => $MCurrency,
                'Tax' => $Tax,
                'PaymentTerms' => $PaymentTerms,
                'MKota' => $MKota,
                'COA' => $COA,
                'bank' => $Bank,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Supplier');
        }
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
            ->insert(
                array(
                    'InfoSupplierID' => $data['infoSupplierID'], //combobox
                    'Name' => $data['name'],
                    'Alamat' => $data['alamat'],
                    'Kota' => $data['kota'],
                    'KodePos' => $data['kodePos'],
                    'Phone1' => $data['phone1'],
                    'Phone2' => $data['phone2'],
                    'Fax1' => $data['fax1'],
                    'Fax2' => $data['fax2'], //
                    'ContactPerson' => $data['contactPerson'],
                    'Email' => $data['email'],
                    'NPWP' => $data['NPWP'],
                    'bank' => $data['bank'],
                    'NoRekening' => $data['noRekening'],
                    'Note' => $data['note'],
                    'AtasNama' => $data['atasNama'],
                    'Lokasi' => $data['lokasi'],
                    'Kode' => $data['kode'],
                    'Hapus' => 0, //
                    'Keterangan' => $data['keterangan'],
                    'NamaNPWP' => $data['namaNPWP'],
                    'KTP' => $data['KTP'],
                    'CreatedBy' => $user->id, //
                    'CreatedOn' => date("Y-m-d h:i:sa"), //
                    'UpdatedBy' => $user->id, //
                    'UpdatedOn' => date("Y-m-d h:i:sa"), //

                )
            );
        return redirect()->route('msupplier.index')->with('status', 'Berhasil Menambah Data Supplier!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MSupplier  $mSupplier
     * @return \Illuminate\Http\Response
     */
    public function show(MSupplier $msupplier)
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
        $Bank = DB::table('bank')
            ->get();


        $user = Auth::user();
        $check = $this->checkAccess('msupplier.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.msupplier.detail', [
                'infoSupplier' => $infoSupplier,
                'MCurrency' => $MCurrency,
                'Tax' => $Tax,
                'PaymentTerms' => $PaymentTerms,
                'MKota' => $MKota,
                'COA' => $COA,
                'msupplier' => $msupplier,
                'bank' => $Bank,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Supplier');
        }
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
        $Bank = DB::table('bank')
            ->get();


        $user = Auth::user();
        $check = $this->checkAccess('msupplier.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.msupplier.edit', [
                'infoSupplier' => $infoSupplier,
                'MCurrency' => $MCurrency,
                'Tax' => $Tax,
                'PaymentTerms' => $PaymentTerms,
                'MKota' => $MKota,
                'COA' => $COA,
                'msupplier' => $msupplier,
                'bank' => $Bank,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Supplier');
        }
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
            ->update(
                array(
                    'InfoSupplierID' => $data['infoSupplierID'], //combobox
                    'Name' => $data['name'],
                    'Alamat' => $data['alamat'],
                    'Kota' => $data['kota'],
                    'KodePos' => $data['kodePos'],
                    'Phone1' => $data['phone1'],
                    'Phone2' => $data['phone2'],
                    'Fax1' => $data['fax1'],
                    'Fax2' => $data['fax2'], //
                    'ContactPerson' => $data['contactPerson'],
                    'Email' => $data['email'],
                    'NPWP' => $data['NPWP'],
                    'bank' => $data['bank'],
                    'NoRekening' => $data['noRekening'],
                    'Note' => $data['note'],
                    'AtasNama' => $data['atasNama'],
                    'Lokasi' => $data['lokasi'],
                    'Kode' => $data['kode'],
                    'Hapus' => 0, //
                    'Keterangan' => $data['keterangan'],
                    'NamaNPWP' => $data['namaNPWP'],
                    'KTP' => $data['KTP'],
                    'UpdatedBy' => $user->id, //
                    'UpdatedOn' => date("Y-m-d h:i:sa"), //
                )
            );
        return redirect()->route('msupplier.index')->with('status', 'Berhasil Mengupdate Data Supplier!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MSupplier  $mSupplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(MSupplier $msupplier)
    {
        //
        //$data = $request->collect();
        $user = Auth::user();
        $check = $this->checkAccess('msupplier.edit', $user->id, $user->idRole);
        if ($check) {
            DB::table('MSupplier')
                ->where('SupplierID', $msupplier['SupplierID'])
                ->update(
                    array(
                        'Hapus' => 1,
                        'UpdatedBy' => $user->id,
                        'UpdatedOn' => date("Y-m-d h:i:sa"),
                    )
                );
            return redirect()->route('msupplier.index')->with('status', 'Berhasil Menghapus Data Supplier!!');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Supplier');
        }
        //dd($msupplier);

    }

    public function searchSupplierName(Request $request)
    {
        $name = $request->input('searchname');
        $data = DB::table('MSupplier')
            ->select(
                'MSupplier.*',
                'infoSupplier.name as infoSupplierName',
                'MCurrency.name as currencyName',
                'Tax.Name as taxName',
                'PaymentTerms.Name as paymentTermsName',
                'MKota.cname as kotaName',
                'COA.Nama as coaName'
            )
            ->leftjoin('infoSupplier', 'MSupplier.InfoSupplierID', '=', 'infoSupplier.InfoSupplierID')
            ->leftjoin('MCurrency', 'MSupplier.MCurrencyID', '=', 'MCurrency.MCurrencyID')
            ->leftjoin('Tax', 'MSupplier.TaxID', '=', 'Tax.TaxID')
            ->leftjoin('PaymentTerms', 'MSupplier.PaymentTermsID', '=', 'PaymentTerms.PaymentTermsID')
            ->leftjoin('MKota', 'MSupplier.MKotaID', '=', 'MKota.MKotaID')
            ->leftjoin('COA', 'MSupplier.COAID', '=', 'COA.COAID')
            ->where('MSupplier.Hapus', '=', 0)
            ->where('MSupplier.Name', 'like', '%' . $name . '%')
            ->paginate(10);
        //->get();

        $user = Auth::user();
        $check = $this->checkAccess('msupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.msupplier.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Supplier');
        }
    }

    public function searchSupplierAtasNama(Request $request)
    {
        $name = $request->input('searchatasnama');
        $data = DB::table('MSupplier')
            ->select(
                'MSupplier.*',
                'infoSupplier.name as infoSupplierName',
                'MCurrency.name as currencyName',
                'Tax.Name as taxName',
                'PaymentTerms.Name as paymentTermsName',
                'MKota.cname as kotaName',
                'COA.Nama as coaName'
            )
            ->leftjoin('infoSupplier', 'MSupplier.InfoSupplierID', '=', 'infoSupplier.InfoSupplierID')
            ->leftjoin('MCurrency', 'MSupplier.MCurrencyID', '=', 'MCurrency.MCurrencyID')
            ->leftjoin('Tax', 'MSupplier.TaxID', '=', 'Tax.TaxID')
            ->leftjoin('PaymentTerms', 'MSupplier.PaymentTermsID', '=', 'PaymentTerms.PaymentTermsID')
            ->leftjoin('MKota', 'MSupplier.MKotaID', '=', 'MKota.MKotaID')
            ->leftjoin('COA', 'MSupplier.COAID', '=', 'COA.COAID')
            ->where('MSupplier.Hapus', '=', 0)
            ->where('MSupplier.AtasNama', 'like', '%' . $name . '%')
            ->paginate(10);
        //->get();
        $user = Auth::user();
        $check = $this->checkAccess('msupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.msupplier.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Supplier');
        }
    }

    public function searchSupplierLokasi(Request $request)
    {
        $lokasi = $request->input('searchlokasi');
        $data = DB::table('MSupplier')
            ->select(
                'MSupplier.*',
                'infoSupplier.name as infoSupplierName',
                'MCurrency.name as currencyName',
                'Tax.Name as taxName',
                'PaymentTerms.Name as paymentTermsName',
                'MKota.cname as kotaName',
                'COA.Nama as coaName'
            )
            ->leftjoin('infoSupplier', 'MSupplier.InfoSupplierID', '=', 'infoSupplier.InfoSupplierID')
            ->leftjoin('MCurrency', 'MSupplier.MCurrencyID', '=', 'MCurrency.MCurrencyID')
            ->leftjoin('Tax', 'MSupplier.TaxID', '=', 'Tax.TaxID')
            ->leftjoin('PaymentTerms', 'MSupplier.PaymentTermsID', '=', 'PaymentTerms.PaymentTermsID')
            ->leftjoin('MKota', 'MSupplier.MKotaID', '=', 'MKota.MKotaID')
            ->leftjoin('COA', 'MSupplier.COAID', '=', 'COA.COAID')
            ->where('MSupplier.Hapus', '=', 0)
            ->where('MSupplier.AtasNama', 'like', '%' . $lokasi . '%')
            ->paginate(10);
        //->get();
        $user = Auth::user();
        $check = $this->checkAccess('msupplier.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.msupplier.index', [
                'data' => $data,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Supplier');
        }
    }
}
