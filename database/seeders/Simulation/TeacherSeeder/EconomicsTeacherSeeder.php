<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class EconomicsTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create('Economics', level: ['9']);
        $this->create('Economics', level: ['10']);
        $this->create('Economics', level: ['11', '12'], section: ['A', 'B']);
    }
}
