<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('users')->insert([
        //     'name' => "Admin",
        //     'email' => "admin@admin.com",
        //     'password' => Hash::make('admin'),
        // ]);
        $user1 = User::create([
            'name' => "Admin",
            'email' => "admin@admin.com",
            'location' => "Cochabamba",
            'password' => Hash::make('admin'),
        ]);
        $user1->assignRole('super-admin');

        $user2 = User::create([
            'name' => "Luis",
            'email' => "luis@email.com",
            'location' => "La Paz",
            'password' => Hash::make('luis123'),
        ]);
        $user2->assignRole('oficial-de-despacho');

        $user3 = User::create([
            'name' => "Juan",
            'email' => "juan@email.com",
            'location' => "Oruro",
            'password' => Hash::make('juan123'),
        ]);
        $user3->assignRole('cliente');
    }
}
