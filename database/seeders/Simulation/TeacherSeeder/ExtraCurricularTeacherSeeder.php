<?php

namespace Database\Seeders\Simulation\TeacherSeeder;

use Database\Seeders\Simulation\TeacherSeeder;

class ExtraCurricularTeacherSeeder extends TeacherSeeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->music();
        $this->art();
        $this->drama();
    }

    private function music(): void
    {
        $this->create(subject: 'Music', level: ['Pre-KG'], section: ['A']);
        $this->create(subject: 'Music', level: ['Pre-KG'], section: ['B']);
        $this->create(subject: 'Music', level: ['Pre-KG'], section: ['C']);
        $this->create(subject: 'Music', level: ['Pre-KG'], section: ['D']);
        $this->create(subject: 'Music', level: ['KG-1'], section: ['A']);
        $this->create(subject: 'Music', level: ['KG-1'], section: ['B']);
        $this->create(subject: 'Music', level: ['KG-1'], section: ['C']);
        $this->create(subject: 'Music', level: ['KG-1'], section: ['D']);
        $this->create(subject: 'Music', level: ['KG-2'], section: ['A']);
        $this->create(subject: 'Music', level: ['KG-2'], section: ['B']);
        $this->create(subject: 'Music', level: ['KG-2'], section: ['C']);
        $this->create(subject: 'Music', level: ['KG-2'], section: ['D']);

        $primary = [
            '1', '2', '3', '4', '5', '6', '7', '8',
        ];

        foreach ($primary as $level) {
            $this->create(subject: 'Music', level: [$level]);
        }
    }

    private function art(): void
    {
        $this->create(subject: 'Art', level: ['Pre-KG'], section: ['A']);
        $this->create(subject: 'Art', level: ['Pre-KG'], section: ['B']);
        $this->create(subject: 'Art', level: ['Pre-KG'], section: ['C']);
        $this->create(subject: 'Art', level: ['Pre-KG'], section: ['D']);
        $this->create(subject: 'Art', level: ['KG-1'], section: ['A']);
        $this->create(subject: 'Art', level: ['KG-1'], section: ['B']);
        $this->create(subject: 'Art', level: ['KG-1'], section: ['C']);
        $this->create(subject: 'Art', level: ['KG-1'], section: ['D']);
        $this->create(subject: 'Art', level: ['KG-2'], section: ['A']);
        $this->create(subject: 'Art', level: ['KG-2'], section: ['B']);
        $this->create(subject: 'Art', level: ['KG-2'], section: ['C']);
        $this->create(subject: 'Art', level: ['KG-2'], section: ['D']);
        $this->create(subject: 'Art', level_category: 'Primary');
    }

    private function drama(): void
    {
        $this->create(subject: 'Drama', level: ['Pre-KG'], section: ['A']);
        $this->create(subject: 'Drama', level: ['Pre-KG'], section: ['B']);
        $this->create(subject: 'Drama', level: ['Pre-KG'], section: ['C']);
        $this->create(subject: 'Drama', level: ['Pre-KG'], section: ['D']);
        $this->create(subject: 'Drama', level: ['KG-1'], section: ['A']);
        $this->create(subject: 'Drama', level: ['KG-1'], section: ['B']);
        $this->create(subject: 'Drama', level: ['KG-1'], section: ['C']);
        $this->create(subject: 'Drama', level: ['KG-1'], section: ['D']);
        $this->create(subject: 'Drama', level: ['KG-2'], section: ['A']);
        $this->create(subject: 'Drama', level: ['KG-2'], section: ['B']);
        $this->create(subject: 'Drama', level: ['KG-2'], section: ['C']);
        $this->create(subject: 'Drama', level: ['KG-2'], section: ['D']);
        $this->create(subject: 'Drama', level_category: ['Secondary']);
        $this->create(subject: 'Drama', level_category: 'Primary');
    }
}
