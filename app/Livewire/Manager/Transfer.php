<?php

namespace App\Livewire\Manager;

use App\Enums\Role;
use App\Enums\VisibilityStatus;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Transfer extends Component
{
    public function transfer($memberId)
    {
        if (Auth::user()->role_id !== Role::Manager) {
            return;
        }

        $currentUser = Auth::user();
        $mess = $currentUser->member->mess;

        $targetMember = Member::with('user')->where('id', $memberId)->where('mess_id', $mess->id)->firstOrFail();

        $currentUser->update(['role_id' => Role::Member]);
        $targetMember->user->update(['role_id' => Role::Manager]);

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'));
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

        $members = Member::with('user')
            ->where('mess_id', $mess->id)
            ->where('user_id', '!=', $user->id)
            ->where('status', VisibilityStatus::Active)
            ->get();

        return view('livewire.manager.transfer', compact('members'))
            ->layout('layouts.app', ['title' => 'Transfer Manager - DIU Mess Management System']);
    }
}
