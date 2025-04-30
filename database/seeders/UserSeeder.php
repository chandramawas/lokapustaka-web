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

        $user2 = User::create([
            'name' => 'Maulana Yusup Ibrahim',
            'email' => 'myusupibrahim00@gmail.com',
            'password' => Hash::make('sufajaya'),
            'gender' => 'Laki-Laki',
            'birthdate' => '2004-07-21',
            'email_verified_at' => Carbon::now(),
        ]);

        Subscription::create([
            'user_id' => $user2->id,
            'type' => 'tahunan',
            'start_date' => now()->subYear()->subMonth(),
            'end_date' => now()->subMonth(),
            'is_active' => false,
        ]);

        Subscription::create([
            'user_id' => $user->id,
            'type' => 'bulanan',
            'start_date' => now()->subMonths(2),
            'end_date' => now()->subMonth(),
            'is_active' => false,
        ]);

        Subscription::create([
            'user_id' => $user->id,
            'type' => 'bulanan',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'is_active' => true,
        ]);
    }
}
