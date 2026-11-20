<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // The original Laravel users table already exists. Keep its password column.
        Schema::table('users', function (Blueprint $table) {
            // Student accounts need neither a duplicate official name nor an email address.
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();

            $table->string('login_name', 80)->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['login_name']);
            $table->dropColumn(['login_name', 'is_active', 'last_login_at']);
            // Do not restore NOT NULL for name/email: that could destroy valid student data.
        });
    }
};
