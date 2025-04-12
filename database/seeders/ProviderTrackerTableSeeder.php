<?php

namespace Database\Seeders;

use App\Models\ProviderTracker;
use Illuminate\Database\Seeder;

class ProviderTrackerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProviderTracker::factory()->count(10)->create();
    }
}
