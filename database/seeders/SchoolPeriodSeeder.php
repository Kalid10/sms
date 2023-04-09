<?php

namespace Database\Seeders;

use App\Models\SchoolPeriod;
use Illuminate\Database\Seeder;

class SchoolPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolPeriod::factory()->create();
    }
}
