<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إضافة عمود 'role' من نوع string
            // نجعله يقبل القيمة الافتراضية 'user'
            // after('email') لوضعه بعد عمود الإيميل (اختياري للتنظيم)
            $table->string('role')->default('user')->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إزالة العمود 'role' في حال التراجع عن الهجرة
            $table->dropColumn('role');
        });
    }
};
