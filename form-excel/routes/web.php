<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PelamarController;

Route::get('/', [PelamarController::class, 'index']);
Route::post('/generate', [PelamarController::class, 'generate'])->name('generate');
