<?php

namespace App\Livewire\Expenses;

use App\Enums\Role;
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
    public $editingId = null;

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
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }

        $this->validate();

        $mess = Auth::user()->member->mess;

        Expense::updateOrCreate(
            ['id' => $this->editingId],
            [
                'mess_id' => $mess->id,
                'amount' => $this->amount,
                'category' => $this->category,
                'description' => $this->description,
                'date' => $this->date,
            ]
        );

        $this->dispatch('toast', message: $this->editingId ? 'Expense updated successfully.' : 'Expense added successfully.');
        $this->cancelEdit();
    }

    public function editExpense($id)
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }
        $expense = Expense::findOrFail($id);
        $this->editingId = $expense->id;
        $this->amount = $expense->amount;
        $this->category = $expense->category;
        $this->description = $expense->description;
        $this->date = $expense->date->format('Y-m-d');
    }

    public function cancelEdit()
    {
        $this->reset('editingId', 'amount', 'category', 'description');
        $this->date = now()->format('Y-m-d');
    }

    public function deleteExpense($id)
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }
        Expense::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Expense deleted.');
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
            ->orderBy('id', 'desc')
            ->paginate(20);

        return view('livewire.expenses.index', compact('expenses'))
            ->layout('layouts.app', ['title' => 'Expenses - DIU Mess Management System']);
    }
}
