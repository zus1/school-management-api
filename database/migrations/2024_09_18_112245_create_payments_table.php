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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->dateTime('created_at');
            $table->string('product_id');
            $table->string('product');
            $table->string('checkout_id')->nullable();
            $table->string('payment_id')->nullable();
            $table->float('sub_total');
            $table->float('total');
            $table->float('tax')->nullable();
            $table->float('discount')->nullable();
            $table->float('currency');
            $table->string('payment_status');
            $table->string('flow_status');
            $table->text('checkout_url')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
