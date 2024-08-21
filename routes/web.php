<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvoiceArcheiveController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\InvoicesDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\UserController;
use App\Models\Invoices_Details;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/{page}', [AdminController::class,'index']);


// invoice route
Route::get('/section/{id}',[InvoiceController::class,'getProducts']);

Route::controller(InvoiceController::class)->prefix('invoices')->group(function(){
    Route::get('/index', 'index')->name('invoices.index');
    Route::get('/showAllInvoicesPaid', 'showAllInvoicesPaid')->name('invoices.showAllInvoicesPaid');
    Route::get('/showAllInvoicesPartialyPaid', 'showAllInvoicesPartialyPaid')->name('invoices.showAllInvoicesPartialyPaid');
    Route::get('/showAllInvoicesUnPaid', 'showAllInvoicesUnPaid')->name('invoices.showAllInvoicesUnPaid');
    Route::get('/archeive', 'allInvoicesArcheive');
    Route::get('/create', 'create')->name('invoices.create');
    Route::get('/edit/{id}', 'edit')->name('invoices.edit');
    Route::post('/store','store')->name('invoices.store');
    Route::patch('/update/{id}','update')->name('invoices.update');
    Route::delete('/destroy/{id}','destroy')->name('invoices.destroy');
    Route::delete('/archeive/{id}','archeiveInvoice')->name('invoices.archeive');
    Route::get('/retreive/{id}','retreiveInvoice')->name('invoices.retreive');
    Route::get('/showstatus/{id}', 'showstatus')->name('invoices.showstatus');
    Route::post('/updatestatus/{id}','updatestatus')->name('invoices.updatestatus');
    Route::get('/print/{id}', 'printInvoice')->name('invoices.print');
    Route::post('/import','importExcel')->name('invoices.import');
    Route::get('/export','exportExcel')->name('invoices.export');
});

Route::controller(InvoiceArcheiveController::class)->prefix('invoices')->group(function(){
    Route::get('/archeive', 'allInvoicesArcheive');
    Route::get('/retreive/{id}','retreiveInvoice')->name('invoices.retreive');
});

// sections route
Route::controller(SectionController::class)->prefix('sections')->group(function(){
    Route::get('/index', 'index');
    Route::post('/store','store')->name('sections.store');
    Route::put('/update','update')->name('sections.update');
    Route::delete('/destroy','destroy')->name('sections.destroy');
});

// products route
Route::controller(ProductController::class)->prefix('products')->group(function(){
    Route::get('/index', 'index');
    Route::post('/store','store')->name('products.store');
    Route::put('/update','update')->name('products.update');
    Route::delete('/destroy','destroy')->name('products.destroy');
});

// invoices details route
Route::controller(InvoicesDetailsController::class)->group(function(){
    Route::get('/invoices/details/{id}', 'edit')->name('invoices.details');
});

Route::controller(InvoiceAttachmentsController::class)->group(function(){
    Route::get('download/{invoice_number}/{file_name}', 'download')->name('invoices.download');
    Route::get('Attachments/{invoice_number}/{file_name}', 'openFile')->name('invoices.openFile');
    Route::delete('InvoiceAttachment/destroy/{id}','destroy')->name('InvoiceAttachment.destroy');
    Route::post('InvoiceAttachment/store','store')->name('InvoiceAttachment.store');
});

// users route
Route::controller(UserController::class)->prefix('users')->group(function(){
    Route::get('/index', 'index')->name('users.index');
    Route::get('/create', 'create')->name('users.create');
    Route::post('/store','store')->name('users.store');
    Route::get('/edit/{id}', 'edit')->name('users.edit');
    Route::post('/update/{id}','update')->name('users.update');
    Route::delete('/destroy/{id}','destroy')->name('users.destroy');
    Route::get('/notifications', 'notifications')->name('users.notifications');
});

Route::controller(RolesController::class)->prefix('roles')->group(function(){
    Route::get('/index', 'index')->name('roles.index');
    Route::get('/create', 'create')->name('roles.create');
    Route::get('/show/{id}', 'show')->name('roles.show');
    Route::post('/store','store')->name('roles.store');
    Route::get('/edit/{id}', 'edit')->name('roles.edit');
    Route::post('/update/{id}','update')->name('roles.update');
    Route::delete('/destroy/{id}','destroy')->name('roles.destroy');
});

Route::controller(ReportsController::class)->prefix('reports')->group(function(){
    Route::get('/invoices', 'getAllInvoices')->name('reports.invoices');
    Route::get('/customers', 'getAllCustomers')->name('reports.customers');
    Route::post('/search_invoices','search_invoices')->name('reports.search_invoices');
    Route::post('/search_customers','search_customers')->name('reports.search_customers');
});



