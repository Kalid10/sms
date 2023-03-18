<?php

namespace App\Console\Commands;

use App\Models\Level;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-levels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create levels';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();
            // create kg levels
            for ($i = 1; $i <= 3; $i++) {
                Level::firstOrCreate([
                    'name' => 'KG-'.$i,
                ]);

                $this->info('Level KG-'.$i.' created.');
            }

            for ($i = 1; $i <= 12; $i++) {
                Level::firstOrCreate([
                    'name' => $i,
                ]);

                $this->info('Level Grade-'.$i.' created.');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error($e->getMessage());
        }
    }
}
