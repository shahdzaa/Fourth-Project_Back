<?php

use App\Http\Controllers\Api\BuildingController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// =========================================================================
// == المسارات العامة (Public Routes) ==
// هذه المسارات لا تتطلب أي مصادقة ويمكن لأي شخص الوصول إليها.
// =========================================================================

// مسار تسجيل الدخول للحصول على التوكن
Route::post('/login', [AuthController::class, 'login']);

// ملاحظة: لقد أزلنا مسار التسجيل العام /register
// لأنك ذكرت أن الأدمن هو الوحيد الذي ينشئ المستخدمين.
// إذا كنت لا تزال بحاجة إليه لسبب ما، يمكنك إضافته هنا.


// =========================================================================
// == المسارات المحمية (Protected Routes) ==
// هذه المسارات تتطلب توكن مصادق عليه (Bearer Token) للوصول إليها.
// =========================================================================

Route::middleware('auth:sanctum')->group(function () {

    // --- مسار خاص بالأدمن فقط ---
    // استخدم ->can() للتحقق من الصلاحية
    Route::post('/users/create', [AuthController::class, 'createUser'])
        ->can('create', User::class);
    // الكود الصحيح المطلوب في routes/api.php
    Route::get('/users', [UserController::class, 'index'])
        ->can('viewAny', User::class); // <-- هذا هو الإصلاح الصحيح
    // عرض مستخدم واحد
    Route::get('/users/{user}', [UserController::class, 'show'])->can('view', 'user');

    // تحديث مستخدم
    Route::put('/users/{user}', [UserController::class, 'update'])->can('update', 'user');

    // حذف مستخدم
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->can('delete', 'user');
    // مسار يعيد بيانات المستخدم الحالي (مفيد جداً للواجهة الأمامية)
    // متاح لأي مستخدم مسجل دخوله
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    //see dashboard
    Route::get('/v1/dashboard', [DashboardController::class, 'index']);

    // --- مسار تسجيل الخروج ---
    // متاح لأي مستخدم مسجل دخوله
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/buildings', [BuildingController::class, 'index']);
Route::get('/buildings/{building}', [BuildingController::class, 'show']);
