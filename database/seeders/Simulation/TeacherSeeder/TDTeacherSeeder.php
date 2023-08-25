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
        $this->create('Technical Drawing', level: ['9']);
        $this->create('Technical Drawing', level: ['10']);
        $this->create('Technical Drawing', level: ['12'], section: ['C', 'D']);
        $this->create('Technical Drawing', level: ['11'], section: ['C', 'D']);
    }
}
