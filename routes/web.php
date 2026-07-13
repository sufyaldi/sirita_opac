<?php

use App\Http\Controllers\Admin\BibliographyController;
use App\Http\Controllers\Admin\CirculationController;
use App\Http\Controllers\Admin\AccreditationController;
use App\Http\Controllers\OpacController;
use App\Http\Controllers\OpacMemberController;
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

use App\Http\Controllers\Admin\LoginController;

use App\Http\Controllers\Admin\MembershipController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MasterFileController;

// OPAC Routes
Route::get('/', [OpacController::class, 'index'])->name('opac.index');
Route::get('/book/{id}', [OpacController::class, 'show'])->name('opac.show');
Route::get('/page/{slug}', [OpacController::class, 'showPage'])->name('opac.page');

// OPAC Member Self-Service Routes
Route::get('/member', [OpacMemberController::class, 'showMemberArea'])->name('opac.member.area');
Route::post('/member/login', [OpacMemberController::class, 'login'])->name('opac.member.login');
Route::get('/member/dashboard', [OpacMemberController::class, 'dashboard'])->name('opac.member.dashboard');
Route::post('/member/logout', [OpacMemberController::class, 'logout'])->name('opac.member.logout');

// Admin Auth Routes
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// Admin Module Routes (Requires authentication guard)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.circulation.index');
    });
    
    // Bibliography Module (CRUD)
    Route::resource('bibliography', BibliographyController::class);
    Route::resource('items', \App\Http\Controllers\Admin\ItemController::class)->names('items');

    // Loan Rules Module (CRUD)
    Route::resource('circulation/loan-rules', \App\Http\Controllers\Admin\LoanRulesController::class)->names([
        'index' => 'circulation.loan-rules.index',
        'create' => 'circulation.loan-rules.create',
        'store' => 'circulation.loan-rules.store',
        'edit' => 'circulation.loan-rules.edit',
        'update' => 'circulation.loan-rules.update',
        'destroy' => 'circulation.loan-rules.destroy',
    ]);

    // Circulation Module
    Route::get('circulation', [CirculationController::class, 'index'])->name('circulation.index');
    Route::post('circulation/start', [CirculationController::class, 'startTransaction'])->name('circulation.start');
    Route::get('circulation/transaction/{member_id}', [CirculationController::class, 'startTransaction'])->name('circulation.transaction');
    Route::post('circulation/checkout/{member_id}', [CirculationController::class, 'checkout'])->name('circulation.checkout');
    Route::post('circulation/checkin/{loan_id}', [CirculationController::class, 'checkin'])->name('circulation.checkin');
    Route::get('circulation/receipt/{member_id}', [CirculationController::class, 'printReceipt'])->name('circulation.receipt');
    
    // Quick Return routes
    Route::get('circulation/quick-return', [CirculationController::class, 'showQuickReturn'])->name('circulation.quick-return');
    Route::post('circulation/quick-return', [CirculationController::class, 'doQuickReturn'])->name('circulation.quick-return.post');
    Route::delete('circulation/quick-return/clear', [CirculationController::class, 'clearQuickReturnLog'])->name('circulation.quick-return.clear');

    // Fines Management routes
    Route::get('circulation/fines', [CirculationController::class, 'listFines'])->name('circulation.fines');
    Route::post('circulation/fines/pay/{fines_id}', [CirculationController::class, 'payFine'])->name('circulation.fines.pay');
    Route::post('circulation/fines/store', [CirculationController::class, 'storeFine'])->name('circulation.fines.store');
    Route::delete('circulation/fines/{fines_id}', [CirculationController::class, 'destroyFine'])->name('circulation.fines.destroy');

    // Membership Module (CRUD)
    Route::resource('membership', MembershipController::class);
    Route::post('membership/extend', [MembershipController::class, 'extend'])->name('membership.extend');


    // Settings Module
    Route::get('setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('setting', [SettingController::class, 'update'])->name('setting.update');

    // Master File Module (Dynamic Route)
    Route::get('master-file/{type}', [MasterFileController::class, 'index'])->name('master-file.index');
    Route::get('master-file/{type}/create', [MasterFileController::class, 'create'])->name('master-file.create');
    Route::post('master-file/{type}', [MasterFileController::class, 'store'])->name('master-file.store');
    Route::get('master-file/{type}/{id}/edit', [MasterFileController::class, 'edit'])->name('master-file.edit');
    Route::put('master-file/{type}/{id}', [MasterFileController::class, 'update'])->name('master-file.update');
    Route::delete('master-file/{type}/{id}', [MasterFileController::class, 'destroy'])->name('master-file.destroy');

    // Copy Cataloging Module (SRU)
    Route::get('copy-cataloging', [\App\Http\Controllers\Admin\CopyCatalogingController::class, 'index'])->name('copy-cataloging.index');
    Route::post('copy-cataloging/import', [\App\Http\Controllers\Admin\CopyCatalogingController::class, 'import'])->name('copy-cataloging.import');

    // Barcode & Label Print Module
    Route::get('barcode', [\App\Http\Controllers\Admin\BarcodeController::class, 'index'])->name('barcode.index');
    Route::post('barcode/print', [\App\Http\Controllers\Admin\BarcodeController::class, 'printBarcodes'])->name('barcode.print');
    Route::post('barcode/label', [\App\Http\Controllers\Admin\BarcodeController::class, 'printLabels'])->name('barcode.label');

    // MARC Import & Export Module
    Route::get('marc/import', [\App\Http\Controllers\Admin\MarcController::class, 'showImport'])->name('marc.import');
    Route::post('marc/import', [\App\Http\Controllers\Admin\MarcController::class, 'doImport'])->name('marc.import.post');
    Route::get('marc/export', [\App\Http\Controllers\Admin\MarcController::class, 'showExport'])->name('marc.export');
    Route::post('marc/export', [\App\Http\Controllers\Admin\MarcController::class, 'doExport'])->name('marc.export.post');

    // Reporting Module
    Route::get('reporting', [\App\Http\Controllers\Admin\ReportingController::class, 'index'])->name('reporting.index');

    // Serial Control Module
    Route::get('serial-control', [\App\Http\Controllers\Admin\SerialControlController::class, 'index'])->name('serial-control.index');
    Route::post('serial-control', [\App\Http\Controllers\Admin\SerialControlController::class, 'store'])->name('serial-control.store');
    Route::delete('serial-control/{subscription_id}', [\App\Http\Controllers\Admin\SerialControlController::class, 'destroy'])->name('serial-control.destroy');
    Route::get('serial-control/{subscription_id}/kardex', [\App\Http\Controllers\Admin\SerialControlController::class, 'showKardex'])->name('serial-control.kardex');
    Route::post('serial-control/{subscription_id}/kardex', [\App\Http\Controllers\Admin\SerialControlController::class, 'storeKardex'])->name('serial-control.kardex.store');
    Route::post('serial-control/kardex/{kardex_id}/receive', [\App\Http\Controllers\Admin\SerialControlController::class, 'receiveKardex'])->name('serial-control.kardex.receive');
    Route::post('serial-control/kardex/{kardex_id}/missing', [\App\Http\Controllers\Admin\SerialControlController::class, 'missKardex'])->name('serial-control.kardex.missing');
    Route::delete('serial-control/kardex/{kardex_id}', [\App\Http\Controllers\Admin\SerialControlController::class, 'destroyKardex'])->name('serial-control.kardex.destroy');

    // Stock Take Module
    Route::get('stock-take', [\App\Http\Controllers\Admin\StockTakeController::class, 'index'])->name('stock-take.index');
    Route::post('stock-take', [\App\Http\Controllers\Admin\StockTakeController::class, 'startSession'])->name('stock-take.start');
    Route::get('stock-take/current', [\App\Http\Controllers\Admin\StockTakeController::class, 'showCurrent'])->name('stock-take.current');
    Route::post('stock-take/current', [\App\Http\Controllers\Admin\StockTakeController::class, 'scanItem'])->name('stock-take.scan');
    Route::get('stock-take/report', [\App\Http\Controllers\Admin\StockTakeController::class, 'showReport'])->name('stock-take.report');
    Route::post('stock-take/finish', [\App\Http\Controllers\Admin\StockTakeController::class, 'finishSession'])->name('stock-take.finish');

    // Holidays Management Module
    Route::get('system/holidays', [\App\Http\Controllers\Admin\HolidayController::class, 'index'])->name('system.holidays.index');
    Route::post('system/holidays', [\App\Http\Controllers\Admin\HolidayController::class, 'store'])->name('system.holidays.store');
    Route::delete('system/holidays/{holiday_id}', [\App\Http\Controllers\Admin\HolidayController::class, 'destroy'])->name('system.holidays.destroy');

    // Plugins Management Module
    Route::get('system/plugins', [\App\Http\Controllers\Admin\PluginController::class, 'index'])->name('system.plugins.index');
    Route::post('system/plugins/toggle', [\App\Http\Controllers\Admin\PluginController::class, 'toggle'])->name('system.plugins.toggle');

    // SLiMS Migrator Routes
    Route::get('system/plugins/slims-migrator', [\App\Plugins\SlimsMigrator\SlimsMigratorController::class, 'index'])->name('plugins.slims-migrator.index');
    Route::post('system/plugins/slims-migrator/test-connection', [\App\Plugins\SlimsMigrator\SlimsMigratorController::class, 'testConnection'])->name('plugins.slims-migrator.test-conn');
    Route::post('system/plugins/slims-migrator/run-step', [\App\Plugins\SlimsMigrator\SlimsMigratorController::class, 'runMigrationStep'])->name('plugins.slims-migrator.run-step');

    // System Users (Librarians) Module
    Route::get('system/users', [\App\Http\Controllers\Admin\SystemUserController::class, 'index'])->name('system.users.index');
    Route::post('system/users', [\App\Http\Controllers\Admin\SystemUserController::class, 'store'])->name('system.users.store');
    Route::put('system/users/{user_id}', [\App\Http\Controllers\Admin\SystemUserController::class, 'update'])->name('system.users.update');
    Route::delete('system/users/{user_id}', [\App\Http\Controllers\Admin\SystemUserController::class, 'destroy'])->name('system.users.destroy');
    Route::post('system/users/groups', [\App\Http\Controllers\Admin\SystemUserController::class, 'storeGroup'])->name('system.users.groups.store');
    Route::delete('system/users/groups/{group_id}', [\App\Http\Controllers\Admin\SystemUserController::class, 'destroyGroup'])->name('system.users.groups.destroy');

    // Akreditasi — Laporan Koleksi per Unit Perpustakaan
    Route::get('accreditation', [AccreditationController::class, 'index'])->name('accreditation.index');
    Route::get('accreditation/unit/{unitId}', [AccreditationController::class, 'unitReport'])->name('accreditation.report');
    Route::post('accreditation/tag/{biblioId}', [AccreditationController::class, 'tagBiblio'])->name('accreditation.tag');
    Route::delete('accreditation/untag/{biblioId}/{unitId}', [AccreditationController::class, 'untagBiblio'])->name('accreditation.untag');

    // Keloba Unit Organisasi / Prodi
    Route::resource('org-units', \App\Http\Controllers\Admin\OrgUnitController::class)->except(['create', 'show', 'edit']);

    // Kelola Menu OPAC Dinamis
    Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class)->except(['create', 'show', 'edit']);

    // Kelola Halaman Dinamis CMS OPAC
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);
});
