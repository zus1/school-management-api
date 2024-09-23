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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->dateTime('created_at');
            $table->dateTime('updated_at')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('product_id');
            $table->string('price_id')->nullable();
            $table->float('price')->nullable();
            $table->tinyInteger('active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
