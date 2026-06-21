<?php

namespace App\Livewire\Deposits;

use App\Enums\Role;
use App\Enums\VisibilityStatus;
use App\Models\Deposit;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $member_id = '';
    public $amount = '';
    public $date = '';
    public $note = '';
    public $editingId = null;
    public $filterMemberId = '';

    protected function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:members,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function filter()
    {
        $this->resetPage();
    }

    public function save()
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }

        $this->validate();

        $activeMonth = Auth::user()->member->mess->activeMonth();

        Deposit::updateOrCreate(
            ['id' => $this->editingId],
            [
                'month_id' => $activeMonth?->id,
                'member_id' => $this->member_id,
                'amount' => $this->amount,
                'date' => $this->date,
                'note' => $this->note,
            ]
        );

        $this->dispatch('toast', message: $this->editingId ? 'Deposit updated successfully.' : 'Deposit added successfully.');
        $this->cancelEdit();
    }

    public function editDeposit($id)
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }
        $deposit = Deposit::findOrFail($id);
        $this->editingId = $deposit->id;
        $this->member_id = $deposit->member_id;
        $this->amount = $deposit->amount;
        $this->date = $deposit->date->format('Y-m-d');
        $this->note = $deposit->note;
    }

    public function cancelEdit()
    {
        $this->reset('editingId', 'member_id', 'amount', 'note');
        $this->date = now()->format('Y-m-d');
    }

    public function deleteDeposit($id)
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }
        Deposit::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Deposit deleted.');
    }

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
        $memberIds = $mess->members()->where('status', VisibilityStatus::Active)->pluck('id');

        $members = Member::with('user')->whereIn('id', $memberIds)->orderBy('id')->get();

        $activeMonth = $mess->activeMonth();

        $deposits = Deposit::whereIn('member_id', $memberIds)
            ->when($activeMonth, fn ($q) => $q->where('month_id', $activeMonth->id))
            ->with('member.user')
            ->when($this->filterMemberId, fn ($q) => $q->where('member_id', $this->filterMemberId))
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('livewire.deposits.index', compact('deposits', 'members'))
            ->layout('layouts.app', ['title' => 'Deposits - DIU Mess Management System']);
    }
}
