<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('integration_logs', function (Blueprint $table) {
            $table->id();
            $table->string('system_code', 50);
            $table->string('operation', 100);
            $table->char('correlation_id', 36)->nullable();
            $table->string('outcome', 20);
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->string('error_code', 100)->nullable();
            $table->timestamp('occurred_at')->useCurrent();

            $table->index(['system_code', 'operation', 'occurred_at']);
            $table->index('correlation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('integration_logs');
    }
};
