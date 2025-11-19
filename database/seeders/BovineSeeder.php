<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Schema; // Necesario para deshabilitar FK

class BovineSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        $propertyIds = DB::table('properties')->pluck('id')->toArray();
        $bovines = [];
        $motherIds = [];
        
        // --- 1. Crear Bovinos Adultos (potential mothers) ---
        for ($i = 1; $i <= 60; $i++) {
            $birthdate = $faker->dateTimeBetween('-8 years', '-2 years');
            $sex = $faker->randomElement(['male', 'female']);
            
            $bovines[] = [
                'id' => $i,
                'mother_id' => null,
                'serie' => 'B-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'rgd' => 'BO' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'sex' => $sex,
                'weight' => $faker->randomFloat(2, 350, 700),
                'birthdate' => $birthdate->format('Y-m-d'),
                'property_id' => $faker->randomElement($propertyIds),
            ];
            
            if ($sex === 'female') {
                $motherIds[] = $i;
            }
        }

        // --- 2. Crear Crías (dependientes de las madres) ---
        for ($i = 61; $i <= 100; $i++) {
            $motherId = $faker->randomElement($motherIds);
            $birthdate = $faker->dateTimeBetween('-1 year', 'now');
            
            // Buscar la propiedad de la madre en la colección $bovines que ya creamos
            // La madre siempre tendrá un ID menor a 61.
            $motherBovine = collect($bovines)->where('id', $motherId)->first();
            $propertyId = $motherBovine['property_id'] ?? $faker->randomElement($propertyIds); // Fallback por seguridad

            $bovines[] = [
                'id' => $i,
                'mother_id' => $motherId,
                'serie' => 'C-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'rgd' => 'BO' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'sex' => $faker->randomElement(['male', 'female']),
                'weight' => $faker->randomFloat(2, 100, 400),
                'birthdate' => $birthdate->format('Y-m-d'),
                'property_id' => $propertyId, // ¡Corregido!
            ];
        }
        
        // Insertar todos los bovinos de una sola vez
        Schema::disableForeignKeyConstraints();
        DB::table('bovines')->insert($bovines);
        Schema::enableForeignKeyConstraints();
    }
}