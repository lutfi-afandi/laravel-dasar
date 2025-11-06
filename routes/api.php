<?php

use App\Http\Controllers\Api\GuruController;
use Illuminate\Support\Facades\Route;

Route::get('/guru/paginate', [GuruController::class, 'getPagination']);
Route::apiResource('/guru', GuruController::class);
