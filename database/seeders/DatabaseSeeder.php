<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Member;
use App\Models\Mess;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $mess = Mess::create([
            'name' => 'DIU Main Mess',
            'code' => 'DIU2026',
            'address' => 'Dhaka International University, Dhaka',
        ]);

        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role_id' => Role::Manager,
        ]);

        Member::create([
            'mess_id' => $mess->id,
            'user_id' => $user->id,
        ]);
    }
}
