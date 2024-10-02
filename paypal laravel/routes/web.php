<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post("/paypal",[App\Http\Controllers\PayPalController::class,'paypal'])->name('paypal');
Route::get('success', [App\Http\Controllers\PaypalController::class, 'success'])->name('success');
Route::get('cancel', [App\Http\Controllers\PaypalController::class, 'cancel'])->name('cancel');