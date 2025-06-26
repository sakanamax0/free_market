<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'test@gmail.com'], 
            [
                'name' => '一般ユーザー',
                'email' => 'test@gmail.com',
                'password' => Hash::make('password'), 
            ]
        );
    }
}
