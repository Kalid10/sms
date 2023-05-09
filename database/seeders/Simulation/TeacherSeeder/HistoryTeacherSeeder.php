<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class HistoryTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create('History', level: ['7', '8']);

        $secondaryTeachers = [
            '9', '10', '11', '12',
        ];
        foreach ($secondaryTeachers as $level) {
            $this->create('History', $level);
        }
    }
}
