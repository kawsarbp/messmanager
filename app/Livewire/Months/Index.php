<?php

namespace App\Livewire\Months;

use App\Enums\Role;
use App\Models\Mess;
use App\Models\Month;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public ?int $viewingMonthId = null;
    public bool $confirmingClose = false;

    public function viewMonth(int $monthId): void
    {
        $this->viewingMonthId = $monthId;
    }

    public function backToMonths(): void
    {
        $this->viewingMonthId = null;
    }

    public function confirmClose(): void
    {
        $this->confirmingClose = true;
    }

    public function cancelClose(): void
    {
        $this->confirmingClose = false;
    }

    public function closeMonth(): void
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }

        $user = Auth::user();
        $mess = $user->member->mess;

        $activeMonth = $mess->months()->where('is_active', true)->first();

        if (!$activeMonth) {
            $this->dispatch('toast', message: 'No active month found.', type: 'error');
            return;
        }

        $activeMonth->update([
            'is_active' => false,
            'end_date' => now()->subDay()->toDateString(),
        ]);

        $monthCount = $mess->months()->count();

        Month::create([
            'mess_id' => $mess->id,
            'label' => now()->format('F Y') . ($monthCount > 1 ? ' #' . $monthCount : ''),
            'start_date' => now()->toDateString(),
            'is_active' => true,
        ]);

        $this->confirmingClose = false;
        $this->dispatch('toast', message: 'Month closed successfully. A new month has started.');
    }

    public function refreshCode(): void
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }

        $mess = Auth::user()->member->mess;
        $mess->update(['code' => Mess::generateUniqueCode()]);

        $this->dispatch('toast', message: 'Mess code updated.');
    }

    public function logout(): void
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

        $months = $mess->months()->orderBy('id', 'desc')->get();
        $activeMonth = $months->firstWhere('is_active', true);

        $viewingMonth = null;
        if ($this->viewingMonthId) {
            $viewingMonth = $months->firstWhere('id', $this->viewingMonthId);
        }

        return view('livewire.months.index', compact('mess', 'months', 'activeMonth', 'viewingMonth'))
            ->layout('layouts.app', ['title' => 'Months - DIU Mess Management System']);
    }
}
