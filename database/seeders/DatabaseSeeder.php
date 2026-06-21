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
        $users = [
            [
                'name' => 'Kawsar Ahmed',
                'email' => 'kawsar@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => Role::Manager,
            ],
            [
                'name' => 'Nahid Hasan',
                'email' => 'nahid@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => Role::Member,
            ],
            [
                'name' => 'Tofael Ahmed',
                'email' => 'tofael@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => Role::Member,
            ],
            [
                'name' => 'Hossain Rabbi',
                'email' => 'rabbi@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => Role::Member,
            ],
            [
                'name' => 'Hridoy Khan',
                'email' => 'hirdoy@gmail.com',
                'password' => bcrypt('password'),
                'role_id' => Role::Member,
            ],
        ];

        foreach ($users as $data) {
            $user = User::create($data);

            Member::create([
                'mess_id' => $mess->id,
                'user_id' => $user->id,
            ]);
        }
    }
}
