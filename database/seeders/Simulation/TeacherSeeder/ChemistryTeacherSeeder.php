<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class ChemistryTeacherSeeder extends TeacherSeeder
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
            $this->create('Chemistry', $level);
        }

        $this->create(subject: 'Chemistry', level: ['11', '12'], section: ['C', 'D']);
    }
}
