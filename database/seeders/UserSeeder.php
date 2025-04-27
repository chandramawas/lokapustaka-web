<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'John Doe',
            'email' => '17230726@bsi.ac.id',
            'password' => Hash::make('root'),
            'gender' => 'Laki-Laki',
            'birthdate' => '2000-01-01',
            'email_verified_at' => Carbon::now(),
        ]);

        Subscription::create([
            'user_id' => $user->id,
            'type' => 'bulanan',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);
        Subscription::create([
            'user_id' => $user->id,
            'type' => 'bulanan',
            'start_date' => now()->subMonths(2),
            'end_date' => now()->subMonth(),
            'is_active' => false,
        ]);
    }
}
