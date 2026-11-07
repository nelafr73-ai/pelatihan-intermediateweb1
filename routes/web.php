<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchMapController;

Route::get('/peta', [BranchMapController::class, 'index']);

Route::get('/profil', function () {
    return view('welcome');
});

Route::get('/profil/{nela}', function ($nela) {
    return 'Halo, ' . $nela;
});