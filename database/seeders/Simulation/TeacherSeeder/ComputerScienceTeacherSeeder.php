<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class ComputerScienceTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create(subject: 'Computer Science', level: ['1', '2']);

        $elementaryTeachers = [
            '3', '4', '5', '6', '7', '8',
        ];
        foreach ($elementaryTeachers as $level) {
            $this->create('Computer Science', $level);
        }

        $this->create(subject: 'Computer Science', level: ['9', '10']);
        $this->create(subject: 'Computer Science', level: ['11', '12']);
    }
}
