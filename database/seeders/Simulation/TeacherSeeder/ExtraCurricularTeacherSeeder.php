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
        $this->create(subject: 'Music', level_category: 'Kindergarten');
        $this->create(subject: 'Music', level_category: 'Primary');
    }

    private function art(): void
    {
        $this->create(subject: 'Art', level_category: 'Kindergarten');
        $this->create(subject: 'Art', level_category: 'Primary');
    }

    private function drama(): void
    {
        $this->create(subject: 'Drama', level_category: ['Kindergarten', 'Secondary']);
        $this->create(subject: 'Drama', level_category: 'Primary');
    }
}
