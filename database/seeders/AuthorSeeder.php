<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuthorSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Author',
            'email' => 'author@email.com',
            'password' => Hash::make('1234@Abcd'),
        ])->assignRole('Author');
    }
}
