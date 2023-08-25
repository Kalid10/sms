<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class PhysicsTeacherSeeder extends TeacherSeeder
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
            $this->create('Physics', $level);
        }

        $this->create(subject: 'Physics', level: ['12'], section: ['C', 'D']);
        $this->create(subject: 'Physics', level: ['11'], section: ['D']);
        $this->create(subject: 'Physics', level: ['11'], section: ['C']);
    }
}
