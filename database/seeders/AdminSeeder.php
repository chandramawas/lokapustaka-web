<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Lokapustaka',
            'email' => '17230726@bsi.ac.id',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('root'),
            'role' => 'admin',
            'gender' => 'Laki-Laki',
            'birthdate' => '2000-01-01',
        ]);
    }
}
