<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absence_request_details', function (Blueprint $table) {
            $table->foreignId('service_request_id')->primary()->constrained('service_requests')->restrictOnDelete();
            $table->foreignId('academic_term_id')->constrained('academic_terms')->restrictOnDelete();
            $table->date('starts_on');
            $table->date('ends_on');

            // Course/lecture-level absence requires an approved extra schema.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absence_request_details');
    }
};
