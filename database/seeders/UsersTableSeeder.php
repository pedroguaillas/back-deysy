<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Sonnia Freire',
            'user' => 'Jaque',
            'rol' => 'asesor',
            'password' => Hash::make('Solotu')
        ]);
    }
}
