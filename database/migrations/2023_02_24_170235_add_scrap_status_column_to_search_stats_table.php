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
        //todo Add string value for scrap status. so that many statuses can be saved
        Schema::table('search_stats', function (Blueprint $table) {
            $table->boolean('scrap_status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_stats', function (Blueprint $table) {
            $table->dropColumn('scrap_status');
        });
    }
};
