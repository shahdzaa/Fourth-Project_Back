<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    // في app/Http/Controllers/Api/AuthController.php

    public function createUser(StoreUserRequest $request)
    {
        if (!$request->user()->tokenCan('user:create')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validated();

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'], // تعيين الدور
        ]);

        return response()->json([
            'data'=> new UserResource($user)
        ], 201);
    }

    // دالة تسجيل الدخول
    // في app/Http/Controllers/Api/AuthController.php

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // هذا السطر يتحقق من صحة الإيميل وكلمة السر
        if (!Auth::attempt($request->only('email', 'password'))) {
            // إذا كانت البيانات خاطئة، سيتم إلقاء استثناء هنا ولن يكمل الكود
            throw ValidationException::withMessages([
                'email' => ['بيانات الاعتماد المقدمة غير صحيحة.'],
            ]);
        }

        // إذا وصل الكود إلى هنا، فهذا يعني أن Auth::attempt نجحت
        // والبيانات صحيحة 100%
        $user = User::where('email', $request['email'])->firstOrFail();

        // ... باقي الكود لتحديد الصلاحيات وإنشاء التوكن ...
        $abilities = [];
        if ($user->role === 'admin') {
            $abilities = ['user:create', 'user:view','user:update','user:delete', 'profile:view', 'dashboard:view'];
        } else {
            $abilities = ['profile:view', 'dashboard:view'];
        }

        $token = $user->createToken('auth_token', $abilities)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data'=> new UserResource($user)
        ]);
    }

    // دالة تسجيل الخروج
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'تم تسجيل الخروج بنجاح']);
    }

}
