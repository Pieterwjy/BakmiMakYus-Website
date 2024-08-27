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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('table_number');
            $table->string('order_type');
            $table->string('notes')->nullable();
            $table->string('order_status')->nullable();
            $table->float('gross_amount',12,0);
            $table->string('cashier')->nullable();
            $table->string('status')->nullable();
            $table->string('snap_token')->nullable();
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
