<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Client\AuthController as ClientAuth;
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\LoanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function() {
        Route::post('signup', [ClientAuth::class,'signup'])->name('auth.signup');
        Route::post('login', [ClientAuth::class,'login'])->name('auth.login');
});

Route::group(['middleware' => ['auth:api']], function () {

    Route::group(['prefix' => 'auth'], function() {
                Route::post('logout', [ClientAuth::class,'logout'])->name('auth.logout');
    });

    Route::group(['prefix' => 'loan'], function() {
        Route::post('create', [LoanController::class, 'create'])->name('loan.create');
        Route::put('repay', [LoanController::class,'repay'])->name('loan.repay');
        Route::get('list', [LoanController::class,'detail'])->name('loans.detail');
    });
});

Route::group(['prefix' => 'auth/admin'], function() {
    Route::post('login', [AdminAuth::class,'login'])->name('admin.auth.login');
    Route::post('logout', [AdminAuth::class,'logout'])->name('admin.auth.logout');
});

Route::group(['middleware' => ['auth:admin', 'role:admin'], 'prefix' => 'admin/loan', 'as' => 'admin.'], function () {
    Route::put('update', [LoanController::class,'update'])->name('loans.update');
});