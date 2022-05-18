<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemTagValuesController;
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
Route::get('/banke/searchname/',[App\Http\Controllers\BankController::class, 'searchName']); //cobak gini ta





/*==================================yang belom==========================================*/ 
//Menu (ex:master, home, pemesanan, profil, dll) 
Route::resource('menu', 'App\Http\Controllers\MenuController');
Route::get('/menue/searchname/',[App\Http\Controllers\MenuController::class, 'searchMenuName']);
//Role(ex:Pegawai, ketua divisi)
Route::resource('role', 'App\Http\Controllers\RoleController');
Route::get('/rolee/searchname/',[App\Http\Controllers\RoleController::class, 'searchRoleName']); //cobak gini ta

Route::resource('roleAccess', 'App\Http\Controllers\RoleAccessController');
Route::get('/roleAccesse/searchname/',[App\Http\Controllers\RoleAccessController::class, 'searchRoleName']); 

//SubMenu (ex:barang_tambah, barang_edit, npp_tambah, po_tambah, dll   nyimpen id menu) 
//Route::resource('submenu', 'App\Http\Controllers\SubMenuController');

//UserAccess (mnm dari 2 diatas) (ex:nama e tambah apa , access nya itu boolean (1 dan 0) nyimpen id submenu, dll) 
//Route::resource('userAccess', 'App\Http\Controllers\UserAccessController');


//fixed ***********************************************************


//COA baru
Route::resource('coa', 'App\Http\Controllers\COAController');
Route::get('/coae/searchname/',[App\Http\Controllers\COAController::class, 'searchCoaName']);
Route::resource('coaDetail', 'App\Http\Controllers\COADetailController');
Route::get('/coaDetaile/searchname/',[App\Http\Controllers\COADetailController::class, 'searchCoaDetailName']); 
Route::resource('coaHead', 'App\Http\Controllers\COAHeadController');
Route::get('/coaHeade/searchname/',[App\Http\Controllers\COAHeadController::class, 'searchCoaHeadName']); 




//payment and paymentterms
Route::resource('payment', 'App\Http\Controllers\PaymentController');
Route::get('/paymente/searchname/',[App\Http\Controllers\PaymentController::class, 'searchPaymentName']); 
Route::resource('paymentTerms', 'App\Http\Controllers\PaymentTermsController');
Route::get('/paymentTermse/searchname/',[App\Http\Controllers\PaymentTermsController::class, 'searchPaymentTermsName']); 

//item
Route::resource('item', 'App\Http\Controllers\ItemController');//
Route::get('/iteme/searchname/',[App\Http\Controllers\ItemController::class, 'searchItemName']); //cobak gini ta
Route::get('/iteme/searchtag/',[App\Http\Controllers\ItemController::class, 'searchItemTagName']); //cobak gini ta
Route::get('/iteme/searchtagmulti/',[App\Http\Controllers\ItemController::class, 'searchItemTagMulti']); //cobak gini ta
//Route::get('/item/searchname/', [App\Http\Controllers\ItemController::class, 'searchItemName'])->name('searchItemName');


Route::resource('itemCategory', 'App\Http\Controllers\ItemCategoryController');
Route::get('/itemCategorye/searchname/',[App\Http\Controllers\ItemCategoryController::class, 'selectItemCategoryName']); //udah

Route::resource('itemTag', 'App\Http\Controllers\ItemTagController');
Route::get('/itemTage/searchname/',[App\Http\Controllers\ItemTagController::class, 'searchItemTagName']);//udah

Route::resource('itemTracing', 'App\Http\Controllers\ItemTracingController');
Route::get('/itemTracinge/searchname/',[App\Http\Controllers\ItemTracingController::class, 'searchItemTracingName']); //udah
Route::get('/itemTracinge/searchket/',[App\Http\Controllers\ItemTracingController::class, 'searchItemTracingKeterangan']); //udah

Route::resource('itemTransaction', 'App\Http\Controllers\ItemTransactionController');
Route::get('/itemTransactione/searchname/',[App\Http\Controllers\ItemTransactionController::class, 'searchItemTransactionName']); //udah

Route::resource('itemType', 'App\Http\Controllers\ItemTypeController');
Route::get('/itemTypee/searchname/',[App\Http\Controllers\ItemTypeController::class, 'searhItemTypeName']); //udah

Route::resource('itemTagValues', 'App\Http\Controllers\ItemTagValuesController');
Route::get('/itemTagValuese/searchname/',[App\Http\Controllers\ItemTagValuesController::class, 'searchItemName']); //udah

Route::resource('unit', 'App\Http\Controllers\UnitController');
Route::get('/unite/searchname/',[App\Http\Controllers\UnitController::class, 'searchUnitName']); //udah
Route::get('/unite/searchdesc/',[App\Http\Controllers\UnitController::class, 'searchUnitDeskripsi']); //udah



