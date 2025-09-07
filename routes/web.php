<?php

use App\Http\Controllers\Api\DamageReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/buildings/{building}', [DamageReportController::class, 'store']);

