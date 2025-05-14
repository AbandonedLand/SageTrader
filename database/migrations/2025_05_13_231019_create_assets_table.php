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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id');
            $table->string('fingerprint_id');
            $table->string('name');
            $table->string('code');
            $table->string('tibetswap_pair_id')->nullable();
            $table->string('tibetswap_liquidity_asset_id')->nullable();
            $table->boolean('can_dexie_swap')->default(false);
            $table->integer('decimals')->default(1000);
            $table->integer('balance')->default(0);
            $table->integer('reserved_balance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
