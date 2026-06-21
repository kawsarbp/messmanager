<?php

namespace App\Livewire\Meals;

use App\Enums\MealType;
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

    public function updatedType($value)
    {
        if ($value !== '') {
            $this->quantity = MealType::from((int) $value)->rate();
        }
    }

    public function save()
    {
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

        session()->flash('message', $this->editingId ? 'Meal updated successfully.' : 'Meal added successfully.');
        $this->cancelEdit();
    }

    public function editMeal($id)
    {
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
        Meal::findOrFail($id)->delete();
        session()->flash('message', 'Meal deleted.');
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
        $memberIds = $mess->members()->pluck('id');

        $members = Member::with('user')->whereIn('id', $memberIds)->orderBy('id')->get();

        $meals = Meal::whereIn('member_id', $memberIds)
            ->with('member.user')
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
