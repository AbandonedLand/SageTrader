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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->json('sage_offer_builder');
            $table->string('status')->default('not_created');
            $table->string('offer_id')->nullable();
            $table->string('offer')->nullable();
            $table->string('dexie_id')->nullable();
            $table->string('asset_x_id')->nullable();
            $table->string('asset_y_id')->nullable();
            $table->string('botable_id')->nullable();
            $table->string('botable_type')->nullable();
            $table->date('generated_at')->nullable();
            $table->date('submitted_at')->nullable();
            $table->date('taken_at')->nullable();
            $table->date('post_processed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
