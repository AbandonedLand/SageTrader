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
        Schema::create('grid_bots', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_active')->default(true);
            $table->string('token_x_asset_id');
            $table->string('token_y_asset_id');
            $table->integer('token_x_reserve');
            $table->integer('token_y_reserve');
            $table->decimal('lower_price');
            $table->decimal('start_price');
            $table->decimal('upper_price');
            $table->integer('tick_count');
            $table->decimal('liquidity_fee');
            $table->string('fee_collected');
            $table->boolean('fee_is_token_x')->default(true);
            $table->json('grid');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grid_bots');
    }
};
