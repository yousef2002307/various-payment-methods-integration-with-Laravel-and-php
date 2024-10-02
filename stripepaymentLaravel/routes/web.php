<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('stripe', [App\Http\Controllers\StripeController::class, 'stripe'])->name('stripe');
Route::get('success', [App\Http\Controllers\StripeController::class, 'success'])->name('success');
Route::get('cancel', [App\Http\Controllers\StripeController::class, 'cancel'])->name('cancel');