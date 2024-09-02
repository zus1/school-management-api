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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->string('description');
            $table->integer('weight');
            $table->integer('height')->comment('cm');
            $table->integer('width')->comment('cm');
            $table->integer('length')->comment('cm');
            $table->integer('total_quantity');
            $table->integer('available_quantity');
            $table->string('type');
            $table->float('cost');
            $table->float('cost_per_unit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