//mPulau
Route::resource('mPulau', 'App\Http\Controllers\MPulauController');//selese
Route::get('/mPulaue/searchname/',[App\Http\Controllers\MPulauController::class, 'searchPulauName']); //udah
//mProvinsi
Route::resource('mProvinsi', 'App\Http\Controllers\MProvinsiController');//selese
Route::get('/mProvinsie/searchname/',[App\Http\Controllers\MProvinsiController::class, 'searchProvinsiName']); //udah
//mKota
Route::resource('mKota', 'App\Http\Controllers\MKotaController');//selese
Route::get('/mKotae/searchname/',[App\Http\Controllers\MKotaController::class, 'searchKotaName']); //udah
//mPerusahaan
Route::resource('mPerusahaan', 'App\Http\Controllers\mPerusahaanController');//selese
Route::get('/mPerusahaane/searchname/',[App\Http\Controllers\mPerusahaanController::class, 'searchPerusahaanName']); //udah
//mGudang
Route::resource('mGudang', 'App\Http\Controllers\MGudangController');
Route::get('/mGudange/searchname/',[App\Http\Controllers\MGudangController::class, 'searchGudangName']); //udah
Route::get('/mGudange/searchcode/',[App\Http\Controllers\MGudangController::class, 'searchGudangCode']); //udah

Route::resource('mGudangAreaSimpan', 'App\Http\Controllers\MGudangAreaSimpanController');
Route::get('/mGudangAreaSimpane/searchname/',[App\Http\Controllers\MGudangAreaSimpanController::class, 'searchGudangAreaSimpanName']); //udah

Route::resource('tagValuesMGudang', 'App\Http\Controllers\MGudangValuesController');
Route::get('/tagValuesMGudange/searchname/',[App\Http\Controllers\MGudangValuesController::class, 'searchGudangName']); //udah


//mKota
Route::resource('msupplier', 'App\Http\Controllers\MSupplierController');//selese
Route::get('/msuppliere/searchname/',[App\Http\Controllers\MSupplierController::class, 'searchSupplierName']);
Route::get('/msuppliere/searchalamat/',[App\Http\Controllers\MSupplierController::class, 'searchSupplierName']);
Route::get('/msuppliere/searchketerangan/',[App\Http\Controllers\MSupplierController::class, 'searchSupplierName']);
Route::resource('infoSupplier', 'App\Http\Controllers\InfoSupplierController');//selese
Route::get('/infoSuppliere/searchname/',[App\Http\Controllers\InfoSupplierController::class, 'searchName']);//cobak gini ta
//mGudang
Route::resource('mCurrency', 'App\Http\Controllers\MCurrencyController');
Route::get('/mCurrencye/searchname/',[App\Http\Controllers\MCurrencyController::class, 'searhMCurrencyName']);//cobak gini ta

Route::resource('prosesTransaksi', 'App\Http\Controllers\ProsesTransaksiController');

//mSupplier
//Route::resource('supplier', 'App\Http\Controllers\MSupplierController');
//tax
Route::resource('tax', 'App\Http\Controllers\TaxController');//selese
Route::get('taxe/searchname/',[App\Http\Controllers\TaxController::class, 'searchTaxName']);

//Purchase Request 
Route::resource('purchaseRequest', 'App\Http\Controllers\PurchaseRequestController');
Route::get('PurchaseRequeste/print/{purchaseRequest}',[App\Http\Controllers\PurchaseRequestController::class, 'print']);
Route::get('purchaseRequeste/searchname/',[App\Http\Controllers\PurchaseRequestController::class, 'searchNamePR']);
Route::get('purchaseRequeste/searchdate/',[App\Http\Controllers\PurchaseRequestController::class, 'searchDatePR']);
//approve purchase request
Route::resource('approvedPurchaseRequest', 'App\Http\Controllers\ApprovedPRController');
Route::get('approvedPurchaseRequeste/searchname/',[App\Http\Controllers\ApprovedPRController::class, 'searchNamePR']);
Route::get('approvedPurchaseRequeste/searchdate/',[App\Http\Controllers\ApprovedPRController::class, 'searchDatePR']);
//Route::get('/approvedPurchaseRequeste/print/{$purchaseRequest}',[App\Http\Controllers\ApprovedPRController::class, 'print']); 


