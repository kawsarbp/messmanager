<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Index extends Component
{
    public $name = '';
    public $email = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    protected function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        return $rules;
    }

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->new_password) {
            $data['password'] = Hash::make($this->new_password);
        }

        Auth::user()->update($data);

        $this->reset('new_password', 'new_password_confirmation');
        $this->dispatch('toast', message: 'Profile updated successfully.');
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
        return view('livewire.profile.index')
            ->layout('layouts.app', ['title' => 'Profile - DIU Mess Management System']);
    }
}
