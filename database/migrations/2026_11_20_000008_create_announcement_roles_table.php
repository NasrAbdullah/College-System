<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcement_roles', function (Blueprint $table) {
            $table->foreignId('announcement_id')->constrained('announcements')->restrictOnDelete();
            $table->foreignId('role_id')->constrained('roles')->restrictOnDelete();

            $table->primary(['announcement_id', 'role_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcement_roles');
    }
};
