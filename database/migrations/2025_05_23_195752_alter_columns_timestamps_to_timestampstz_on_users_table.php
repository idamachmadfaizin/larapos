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
        Schema::table('users', function (Blueprint $table) {
            $table->dropTimestamps();
            $table->timestampsTz();
        });
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->timestampTz('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropTimestampsTz();
            $table->timestamps();
        });
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropColumn('created_at');
            $table->timestamp('created_at')->nullable();
        });
    }
};
