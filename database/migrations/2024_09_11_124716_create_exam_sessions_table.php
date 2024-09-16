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
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->dateTime('started_at');
            $table->dateTime('ends_at');
            $table->dateTime('ended_at')->nullable();
            $table->integer('duration')->nullable();
            $table->string('status');
            $table->integer('achieved_points')->nullable();
            $table->float('achieved_percentage')->nullable();
            $table->integer('grade')->nullable();
            $table->string('comment')->nullable();
            $table->foreign('exam_id')->references('id')->on('exams');
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
    }
};
