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
        Schema::create('dinner_only', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')
                ->constrained('order_items')
                ->cascadeOnDelete();
            $table->string('dietary_restrictions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dinner_only');
    }
};
