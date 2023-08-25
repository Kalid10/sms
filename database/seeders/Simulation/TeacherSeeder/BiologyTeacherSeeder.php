<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class BiologyTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $middleSchool = [
            '7', '8', '9', '10',
        ];
        foreach ($middleSchool as $level) {
            $this->create('Biology', $level);
        }

        $this->create(subject: 'Biology', level: ['11'], section: ['D']);
        $this->create(subject: 'Biology', level: ['11'], section: ['C']);
        $this->create(subject: 'Biology', level: ['12'], section: ['D']);
        $this->create(subject: 'Biology', level: ['12'], section: ['C']);
    }
}
