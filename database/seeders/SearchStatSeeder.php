<?php

namespace Database\Seeders;

use App\Models\SearchStat;
use Illuminate\Database\Seeder;

class SearchStatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SearchStat::factory(150)->create();
    }
}
