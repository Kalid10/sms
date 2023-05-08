<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class AmharicTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->create('አማርኛ', ['Pre-KG']);
        $this->create('አማርኛ', ['KG-1', 'KG-2']);

        $englishPrimary = [
            '1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
        ];
        foreach ($englishPrimary as $level) {
            $this->create('አማርኛ', $level);
        }

        $this->create(subject: 'አማርኛ', level: ['11', '12'], section: ['A', 'B']);
        $this->create(subject: 'አማርኛ', level: ['11', '12'], section: ['C', 'D']);
    }
}
