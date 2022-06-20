<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ItemCategoryController extends Controller
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
        $dataCategory = DB::table('ItemCategory')
            ->paginate(10);
        //->get();
        $dataCOA = DB::table('COA')
            ->get();


        $user = Auth::user();

        $check = $this->checkAccess('itemCategory.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.itemCategory.index', [
                'dataCategory' => $dataCategory,
                'dataCOA' => $dataCOA,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Kategori Barang');
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
        $dataCOA = DB::table('COA')
            ->get();

        $user = Auth::user();
        $check = $this->checkAccess('itemCategory.index', $user->id, $user->idRole);
        if ($check) {
            return view('master.itemCategory.tambah', [
                'dataCOA' => $dataCOA,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Tambah Kategori Barang');
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

        DB::table('ItemCategory')
            ->insert(
                array(
                    'Name' => $data['Name'],
                    'Remarks' => $data['remarks'],
                    //'NTBDebetCOA' => $data['NTBDebetCOA'],
                    //'NTBKreditCOA' => $data['NTBKreditCOA'],
                    //'BillVDebetCOA' => $data['BillVDebetCOA'],
                    //'BillVKreditCOA' => $data['BillVKreditCOA'],
                    //'PenjualanCOA' => $data['PenjualanCOA'],
                    'CreatedBy' => $user->id,
                    'CreatedOn' => date("Y-m-d h:i:sa"),
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                )
            );
        return redirect()->route('itemCategory.index')->with('status', 'Berhasil menambahkan kategori barang');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ItemCategory $itemCategory)
    {
        //
        $dataCategory = DB::table('ItemCategory')
            ->get();
        $dataCOA = DB::table('COA')
            ->get();


        $user = Auth::user();
        $check = $this->checkAccess('itemCategory.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.itemCategory.detail', [
                'itemCategory' => $itemCategory,
                'dataCategory' => $dataCategory,
                'dataCOA' => $dataCOA,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Detail Kategori Barang');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemCategory $itemCategory)
    {
        //
        $dataCOA = DB::table('COA')
            ->get();


        $user = Auth::user();
        $check = $this->checkAccess('itemCategory.edit', $user->id, $user->idRole);
        if ($check) {
            return view('master.itemCategory.edit', [
                'itemCategory' => $itemCategory,
                'dataCOA' => $dataCOA,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Ubah Kategori Barang');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemCategory $itemCategory)
    {
        //
        $data = $request->collect();
        $user = Auth::user();
        DB::table('ItemCategory')
            ->where('ItemCategoryID', $itemCategory['ItemCategoryID'])
            ->update(
                array(
                    'Name' => $data['Name'],
                    'Remarks' => $data['remarks'],
                    //'NTBDebetCOA' => $data['NTBDebetCOA'],
                    //'NTBKreditCOA' => $data['NTBKreditCOA'],
                    //'BillVDebetCOA' => $data['BillVDebetCOA'],
                    //'BillVKreditCOA' => $data['BillVKreditCOA'],
                    //'PenjualanCOA' => $data['PenjualanCOA'],
                    'UpdatedBy' => $user->id,
                    'UpdatedOn' => date("Y-m-d h:i:sa"),
                )
            );
        return redirect()->route('itemCategory.index')->with('status', 'Berhasil mengubah kategori barang');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemCategory $itemCategory)
    {
        //$itemCategory->delete();

        $user = Auth::user();
        $check = $this->checkAccess('itemCategory.edit', $user->id, $user->idRole);
        if ($check) {
            DB::table('ItemCategory')->where('ItemCategoryID', $itemCategory['ItemCategoryID'])->delete();
            return redirect()->route('itemCategory.index')->with('status', 'Berhasil menghapus kategori barang');
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Hapus Kategori Barang');
        }
    }

    public function selectItemCategoryName(Request $request)
    {
        //
        $name = $request->input('searchname');
        $user = Auth::user();

        $dataCategory = DB::table('ItemCategory')
            ->where('Name', 'like', '%' . $name . '%')
            ->paginate(10);
        //->get();
        $dataCOA = DB::table('COA')
            ->get();


        $user = Auth::user();
        $check = $this->checkAccess('itemCategory.show', $user->id, $user->idRole);
        if ($check) {
            return view('master.itemCategory.index', [
                'dataCategory' => $dataCategory,
                'dataCOA' => $dataCOA,
            ]);
        } else {
            return redirect()->route('home')->with('message', 'Anda tidak memiliki akses kedalam Index Kategori Barang');
        }
    }
}
