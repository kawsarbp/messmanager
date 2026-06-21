<?php

namespace App\Livewire\Meals;

use App\Enums\MealType;
use App\Enums\Role;
use App\Enums\VisibilityStatus;
use App\Models\Meal;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $member_id = '';
    public $date = '';
    public $types = [];
    public $editingId = null;
    public $filterMemberId = '';

    protected function rules(): array
    {
        return [
            'member_id' => ['required', 'exists:members,id'],
            'date' => ['required', 'date'],
            'types' => ['required', 'array', 'min:1'],
            'types.*' => ['required', 'integer', 'in:1,2,3'],
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

        if ($this->editingId) {
            Meal::findOrFail($this->editingId)->delete();
        }

        foreach ($this->types as $type) {
            Meal::firstOrCreate(
                [
                    'member_id' => $this->member_id,
                    'date' => $this->date,
                    'type' => $type,
                ],
                [
                    'month_id' => $activeMonth?->id,
                    'quantity' => MealType::from((int) $type)->rate(),
                ]
            );
        }

        $this->dispatch('toast', message: $this->editingId ? 'Meal updated successfully.' : 'Meal(s) added successfully.');

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
        $this->types = [$meal->type->value];
    }

    public function cancelEdit()
    {
        $this->reset('editingId', 'member_id', 'types');
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

    public function deleteRow($memberId, $date)
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }
        Meal::where('member_id', $memberId)->where('date', $date)->delete();
        $this->dispatch('toast', message: 'All meals for this day deleted.');
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

        $activeMonth = $mess->activeMonth();

        $meals = DB::table('meals')
            ->join('members', 'meals.member_id', '=', 'members.id')
            ->join('users', 'members.user_id', '=', 'users.id')
            ->whereIn('meals.member_id', $memberIds)
            ->when($activeMonth, fn ($q) => $q->where('meals.month_id', $activeMonth->id))
            ->when($this->filterMemberId, fn ($q) => $q->where('meals.member_id', $this->filterMemberId))
            ->selectRaw("
                meals.member_id,
                meals.date,
                users.name as member_name,
                MAX(CASE WHEN meals.type = 1 THEN meals.quantity END) as breakfast_quantity,
                MAX(CASE WHEN meals.type = 2 THEN meals.quantity END) as lunch_quantity,
                MAX(CASE WHEN meals.type = 3 THEN meals.quantity END) as dinner_quantity,
                MAX(CASE WHEN meals.type = 1 THEN meals.id END) as breakfast_id,
                MAX(CASE WHEN meals.type = 2 THEN meals.id END) as lunch_id,
                MAX(CASE WHEN meals.type = 3 THEN meals.id END) as dinner_id
            ")
            ->groupBy('meals.member_id', 'meals.date', 'users.name')
            ->orderBy('meals.date', 'desc')
            ->orderBy('users.name')
            ->paginate(20);

        return view('livewire.meals.index', [
            'members' => $members,
            'mealTypes' => MealType::cases(),
            'meals' => $meals,
            'today' => Carbon::parse($this->date),
        ])->layout('layouts.app', ['title' => 'Meals - DIU Mess Management System']);
    }
}
