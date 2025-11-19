<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        $properties = [];
        $userIds = DB::table('users')->pluck('id')->toArray();
        $places = ['Santa Cruz', 'Trinidad (Beni)', 'Yacuiba (Tarija)', 'Riberalta (Beni)', 'Montero (Santa Cruz)'];

        for ($i = 0; $i < 20; $i++) {
            $ownerName = $faker->name();
            $properties[] = [
                'name' => $faker->randomElement(['Estancia', 'Finca', 'Hacienda']) . ' ' . $faker->lastName(),
                'place' => $faker->randomElement($places),
                'phone_number' => '7' . $faker->numberBetween(0000000, 9999999), // Celular boliviano
                'owner_name' => $ownerName,
                'user_id' => $faker->randomElement($userIds),
            ];
        }

        DB::table('properties')->insert($properties);
    }
}