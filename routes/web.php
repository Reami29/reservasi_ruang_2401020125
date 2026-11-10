<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\ReservasiRuangController;

Route::resource('reservasi', ReservasiRuangController::class);

Route::get('/', function () {
    return redirect('/reservasi');
});