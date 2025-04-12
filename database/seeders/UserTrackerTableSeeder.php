<?php

namespace Database\Seeders;

use App\Models\UserTracker;
use Illuminate\Database\Seeder;

class UserTrackerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserTracker::factory()->count(10)->create();
    }
}
