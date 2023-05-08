<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class TDTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create('Technical Drawing', level: ['9', '10']);
        $this->create('Technical Drawing', level: ['11', '12'], section: ['C', 'D']);
    }
}
