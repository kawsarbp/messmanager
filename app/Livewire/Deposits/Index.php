<?php

namespace App\Livewire\Deposits;

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

    public function save()
    {
        $this->validate();

        Deposit::create([
            'member_id' => $this->member_id,
            'amount' => $this->amount,
            'date' => $this->date,
            'note' => $this->note,
        ]);

        $this->reset('member_id', 'amount', 'note');
        $this->date = now()->format('Y-m-d');
        session()->flash('message', 'Deposit added successfully.');
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
        $memberIds = $mess->members()->pluck('id');

        $members = Member::with('user')->whereIn('id', $memberIds)->orderBy('id')->get();

        $deposits = Deposit::whereIn('member_id', $memberIds)
            ->with('member.user')
            ->latest()
            ->paginate(20);

        return view('livewire.deposits.index', compact('deposits', 'members'))
            ->layout('layouts.app', ['title' => 'Deposits - DIU Mess Management System']);
    }
}
