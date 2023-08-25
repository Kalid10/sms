<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class MathsTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mathsElementary = [
            'Pre-KG', 'KG-1', 'KG-2', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
        ];
        foreach ($mathsElementary as $level) {
            $this->create('Mathematics', $level);
        }

        $this->create(subject: 'Mathematics', level: ['11'], section: ['A']);
        $this->create(subject: 'Mathematics', level: ['11'], section: ['B']);
        $this->create(subject: 'Mathematics', level: ['12'], section: ['B']);
        $this->create(subject: 'Mathematics', level: ['12'], section: ['A']);
        $this->create(subject: 'Mathematics', level: ['11'], section: ['C']);
        $this->create(subject: 'Mathematics', level: ['11'], section: ['D']);
        $this->create(subject: 'Mathematics', level: ['12'], section: ['C']);
        $this->create(subject: 'Mathematics', level: ['12'], section: ['D']);
    }
}
