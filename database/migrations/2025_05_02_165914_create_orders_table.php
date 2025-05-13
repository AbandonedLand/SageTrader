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
            $table->string('requested_asset');
            $table->string('requested_code')->nullable();
            $table->integer('requested_amount');
            $table->string('offered_asset');
            $table->string('offered_code')->nullable();
            $table->integer('offered_amount');
            $table->integer('fee_collected')->nullable();
            $table->string('fee_collected_asset')->nullable();
            $table->integer('market_fee_paid')->default(0);
            $table->integer('transaction_fee_paid')->default(0);
            $table->decimal('price');
            $table->string('offer')->nullable();
            $table->string('offer_id')->nullable();
            $table->string('dexie_id')->nullable();
            $table->boolean('is_created')->nullable();
            $table->boolean('is_submitted')->default(false);
            $table->boolean('is_cancelled')->default(false);
            $table->boolean('is_filled')->default(false);
            $table->string('previous_required_offer')->nullable();
            $table->string('initiated_by');
            $table->json('meta_data')->nullable();
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
