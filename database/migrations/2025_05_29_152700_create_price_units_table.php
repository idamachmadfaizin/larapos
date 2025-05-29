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
        Schema::create('price_units', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Product::class)->constrained();
            $table->double('price');
            $table->string('unit');
            $table->integer('min_qty')->default(1);
            $table->integer('max_qty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_units');
    }
};
