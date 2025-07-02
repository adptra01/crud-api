<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *  'car_id',
     *  'order_date',
     *  'pickup_date',
     *  'dropoff_date',
     *  'pickup_location',
     *  'dropoff_location',
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->date('order_date');
            $table->date('pickup_date');
            $table->date('dropoff_date');
            $table->string('pickup_location', 50);
            $table->string('dropoff_location', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
