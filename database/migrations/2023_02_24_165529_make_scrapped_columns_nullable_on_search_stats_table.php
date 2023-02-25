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
        Schema::table('search_stats', function (Blueprint $table) {
            $table->integer('ads_count')->nullable()->change();
            $table->integer('links_count')->nullable()->change();
            $table->unsignedBigInteger('total_result_count')->nullable()->change();
            $table->text('raw_response')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_stats', function (Blueprint $table) {
            $table->integer('ads_count')->nullable(false)->change();
            $table->integer('links_count')->nullable(false)->change();
            $table->unsignedBigInteger('total_result_count')->nullable(false)->change();
            $table->text('raw_response')->nullable(false)->change();
        });
    }
};
