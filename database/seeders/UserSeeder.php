<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        $users = [];

        for ($i = 1; $i <= 5; $i++) {
            $name = $faker->name();
            $users[] = [
                'name' => $name,
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}