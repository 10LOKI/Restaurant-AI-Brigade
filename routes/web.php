<?php

use Illuminate\Support\Facades\Route;
use App\Models\Plat;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/plats', function () {
    $plats = Plat::all();
    return view('plats.index', ['plats' => $plats]);
});
