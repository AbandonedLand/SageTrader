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
        Schema::create('dca_bots', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(false);
            $table->string('asset_x_id')->default(1);
            $table->string('asset_y_id');
            $table->integer('amount');
            $table->boolean('amount_is_x');
            $table->boolean('amount_is_offered');
            $table->integer('min_price')->nullable();
            $table->integer('max_price')->nullable();
            $table->integer('frequency_minutes')->nullable();
            $table->integer('max_amount')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dca_bots');
    }
};
