<?php

use Illuminate\Support\Facades\Route;

// rutas al home para usar Vue
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
