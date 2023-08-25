<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class SocialStudiesTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create(subject: 'Social Studies', level: ['1']);
        $this->create(subject: 'Social Studies', level: ['2']);

        $elementaryTeachers = [
            '3', '4', '5', '6', '7', '8',
        ];
        foreach ($elementaryTeachers as $level) {
            $this->create('Social Studies', $level);
        }
    }
}
