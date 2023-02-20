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
        Schema::create('search_stats', function (Blueprint $table) {
            $table->id();
            $table->string('keyword', 512)->index('keyword');
            $table->integer('ads_count');
            $table->integer('links_count');
            $table->unsignedBigInteger('total_result_count');
            $table->text('raw_response');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_stats');
    }
};
