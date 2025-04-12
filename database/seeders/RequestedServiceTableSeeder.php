<?php

namespace Database\Seeders;

use App\Models\RequestedService;
use Illuminate\Database\Seeder;

class RequestedServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RequestedService::factory()->count(10)->create();
    }
}
