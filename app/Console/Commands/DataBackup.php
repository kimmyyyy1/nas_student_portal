<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\Log;

class DataBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a daily backup of the database and local uploaded files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        // Ensure backup directory exists
        if (!Storage::disk('local')->exists('backups')) {
            Storage::disk('local')->makeDirectory('backups');
        }

        $timestamp = Carbon::now()->format('Y_m_d_H_i_s');
        $dbHost = env('DB_HOST');
        $dbUsername = env('DB_USERNAME');
        $dbPassword = env('DB_PASSWORD');
        $dbName = env('DB_DATABASE');

        $sqlFile = storage_path("app/backups/database_{$timestamp}.sql");
        $zipFile = storage_path("app/backups/backup_{$timestamp}.zip");

        // Use absolute path to XAMPP's mysqldump
        $passwordArg = empty($dbPassword) ? "" : "--password=\"{$dbPassword}\"";
        $dumpCommand = "C:\\xampp\\mysql\\bin\\mysqldump.exe --user=\"{$dbUsername}\" {$passwordArg} --host=\"{$dbHost}\" \"{$dbName}\" > \"{$sqlFile}\"";

        try {
            $this->info("Executing: " . $dumpCommand);
            // Execute the command
            exec($dumpCommand, $output, $returnVar);

            if ($returnVar !== 0) {
                // Keep a fallback in case it's added to global path
                $fallbackCommand = "mysqldump --user=\"{$dbUsername}\" {$passwordArg} --host=\"{$dbHost}\" \"{$dbName}\" > \"{$sqlFile}\"";
                $this->info("Executing fallback: " . $fallbackCommand);
                exec($fallbackCommand, $output2, $returnVar2);
                
                if ($returnVar2 !== 0) {
                    $this->error('Database backup failed. Could not execute mysqldump.');
                    Log::error('Automated Backup Failed: mysqldump could not be executed.');
                    return Command::FAILURE;
                }
            }

            $this->info('Database dumped successfully. Zipping files...');

            $this->info("Backup created successfully: {$sqlFile}");
            Log::info("Automated Backup Successful: Created {$sqlFile}");

            // Optional: Cleanup old backups (keep last 7 days)
            $this->cleanupOldBackups();

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Backup Error: ' . $e->getMessage());
            Log::error('Automated Backup Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
    
    protected function cleanupOldBackups()
    {
        $backupPath = storage_path('app/backups');
        $files = glob("{$backupPath}/backup_*.zip");
        $sevenDaysAgo = time() - (7 * 24 * 60 * 60);

        foreach ($files as $file) {
            if (is_file($file)) {
                if (filemtime($file) < $sevenDaysAgo) {
                    unlink($file);
                }
            }
        }
    }
}
