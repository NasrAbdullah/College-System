<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->restrictOnDelete();
            $table->foreignId('service_type_id')->constrained('service_types')->restrictOnDelete();
            $table->string('subject', 200);
            $table->text('details');
            $table->string('status', 30)->default('submitted');
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['service_type_id', 'status', 'submitted_at'], 'requests_type_status_submitted_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};
