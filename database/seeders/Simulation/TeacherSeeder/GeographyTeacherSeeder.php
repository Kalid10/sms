<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class GeographyTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create('Geography', level: ['8']);
        $this->create('Geography', level: ['7']);

        $secondaryTeachers = [
            '9', '10', '11', '12',
        ];
        foreach ($secondaryTeachers as $level) {
            $this->create('Geography', $level);
        }
    }
}
