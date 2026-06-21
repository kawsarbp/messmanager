<?php

namespace App\Livewire\Auth;

use App\Enums\VisibilityStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $member = Auth::user()->member;

            if ($member && $member->status === VisibilityStatus::Inactive) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                $this->addError('email', 'Your account has been deactivated. Contact the manager.');
                return;
            }

            session()->regenerate();
            $this->redirect(route('dashboard'), navigate: true);
            return;
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.app', ['title' => 'Login - DIU Mess Management System']);
    }
}
