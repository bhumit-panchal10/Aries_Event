<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class LogDelete extends Command
{
    protected $signature = 'send:log_delete';
    protected $description = 'log delete';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $logPath = storage_path('logs/laravel.log'); // Get the correct log path
        // dd($logPath);

        if (File::exists($logPath)) {
            if (File::delete($logPath)) {
                // Log::info('Log file deleted successfully.');
            } else {
                // Log::error('Failed to delete the log file.');
            }
        } else {
            // Log::info('Log file does not exist.');
        }

    }
}
