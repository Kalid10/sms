<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class NapTimeTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create(subject: 'Nap Time', level: ['Pre-KG'], section: ['A']);
        $this->create(subject: 'Nap Time', level: ['Pre-KG'], section: ['B']);
        $this->create(subject: 'Nap Time', level: ['Pre-KG'], section: ['C']);
        $this->create(subject: 'Nap Time', level: ['Pre-KG'], section: ['D']);
        $this->create(subject: 'Nap Time', level: ['KG-1'], section: ['A']);
        $this->create(subject: 'Nap Time', level: ['KG-1'], section: ['B']);
        $this->create(subject: 'Nap Time', level: ['KG-1'], section: ['C']);
        $this->create(subject: 'Nap Time', level: ['KG-1'], section: ['D']);
        $this->create(subject: 'Nap Time', level: ['KG-2'], section: ['A']);
        $this->create(subject: 'Nap Time', level: ['KG-2'], section: ['B']);
        $this->create(subject: 'Nap Time', level: ['KG-2'], section: ['C']);
        $this->create(subject: 'Nap Time', level: ['KG-2'], section: ['D']);
    }
}
