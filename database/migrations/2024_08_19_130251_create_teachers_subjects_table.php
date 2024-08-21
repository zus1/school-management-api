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
        Schema::create('teachers_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('school_class_id')->nullable();
            $table->foreign('teacher_id')->references('id')->on('teachers')->cascadeOnDelete();
            $table->foreign('subject_id')->references('id')->on('subjects')->cascadeOnDelete();
            $table->foreign('school_class_id')->references('id')->on('school_classes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers_subjects');
    }
};
