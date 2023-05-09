<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class PETeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create(subject: 'Physical Education', level_category: 'Kindergarten');

        $elementaryTeachers = [
            '1', '2', '3', '4',
        ];
        foreach ($elementaryTeachers as $level) {
            $this->create('Physical Education', $level);
        }

        $middleTeachers = [
            '5', '6', '7', '8',
        ];
        foreach ($middleTeachers as $level) {
            $this->create('Physical Education', $level);
        }

        $this->create(subject: 'Physical Education', level_category: 'Secondary');
    }
}
