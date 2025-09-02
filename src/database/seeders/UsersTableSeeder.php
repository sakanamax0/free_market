<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
       
        $user1 = User::updateOrCreate(
            ['email' => 'test1@gmail.com'],
            [
                'name' => '一般ユーザー①',
                'email' => 'test1@gmail.com',
                'password' => Hash::make('password'),
                'profile_photo' => 'profile_photos/user1.gif',
            ]
        );

        
        $user2 = User::updateOrCreate(
            ['email' => 'test2@gmail.com'],
            [
                'name' => '一般ユーザー②',
                'email' => 'test2@gmail.com',
                'password' => Hash::make('password'),
                'profile_photo' => 'profile_photos/user2.jpg',
            ]
        );

      
        $user3 = User::updateOrCreate(
            ['email' => 'test3@gmail.com'],
            [
                'name' => '一般ユーザー③',
                'email' => 'test3@gmail.com',
                'password' => Hash::make('password'),
                'profile_photo' => 'profile_photos/user3.png',
            ]
        );
    }
}
