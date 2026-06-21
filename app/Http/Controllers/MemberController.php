<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\VisibilityStatus;
use App\Models\Member;
use Illuminate\Http\RedirectResponse;
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

    public function toggleStatus(Member $member): RedirectResponse
    {
        $user = Auth::user();

        if ($user->role_id !== Role::Manager) {
            abort(403);
        }

        $member->update([
            'status' => $member->status === VisibilityStatus::Active
                ? VisibilityStatus::Inactive
                : VisibilityStatus::Active,
        ]);

        return back();
    }
}