Route::resource('approvedPurchaseOrder', 'App\Http\Controllers\ApprovedPOController');
Route::get('approvedPurchaseOrdere/searchname/',[App\Http\Controllers\ApprovedPOController::class, 'searchNamePO']);
Route::get('approvedPurchaseOrdere/searchdate/',[App\Http\Controllers\ApprovedPOController::class, 'searchDatePO']);
//Purchase Order 
Route::resource('purchaseOrder', 'App\Http\Controllers\PurchaseOrderController');
Route::get('PurchaseOrdere/print/{purchaseOrder}',[App\Http \Controllers\PurchaseOrderController::class, 'print']);
Route::get('purchaseOrdere/searchname/',[App\Http\Controllers\PurchaseOrderController::class, 'searchNamePO']);
Route::get('purchaseOrdere/searchdate/',[App\Http\Controllers\PurchaseOrderController::class, 'searchDatePO']);

//transactionGudangBarang
Route::resource('transactionGudang','App\Http\Controllers\TransactionGudangBarangController');
Route::resource('kirimBarangPesanan','App\Http\Controllers\KirimBarangPesananController');
Route::get('kirimBarangPesanane/searchname/',[App\Http\Controllers\KirimBarangPesananController::class, 'searchTGBName']);
Route::get('kirimBarangPesanane/searchdate/',[App\Http\Controllers\KirimBarangPesananController::class, 'searchTGBDate']);
Route::get('kirimBarangPesanane/print/{kirimBarangPesanan}',[App\Http\Controllers\KirimBarangPesananController::class, 'print']);

Route::resource('terimaBarangPesanan','App\Http\Controllers\TerimaBarangPesananController');
Route::get('terimaBarangPesanane/searchname/',[App\Http\Controllers\TerimaBarangPesananController::class, 'searchTGBName']);
Route::get('terimaBarangPesanane/searchdate/',[App\Http\Controllers\TerimaBarangPesananController::class, 'searchTGBDate']);
Route::get('terimaBarangPesanane/print/{terimaBarangPesanan}',[App\Http\Controllers\TerimaBarangPesananController::class, 'print']);


Route::resource('terimaBarangSupplier','App\Http\Controllers\TerimaBarangSupplierController');
Route::get('terimaBarangSuppliere/searchname/',[App\Http\Controllers\TerimaBarangSupplierController::class, 'searchTGBName']);
Route::get('terimaBarangSuppliere/searchdate/',[App\Http\Controllers\TerimaBarangSupplierController::class, 'searchTGBDate']);
Route::get('terimaBarangSuppliere/print/{terimaBarangSupplier}',[App\Http\Controllers\TerimaBarangSupplierController::class, 'print']);

Route::resource('suratJalan','App\Http\Controllers\SuratJalanController');
Route::get('suratJalane/searchname/',[App\Http\Controllers\SuratJalanController::class, 'searchSuratJalanName']);
Route::get('suratJalane/searchdate/',[App\Http\Controllers\SuratJalanController::class, 'searchSuratJalanDate']);
Route::get('suratJalane/print/{suratJalan}',[App\Http\Controllers\SuratJalanController::class, 'print']);

Route::resource('inventoryTransaction','App\Http\Controllers\InventoryTransactionController');
Route::get('/kartuStok/searchgudang/',[App\Http\Controllers\InventoryTransactionController::class, 'searchIndexByGudang']);
Route::get('/kartuStok/searchLengkap/',[App\Http\Controllers\InventoryTransactionController::class, 'reportLengkap']);
Route::get('/kartuStoke/report/{idGudang}/{idItem}',[App\Http\Controllers\InventoryTransactionController::class, 'report']);
Route::get('/kartuStoke/print/{idGudang}/{idItem}',[App\Http\Controllers\InventoryTransactionController::class, 'print']);


Route::resource('stokAwal','App\Http\Controllers\StokAwalController');
Route::get('/stokAwale/searchname/',[App\Http\Controllers\StokAwalController::class, 'searchStokAwalName']);
Route::get('/stokAwale/searchdate/',[App\Http\Controllers\StokAwalController::class, 'searchStokAwalNameDate']);
Route::get('stokAwale/print/{stokAwal}',[App\Http\Controllers\StokAwalController::class, 'print']);

Route::resource('adjustmentStock','App\Http\Controllers\AdjustmentStockController');  ///Item Adjustment Stock
Route::get('/adjustmentStocke/searchname/',[App\Http\Controllers\AdjustmentStockController::class, 'searchNameAS']);
Route::get('/adjustmentStocke/searchdate/',[App\Http\Controllers\AdjustmentStockController::class, 'searchDateAS']);
Route::get('/adjustmentStocke/searchnamedate/',[App\Http\Controllers\AdjustmentStockController::class, 'searchNameDateAS']);
Route::get('adjustmentStocke/print/{adjustmentStock}',[App\Http\Controllers\AdjustmentStockController::class, 'print']);

Route::resource('tagValuesMGudang', 'App\Http\Controllers\MGudangValuesController');