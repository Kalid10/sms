<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class BusinessTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create('Business Studies', level: ['9', '10']);
        $this->create('Business Studies', level: ['11', '12'], section: ['A', 'B']);
    }
}
