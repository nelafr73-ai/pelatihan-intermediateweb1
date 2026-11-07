<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchMapController;


// routes/web.php
Route::get('/peta', [BranchMapController::class, 'peta']);
Route::put('/cabang/{branch}', [BranchMapController::class, 'update']);

Route::get('/profil', function () {
    return view('welcome');
});

Route::get('/profil/{nela}', function ($nela) {
    return 'Halo, ' . $nela;
});