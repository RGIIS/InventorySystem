<?php

use Illuminate\Support\Facades\Route;
use App\Models\Log;
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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/checkLogin',[App\Http\Controllers\UserController::class, 'checkUser']);

// PAGES
Route::get('/dashboard',[App\Http\Controllers\UserController::class, 'dashboard'])->name('dashboard');
Route::get('/inventory',[App\Http\Controllers\UserController::class, 'inventory'])->name('inventory');
Route::get('/sales/monthly',[App\Http\Controllers\SalesController::class, 'showMonthlySales'])->name('monthly_sales');
Route::get('/sales/daily',[App\Http\Controllers\SalesController::class, 'showDailySales'])->name('daily_sales');
Route::get('/sales/detailed',[App\Http\Controllers\SalesController::class, 'showDetailedSales'])->name('detailed_sales');
Route::get('/accounts',[App\Http\Controllers\UserController::class, 'accounts'])->name('accounts');
Route::get('/logs',[App\Http\Controllers\UserController::class, 'logs'])->name('logs');

//FUNCTIONS
Route::post('/additem',[App\Http\Controllers\UserController::class, 'additem']);
Route::post('/updateitem',[App\Http\Controllers\UserController::class, 'updateitem']);
Route::post('/removeitem',[App\Http\Controllers\UserController::class, 'removeitem']);
Route::get('/logout',function(){
    
    $log = new Log;
    $log->action = session('User')->name.'.loggedOUT';
    $log->save();
    \Session::forget('User'); return redirect('/');
});
Route::post('/loadDailySales',[App\Http\Controllers\SalesController::class, 'loadDailySales']);
Route::post('/loadMonthlySales',[App\Http\Controllers\SalesController::class, 'loadMonthlySales']);
Route::post('/sales/remove',[App\Http\Controllers\SalesController::class, 'removeItem']);
Route::post('/sales/clear',[App\Http\Controllers\SalesController::class, 'clearSales']);
Route::post('/addAdmin',[App\Http\Controllers\UserController::class, 'addAdmin']);
Route::post('/addCashier',[App\Http\Controllers\UserController::class, 'addCashier']);
Route::post('/updateCashier',[App\Http\Controllers\UserController::class, 'updateCashier']);
Route::get('/accounts/remove/cashier',[App\Http\Controllers\UserController::class, 'removeCashier']);
Route::post('/updateAdmin', [App\Http\Controllers\UserController::class, 'updateAdmin']);
Route::post('/removeAdmin', [App\Http\Controllers\UserController::class, 'removeAdmin']);



// CASHIER Pages
Route::get('/cashier/login',[App\Http\Controllers\CashierController::class, 'login'])->name('cashierLogin');
Route::get('/pos',[App\Http\Controllers\CashierController::class, 'pos'])->name('pos');
Route::post('/recordtransaction',[App\Http\Controllers\CashierController::class, 'recordTransaction']);
Route::get('/pos/search',[App\Http\Controllers\CashierController::class, 'search']);


// CASHIER FUNCTIONS
Route::post('/checkCashier',[App\Http\Controllers\CashierController::class, 'checkCashier']);
Route::get('/cashier/logout',function(){
    $log = new Log;
    $log->action = 'cashierAccount.'.session('cashier')->name.'.loggedOUT';
    $log->save();
session()->forget('cashier');
return redirect('/cashier/login');
});

