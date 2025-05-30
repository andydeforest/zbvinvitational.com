<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Andrew',
            'email' => 'test@test.com',
            'password' => bcrypt('test'),
        ]);
    }
}
