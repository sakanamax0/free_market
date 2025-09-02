<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Address;
use App\Models\User;

class AddressesTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            switch ($user->email) {
                case 'test1@gmail.com':
                    $zipcode = '5870041';
                    $details = '堺市美原区';
                    $building ='美原荘1-2';
                    break;
                case 'test2@gmail.com':
                    $zipcode = '6340004';
                    $details = '奈良市登美ヶ丘';
                    $building = '登美ヶ丘マンション101';
                    break;
                case 'test3@gmail.com':
                    $zipcode = '1500001';
                    $details = '東京都渋谷区神宮前';
                    $building = '原宿ハイツ201';
                    break;
                default:
                    $zipcode = null;
                    $details = null;
                    $building = null;
            }

            Address::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'zipcode' => $zipcode,
                    'details' => $details,
                    'building' => $building,
                ]
            );
        }
    }
}
