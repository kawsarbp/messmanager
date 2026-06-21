<?php

namespace App\Livewire\Meals;

use App\Enums\MealType;
use App\Enums\Role;
use App\Enums\VisibilityStatus;
use App\Models\Meal;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $member_id = '';
    public $date = '';
    public $type = '';
    public $quantity = 1;
    public $editingId = null;
    public $filterMemberId = '';

    protected function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:members,id'],
            'date' => ['required', 'date'],
            'type' => ['required', 'integer', 'in:1,2,3'],
            'quantity' => ['required', 'numeric', 'min:0.01', 'max:999.99'],
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

    public function updatedType($value)
    {
        if ($value !== '') {
            $this->quantity = MealType::from((int) $value)->rate();
        }
    }

    public function save()
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }

        $this->validate();

        Meal::updateOrCreate(
            [
                'id' => $this->editingId,
            ],
            [
                'member_id' => $this->member_id,
                'date' => $this->date,
                'type' => $this->type,
                'quantity' => $this->quantity,
            ]
        );

        $this->dispatch('toast', message: $this->editingId ? 'Meal updated successfully.' : 'Meal added successfully.');
        $this->cancelEdit();
    }

    public function editMeal($id)
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }
        $meal = Meal::findOrFail($id);
        $this->editingId = $meal->id;
        $this->member_id = $meal->member_id;
        $this->date = $meal->date->format('Y-m-d');
        $this->type = $meal->type->value;
        $this->quantity = $meal->quantity;
    }

    public function cancelEdit()
    {
        $this->reset('editingId', 'member_id', 'type', 'quantity');
        $this->quantity = 1;
        $this->date = now()->format('Y-m-d');
    }

    public function deleteMeal($id)
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }
        Meal::findOrFail($id)->delete();
        $this->dispatch('toast', message: 'Meal deleted.');
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
        $memberIds = $mess->members()->where('status', VisibilityStatus::Active)->pluck('id');

        $members = Member::with('user')->whereIn('id', $memberIds)->orderBy('id')->get();

        $meals = Meal::whereIn('member_id', $memberIds)
            ->with('member.user')
            ->when($this->filterMemberId, fn ($q) => $q->where('member_id', $this->filterMemberId))
            ->latest()
            ->paginate(20);

        return view('livewire.meals.index', [
            'members' => $members,
            'mealTypes' => MealType::cases(),
            'meals' => $meals,
            'today' => Carbon::parse($this->date),
        ])->layout('layouts.app', ['title' => 'Meals - DIU Mess Management System']);
    }
}
