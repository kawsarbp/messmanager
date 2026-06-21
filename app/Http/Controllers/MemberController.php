<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $mess = $user->member->mess;

        $members = $mess->members()
            ->with('user')
            ->get()
            ->sortByDesc(function ($member) use ($user) {
                $isManager = $member->user->role_id === Role::Manager;
                $isMe = $member->user_id === $user->id;
                return ($isManager ? 2 : 0) + ($isMe ? 1 : 0);
            })
            ->values();

        return view('members.index', compact('mess', 'members'));
    }
}
