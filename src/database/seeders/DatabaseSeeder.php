<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        Storage::disk('public')->deleteDirectory('chat_images');
        Storage::disk('public')->makeDirectory('chat_images');

        $this->call([
            CategoriesTableSeeder::class,
            DownloadImagesSeeder::class,
            UsersTableSeeder::class,  
            AddressesTableSeeder::class, 
            ItemsTableSeeder::class,
            UpdateIsPurchasedSeeder::class,
        ]);
    }
}
