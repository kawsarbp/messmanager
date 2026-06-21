<?php

namespace App\Livewire\Auth;

use App\Models\Member;
use App\Models\Mess;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $mess_code = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'mess_code' => ['required', 'string', 'exists:messes,code'],
        ];
    }

    protected function messages(): array
    {
        return [
            'mess_code.exists' => 'The mess code is invalid. Please check and try again.',
            'email.unique' => 'This email is already registered.',
        ];
    }

    public function register()
    {
        $this->validate();

        $mess = Mess::where('code', $this->mess_code)->first();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role_id' => 2,
        ]);

        Member::create([
            'mess_id' => $mess->id,
            'user_id' => $user->id,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('layouts.app', ['title' => 'Register - DIU Mess Management System']);
    }
}
