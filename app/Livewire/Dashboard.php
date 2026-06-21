<?php

namespace App\Livewire;

use App\Enums\VisibilityStatus;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\Meal;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Dashboard extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect('/');
    }

    public function render()
    {
        $user = Auth::user();
        $mess = $user->member->mess;
        $currentMemberId = $user->member->id;

        $members = Member::with('user')->where('mess_id', $mess->id)->where('status', VisibilityStatus::Active)->get();
        $memberIds = $members->pluck('id');

        $totalExpenses = Expense::where('mess_id', $mess->id)->sum('amount');

        $deposits = Deposit::whereIn('member_id', $memberIds)
            ->selectRaw('member_id, sum(amount) as total')
            ->groupBy('member_id')
            ->pluck('total', 'member_id');

        $meals = Meal::whereIn('member_id', $memberIds)
            ->selectRaw('member_id, sum(quantity) as total')
            ->groupBy('member_id')
            ->pluck('total', 'member_id');

        $totalMealQty = $meals->sum();
        $totalDeposits = $deposits->sum();

        $summary = $members->map(function ($member) use ($deposits, $meals, $totalExpenses, $totalMealQty, $currentMemberId, $members) {
            $memberDeposits = (float) ($deposits[$member->id] ?? 0);
            $memberMeals = (float) ($meals[$member->id] ?? 0);
            $expenseShare = $totalMealQty > 0
                ? ($memberMeals / $totalMealQty) * $totalExpenses
                : $totalExpenses / max($members->count(), 1);
            $balance = $memberDeposits - $expenseShare;

            return [
                'id' => $member->id,
                'name' => $member->user->name,
                'is_me' => $member->id === $currentMemberId,
                'total_deposits' => $memberDeposits,
                'total_meals' => $memberMeals,
                'balance' => $balance,
            ];
        });

        $summary = $summary->sortByDesc('is_me')->values();

        return view('livewire.dashboard', compact('summary', 'totalExpenses', 'totalDeposits', 'totalMealQty'))
            ->layout('layouts.app', ['title' => 'Dashboard - DIU Mess Management System']);
    }
}
