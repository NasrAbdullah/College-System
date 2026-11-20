<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_terms', function (Blueprint $table) {
            $table->id();
            $table->string('emtiaz_term_id', 100)->nullable()->unique();
            $table->string('academic_year', 9);
            $table->unsignedTinyInteger('term_number');
            $table->date('starts_on');
            $table->date('ends_on');
            $table->timestamps();

            $table->unique(['academic_year', 'term_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_terms');
    }
};
