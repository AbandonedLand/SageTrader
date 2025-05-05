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
        Schema::create('uniswaps', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('token_x_starting_reserve_amount');
            $table->bigInteger('token_y_starting_reserve_amount');
            $table->bigInteger('token_x_real_reserve_amount');
            $table->bigInteger('token_y_real_reserve_amount');
            $table->bigInteger('token_x_virtual_reserve_amount');
            $table->bigInteger('token_y_virtual_reserve_amount');
            $table->string('token_x_ticker');
            $table->boolean('token_x_is_xch');
            $table->string('token_y_ticker');
            $table->boolean('token_y_is_xch');
            $table->bigInteger('starting_price');
            $table->bigInteger('lower_price');
            $table->bigInteger('upper_price');
            $table->bigInteger('liquidity');
            $table->bigInteger('liquidity_squared');
            $table->integer('pool_fee_percent_thousandths');
            $table->integer('pool_fee_collected');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uniswaps');
    }
};
