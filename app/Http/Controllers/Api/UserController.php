<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;



class UserController extends Controller
{
    /**
     * عرض قائمة بجميع المستخدمين.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // جلب جميع المستخدمين من قاعدة البيانات
        $users = User::paginate(10);

        // إرجاع قائمة المستخدمين كاستجابة JSON
        return response()->json([
            'status' => 'success',
            'data'=>new UserCollection($users),
        ]);
    }

    public function show(User $user)
    {
        // Laravel سيقوم تلقائياً بإيجاد المستخدم بناءً على الـ {id} في المسار
        // إذا لم يجده، سيعيد خطأ 404 Not Found تلقائياً
        return response()->json([
            'status' => 'success',
            'data'=> new UserResource($user)
        ]);
    }


    public function update(UpdateUserRequest $request, User $user)
    {
        // الحصول على البيانات المفحوصة مسبقاً من الـ Request
        $validatedData = $request->validated();

        // تحديث كلمة المرور فقط إذا تم إرسالها
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        // تحديث بيانات المستخدم
        $user->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'data'=> new UserResource($user)
        ]);
    }

    public function destroy(User $user)
    {
        // لا تسمح للأدمن بحذف نفسه
        if ($user->id === auth()->id()) {
            return response()->json(['status' => 'error', 'message' => 'You cannot delete your own account.'], 403);
        }

        $user->delete();

        return response()->json(['status' => 'success', 'message' => 'User deleted successfully']);
    }
}
