<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // استيراد Auth

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. تحقق أولاً من أن المستخدم قام بتسجيل الدخول
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401); // غير مصادق عليه
        }

        // 2. تحقق من أن دور المستخدم هو 'admin'
        //    (تأكد من أن اسم الحقل 'role' وقيمته 'admin' تتطابق مع قاعدة بياناتك)
        if (auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden: You do not have admin privileges.'], 403); // ممنوع الوصول
        }

        // 3. إذا كان المستخدم هو admin، اسمح للطلب بالمرور إلى الـ Controller
        return $next($request);
    }
}
