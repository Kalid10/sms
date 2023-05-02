<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateBatchSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-batch-sessions {--duration=weekly : Generate batch sessions for the week or month}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate batch sessions for the week or month';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $duration = $this->option('duration');
        if (! in_array($duration, ['weekly', 'monthly'])) {
            $this->error('Invalid duration specified. Allowed values are "weekly" or "monthly".');

            return;
        }

        \App\Helpers\GenerateBatchSessions::generate($this, $duration);
    }
}
