<?php

use App\Models\SearchStat;
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
            $table->string('scrap_status', 30)->default(SearchStat::SCRAP_STATUS_INITIALIZED)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('search_stats', function (Blueprint $table) {
            $table->boolean('scrap_status')->default(false)->change();
        });
    }
};
