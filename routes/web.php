<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


/*bank   - no debug still dont know  belum dibuat blade nya*/
Route::resource('bank', 'App\Http\Controllers\BankController');






/*==================================yang belom==========================================*/ 
//Menu (ex:master, home, pemesanan, profil, dll) 
Route::resource('menu', 'App\Http\Controllers\MenuController');

//Role(ex:Pegawai, ketua divisi)
Route::resource('role', 'App\Http\Controllers\RoleController');
    
//SubMenu (ex:barang_tambah, barang_edit, npp_tambah, po_tambah, dll   nyimpen id menu) 
//Route::resource('submenu', 'App\Http\Controllers\SubMenuController');

//UserAccess (mnm dari 2 diatas) (ex:nama e tambah apa , access nya itu boolean (1 dan 0) nyimpen id submenu, dll) 
//Route::resource('userAccess', 'App\Http\Controllers\UserAccessController');


//fixed ***********************************************************


//COA baru
Route::resource('coa', 'App\Http\Controllers\COAController');
Route::resource('coaDetail', 'App\Http\Controllers\COADetailController');
Route::resource('coaHead', 'App\Http\Controllers\COAHeadController');




//payment and paymentterms
Route::resource('payment', 'App\Http\Controllers\PaymentController');
Route::resource('paymentTerms', 'App\Http\Controllers\PaymentTermsController');

//item
Route::resource('item', 'App\Http\Controllers\ItemController');//
Route::get('/iteme/searchname/',[App\Http\Controllers\ItemController::class, 'searchItemName']); //cobak gini ta
Route::get('/iteme/searchtag/',[App\Http\Controllers\ItemController::class, 'searchItemTagName']); //cobak gini ta
Route::get('/iteme/searchtagmulti/',[App\Http\Controllers\ItemController::class, 'searchItemTagMulti']); //cobak gini ta
//Route::get('/item/searchname/', [App\Http\Controllers\ItemController::class, 'searchItemName'])->name('searchItemName');
Route::resource('itemCategory', 'App\Http\Controllers\ItemCategoryController');//selese,delete masik gbs

Route::resource('itemTag', 'App\Http\Controllers\ItemTagController');//

Route::resource('itemTracing', 'App\Http\Controllers\ItemTracingController');//selese

Route::resource('itemTransaction', 'App\Http\Controllers\ItemTransactionController');//selese

Route::resource('itemType', 'App\Http\Controllers\ItemTypeController');//selese,delete masik gbs

Route::resource('itemTagValues', 'App\Http\Controllers\ItemTagValuesController');

Route::resource('unit', 'App\Http\Controllers\UnitController');

//mPulau
Route::resource('mPulau', 'App\Http\Controllers\MPulauController');//selese

//mProvinsi
Route::resource('mProvinsi', 'App\Http\Controllers\MProvinsiController');//selese

//mKota
Route::resource('mKota', 'App\Http\Controllers\MKotaController');//selese

//mPerusahaan
Route::resource('mPerusahaan', 'App\Http\Controllers\mPerusahaanController');//selese

//mKota
Route::resource('msupplier', 'App\Http\Controllers\MSupplierController');//selese

//mGudang
Route::resource('mGudang', 'App\Http\Controllers\MGudangController');//dikerjakan setelah item dan item values selesai
Route::resource('mGudangAreaSimpan', 'App\Http\Controllers\MGudangAreaSimpanController');//dikerjakan setelah item dan item values selesai
Route::resource('tagValuesMGudang', 'App\Http\Controllers\MGudangValuesController');//dikerjakan setelah item dan item values selesai

//mGudang
Route::resource('mCurrency', 'App\Http\Controllers\MCurrencyController');
Route::resource('prosesTransaksi', 'App\Http\Controllers\ProsesTransaksiController');

//mSupplier
Route::resource('supplier', 'App\Http\Controllers\MSupplierController');
//tax
Route::resource('tax', 'App\Http\Controllers\TaxController');//selese

//Purchase Request 
Route::resource('purchaseRequest', 'App\Http\Controllers\PurchaseRequestController');
//Route::get('purchaseRequest/pdf/{purchaseRequest}',[App\Http\Controllers\PurchaseRequestController::class, 'pdf']);
//approve purchase request
Route::resource('approvedPurchaseRequest', 'App\Http\Controllers\ApprovedPRController');
//Purchase Order 
Route::resource('purchaseOrder', 'App\Http\Controllers\PurchaseOrderController');