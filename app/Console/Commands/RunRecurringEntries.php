<?php

namespace App\Console\Commands;

use App\Models\RecurrencePattern;
use App\Models\JournalEntry;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RunRecurringEntries extends Command
{
    protected $signature = 'accounting:run-recurring-entries';

    protected $description = 'Generate new journal entries from recurring patterns that are due';

    public function handle()
    {
        $this->info('Starting recurring journal entries generation...');
        
        $today = Carbon::today();
        
        $duePatterns = RecurrencePattern::with('templateJournalEntry.journalLines')
            ->where('next_run_date', '<=', $today)
            ->get();
        
        if ($duePatterns->isEmpty()) {
            $this->info('No recurring patterns are due today.');
            return Command::SUCCESS;
        }
        
        $this->info("Found {$duePatterns->count()} recurring pattern(s) to process.");
        
        $generatedCount = 0;
        
        foreach ($duePatterns as $pattern) {
            try {
                $template = $pattern->templateJournalEntry;
                
                $newEntry = JournalEntry::create([
                    'branch_id' => $template->branch_id,
                    'entry_date' => $today,
                    'description' => $template->description . ' (Recurring - Generated on ' . $today->format('Y-m-d') . ')',
                    'status' => 'Pending',
                    'source_module' => $template->source_module,
                ]);
                
                foreach ($template->journalLines as $line) {
                    $newEntry->journalLines()->create([
                        'account_id' => $line->account_id,
                        'debit' => $line->debit,
                        'credit' => $line->credit,
                    ]);
                }
                
                $nextRunDate = $this->calculateNextRunDate($today, $pattern->frequency);
                $pattern->update(['next_run_date' => $nextRunDate]);
                
                $this->info("✓ Generated Journal Entry #{$newEntry->id} from pattern #{$pattern->id}");
                $this->info("  Next run date set to: {$nextRunDate->format('Y-m-d')}");
                
                $generatedCount++;
                
            } catch (\Exception $e) {
                $this->error("✗ Failed to generate entry from pattern #{$pattern->id}: {$e->getMessage()}");
            }
        }
        
        $this->info("\n" . str_repeat('=', 50));
        $this->info("Recurring entries generation completed!");
        $this->info("Total entries generated: {$generatedCount}");
        $this->info(str_repeat('=', 50));
        
        return Command::SUCCESS;
    }

    private function calculateNextRunDate(Carbon $currentDate, string $frequency): Carbon
    {
        return match($frequency) {
            'Monthly' => $currentDate->copy()->addMonth(),
            'Quarterly' => $currentDate->copy()->addMonths(3),
            'Yearly' => $currentDate->copy()->addYear(),
            default => $currentDate->copy()->addMonth(),
        };
    }
}
