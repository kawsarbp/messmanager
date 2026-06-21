<?php

namespace App\Livewire\Members;

use App\Enums\Role;
use App\Enums\VisibilityStatus;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public function getMembersProperty()
    {
        $user = Auth::user();
        $mess = $user->member->mess;

        return $mess->members()
            ->with('user')
            ->get()
            ->sortByDesc(function ($member) use ($user) {
                $isManager = $member->user->role_id === Role::Manager;
                $isMe = $member->user_id === $user->id;
                return ($isManager ? 2 : 0) + ($isMe ? 1 : 0);
            })
            ->values();
    }

    public function toggleStatus(Member $member)
    {
        $user = Auth::user();

        if ($user->role_id !== Role::Manager || $member->user_id === $user->id) {
            return;
        }

        $member->update([
            'status' => $member->status === VisibilityStatus::Active
                ? VisibilityStatus::Inactive
                : VisibilityStatus::Active,
        ]);

        $status = $member->fresh()->status?->name ?? 'active';
        $this->dispatch('toast', message: "{$member->user->name} is now {$status}.");

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
        return view('livewire.members.index')
            ->layout('layouts.app', ['title' => 'Members - DIU Mess Management System']);
    }
}
