<?php

use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\CommitteeController;
use App\Http\Controllers\Api\DamageReportController;
use App\Http\Controllers\Api\EngineerController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\Form1Controller;
use App\Http\Controllers\Api\V1\Form2Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/users/create', [AuthController::class, 'createUser'])
        ->can('create', User::class);

    Route::get('/users', [UserController::class, 'index'])
        ->can('viewAny', User::class); // <-- هذا هو الإصلاح الصحيح

    Route::get('/users/{user}', [UserController::class, 'show'])->can('view', 'user');

    Route::put('/users/{user}', [UserController::class, 'update'])->can('update', 'user');

    Route::delete('/users/{user}', [UserController::class, 'destroy'])->can('delete', 'user');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/v1/dashboard', [DashboardController::class, 'index']);

    Route::post('/logout', [AuthController::class, 'logout']);

});
Route::get('/buildings', [BuildingController::class, 'index']);
//Processes Form1
Route::get('form1/buildings', [Form1Controller::class, 'index']);
Route::get('/buildings/form1/edit/{id}', [Form1Controller::class, 'editData']);
Route::put('/buildings/update/{id}', [Form1Controller::class, 'update']);
Route::post('/buildings/{building}', [Form1Controller::class, 'store']);
Route::delete('/buildings/delete/{id}', [Form1Controller::class, 'updateAndCleanReports']);
//Processes Form2
Route::get('form2/buildings', [Form2Controller::class, 'index']);
Route::get('/buildings/form2/edit/{id}', [Form1Controller::class, 'editData']);
Route::get('/engineers-by-specialization', [EngineerController::class, 'bySpecialization']);
Route::get('/committees', [CommitteeController::class, 'index']);
Route::get('/buildings/stats/type-level1', [\App\Http\Controllers\Api\BuildingController::class, 'statsByTypeLevel1']);
