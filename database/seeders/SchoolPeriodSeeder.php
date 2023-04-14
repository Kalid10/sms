<?php

namespace Database\Seeders;

use App\Models\SchoolPeriod;
use Illuminate\Database\Seeder;

class SchoolPeriodSeeder extends Seeder
{
    public function run()
    {
        SchoolPeriod::factory()->count(50)->create();
    }
}
