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
            $table->boolean('is_active')->default(false);
            $table->string('token_x_id');
            $table->string('token_y_id');
            $table->decimal('lower_price');
            $table->decimal('starting_price');
            $table->decimal('upper_price');
            $table->integer('min_starting_offers')->default(5);
            $table->string('token_x_amount');
            $table->string('token_y_amount');
            $table->integer('number_of_steps');
            $table->integer('amount_per_steps');
            $table->boolean('amount_is_token_x');
            $table->decimal('fee_percentage');
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
