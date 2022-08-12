<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate(['email' => 'test@test.com'],[
            'name' => 'demetrius',
            'email' => 'test@test.com',
            'password' => 'password'
        ]);

        if($user->wasRecentlyCreated) {
            $user->assignRole('super-admin');
        }
    }
}
