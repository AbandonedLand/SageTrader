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
        Schema::create('dcas', function (Blueprint $table) {
            $table->id();
            $table->integer('asset_id');
            $table->boolean('is_active')->default(true);
            $table->integer('buy_frequency')->default(60);
            $table->string('buy_sell');
            $table->integer('amount');
            $table->integer('max_amount')->nullable();
            $table->integer('current_amount')->default(0);
            $table->string('price_lt_gt')->nullable();   // Price constraint for price less than or greater than
            $table->integer('price')->nullable();        // Set a price for the constraint.
            $table->integer('max_orders')->nullable();
            $table->integer('successful_orders')->default(0);
            $table->integer('failed_orders')->default(0);
            $table->date('end_date')->nullable();
            $table->date('last_run')->nullable();
            $table->date('next_run')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dcas');
    }
};
