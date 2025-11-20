<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // El orden es crucial debido a las llaves foráneas
        $this->call([
            UserSeeder::class,
            PropertySeeder::class,
            BovineSeeder::class,
            ControlAndBreedingSeeder::class,
            // Aquí puedes agregar un seeder para 'current_sessions' si lo necesitas,
            // pero es opcional ya que es una tabla de estado.
        ]);
    }
}