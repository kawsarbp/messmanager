<?php

namespace App\Console\Commands;

use App\Models\Deposit;
use App\Models\Expense;
use App\Models\Meal;
use App\Models\Mess;
use App\Models\Month;
use Illuminate\Console\Command;

class InitMonths extends Command
{
    protected $signature = 'app:init-months';
    protected $description = 'Create initial months for existing messes and assign existing records';

    public function handle(): void
    {
        $messes = Mess::all();

        if ($messes->isEmpty()) {
            $this->info('No messes found.');
            return;
        }

        foreach ($messes as $mess) {
            if ($mess->months()->exists()) {
                $this->info("Mess '{$mess->name}' already has months. Skipping.");
                continue;
            }

            $earliestDeposit = Deposit::whereIn('member_id', $mess->members()->pluck('id'))->min('date');
            $earliestExpense = Expense::where('mess_id', $mess->id)->min('date');
            $earliestMeal = Meal::whereIn('member_id', $mess->members()->pluck('id'))->min('date');

            $dates = array_filter([$earliestDeposit, $earliestExpense, $earliestMeal]);
            $startDate = $dates ? min($dates) : now()->startOfMonth()->toDateString();

            $month = Month::create([
                'mess_id' => $mess->id,
                'label' => now()->format('F Y'),
                'start_date' => $startDate,
                'is_active' => true,
            ]);

            $memberIds = $mess->members()->pluck('id');

            Deposit::whereIn('member_id', $memberIds)->whereNull('month_id')->update(['month_id' => $month->id]);
            Expense::where('mess_id', $mess->id)->whereNull('month_id')->update(['month_id' => $month->id]);
            Meal::whereIn('member_id', $memberIds)->whereNull('month_id')->update(['month_id' => $month->id]);

            $this->info("Created month '{$month->label}' for mess '{$mess->name}' with {$month->deposits()->count()} deposits, {$month->expenses()->count()} expenses, {$month->meals()->count()} meals.");
        }

        $this->info('Done.');
    }
}
