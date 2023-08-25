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
        $this->create('አማርኛ', level: ['Pre-KG'], section: ['A']);
        $this->create('አማርኛ', level: ['Pre-KG'], section: ['B']);
        $this->create('አማርኛ', level: ['Pre-KG'], section: ['C']);
        $this->create('አማርኛ', level: ['Pre-KG'], section: ['D']);
        $this->create('አማርኛ', level: ['KG-1'], section: ['A']);
        $this->create('አማርኛ', level: ['KG-1'], section: ['B']);
        $this->create('አማርኛ', level: ['KG-1'], section: ['C']);
        $this->create('አማርኛ', level: ['KG-1'], section: ['D']);
        $this->create('አማርኛ', level: ['KG-2'], section: ['A']);
        $this->create('አማርኛ', level: ['KG-2'], section: ['B']);
        $this->create('አማርኛ', level: ['KG-2'], section: ['C']);
        $this->create('አማርኛ', level: ['KG-2'], section: ['D']);

        $englishPrimary = [
            '1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
        ];
        foreach ($englishPrimary as $level) {
            $this->create('አማርኛ', $level);
        }

        $this->create(subject: 'አማርኛ', level: ['12'], section: ['A']);
        $this->create(subject: 'አማርኛ', level: ['12'], section: ['B']);
        $this->create(subject: 'አማርኛ', level: ['11'], section: ['A']);
        $this->create(subject: 'አማርኛ', level: ['11'], section: ['B']);
        $this->create(subject: 'አማርኛ', level: ['11'], section: ['C']);
        $this->create(subject: 'አማርኛ', level: ['12'], section: ['C']);
        $this->create(subject: 'አማርኛ', level: ['11'], section: ['D']);
        $this->create(subject: 'አማርኛ', level: ['12'], section: ['D']);
    }
}
