<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use App\Models\TopicProgress;
use App\Models\Topic;
use App\Models\User;

class FixVideoProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'video:fix-progress {--user-id= : Specific user ID to fix} {--topic-id= : Specific topic ID to fix} {--reset : Reset all progress to 0} {--repair : Repair corrupted progress data}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose and fix video progress tracking issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Diagnosing video progress system...');
        
        // Check database connection
        try {
            DB::connection()->getPdo();
            $this->info('✅ Database connection: OK');
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: ' . $e->getMessage());
            return 1;
        }

        // Check tables
        $this->checkTables();
        
        // Check for corrupted data
        $this->checkCorruptedData();
        
        // Fix specific issues
        if ($this->option('repair')) {
            $this->repairProgressData();
        }
        
        if ($this->option('reset')) {
            $this->resetAllProgress();
        }
        
        // Generate report
        $this->generateReport();
        
        $this->info('🎯 Video progress system diagnosis completed!');
        return 0;
    }

    /**
     * Check if required tables exist
     */
    private function checkTables()
    {
        $this->info('📊 Checking database tables...');
        
        $tables = ['topic_progress', 'topics', 'users'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                $this->info("✅ Table '{$table}': EXISTS");
            } else {
                $this->error("❌ Table '{$table}': MISSING");
            }
        }
    }

    /**
     * Check for corrupted progress data
     */
    private function checkCorruptedData()
    {
        $this->info('🔍 Checking for corrupted progress data...');
        
        // Check for invalid progress values
        $invalidProgress = TopicProgress::where('progress', '<', 0)
            ->orWhere('progress', '>', 100)
            ->count();
            
        if ($invalidProgress > 0) {
            $this->warn("⚠️  Found {$invalidProgress} records with invalid progress values");
        } else {
            $this->info('✅ All progress values are valid');
        }
        
        // Check for orphaned records
        $orphanedProgress = TopicProgress::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('topics')
                  ->whereRaw('topics.id = topic_progress.topic_id');
        })->count();
        
        if ($orphanedProgress > 0) {
            $this->warn("⚠️  Found {$orphanedProgress} orphaned progress records");
        } else {
            $this->info('✅ No orphaned progress records found');
        }
        
        // Check for user-less records
        $userlessProgress = TopicProgress::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('users')
                  ->whereRaw('users.id = topic_progress.user_id');
        })->count();
        
        if ($userlessProgress > 0) {
            $this->warn("⚠️  Found {$userlessProgress} progress records without users");
        } else {
            $this->info('✅ All progress records have valid users');
        }
    }

    /**
     * Repair corrupted progress data
     */
    private function repairProgressData()
    {
        $this->info('🔧 Repairing corrupted progress data...');
        
        // Fix invalid progress values
        $fixed = TopicProgress::where('progress', '<', 0)
            ->orWhere('progress', '>', 100)
            ->update(['progress' => 0]);
            
        if ($fixed > 0) {
            $this->info("✅ Fixed {$fixed} invalid progress values");
        }
        
        // Remove orphaned records
        $removed = TopicProgress::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('topics')
                  ->whereRaw('topics.id = topic_progress.topic_id');
        })->delete();
        
        if ($removed > 0) {
            $this->info("✅ Removed {$removed} orphaned progress records");
        }
        
        // Remove user-less records
        $removedUsers = TopicProgress::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('users')
                  ->whereRaw('users.id = topic_progress.user_id');
        })->delete();
        
        if ($removedUsers > 0) {
            $this->info("✅ Removed {$removedUsers} user-less progress records");
        }
    }

    /**
     * Reset all progress data
     */
    private function resetAllProgress()
    {
        if (!$this->confirm('Are you sure you want to reset ALL progress data? This cannot be undone!')) {
            $this->info('Progress reset cancelled.');
            return;
        }
        
        $this->info('🔄 Resetting all progress data...');
        
        $reset = TopicProgress::update(['progress' => 0]);
        $this->info("✅ Reset {$reset} progress records to 0%");
    }

    /**
     * Generate diagnostic report
     */
    private function generateReport()
    {
        $this->info('📋 Generating diagnostic report...');
        
        $totalProgress = TopicProgress::count();
        $totalUsers = User::count();
        $totalTopics = Topic::count();
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Progress Records', $totalProgress],
                ['Total Users', $totalUsers],
                ['Total Topics', $totalTopics],
                ['Progress Records per User', $totalUsers > 0 ? round($totalProgress / $totalUsers, 2) : 0],
                ['Progress Records per Topic', $totalTopics > 0 ? round($totalProgress / $totalTopics, 2) : 0],
            ]
        );
        
        // Show progress distribution
        $progressDistribution = TopicProgress::selectRaw('
            CASE 
                WHEN progress = 0 THEN "0%"
                WHEN progress <= 25 THEN "1-25%"
                WHEN progress <= 50 THEN "26-50%"
                WHEN progress <= 75 THEN "51-75%"
                WHEN progress <= 99 THEN "76-99%"
                WHEN progress = 100 THEN "100%"
            END as range,
            COUNT(*) as count
        ')
        ->groupBy('range')
        ->orderByRaw('MIN(progress)')
        ->get();
        
        $this->info('📊 Progress Distribution:');
        foreach ($progressDistribution as $dist) {
            $this->line("   {$dist->range}: {$dist->count} records");
        }
    }
}
