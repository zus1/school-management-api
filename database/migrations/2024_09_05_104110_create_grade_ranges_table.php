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
        Schema::create('grade_ranges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grading_rule_id');
            $table->integer('lower');
            $table->integer('upper');
            $table->tinyInteger('grade');
            $table->foreign('grading_rule_id')->references('id')->on('grading_rules')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_ranges');
    }
};
