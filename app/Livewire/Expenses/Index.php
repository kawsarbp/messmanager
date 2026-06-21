<?php

namespace App\Livewire\Expenses;

use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $amount = '';
    public $category = '';
    public $description = '';
    public $date = '';

    protected function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'date' => ['required', 'date'],
        ];
    }

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        $mess = Auth::user()->member->mess;

        Expense::create([
            'mess_id' => $mess->id,
            'amount' => $this->amount,
            'category' => $this->category,
            'description' => $this->description,
            'date' => $this->date,
        ]);

        $this->reset('amount', 'category', 'description');
        $this->date = now()->format('Y-m-d');
        session()->flash('message', 'Expense added successfully.');
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
        $mess = Auth::user()->member->mess;

        $expenses = Expense::where('mess_id', $mess->id)
            ->latest()
            ->paginate(20);

        return view('livewire.expenses.index', compact('expenses'))
            ->layout('layouts.app', ['title' => 'Expenses - DIU Mess Management System']);
    }
}
