<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProfessionTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(ProviderTrackerTableSeeder::class);
        $this->call(UserTrackerTableSeeder::class);
        $this->call(RequestedServiceTableSeeder::class);
    }
}
