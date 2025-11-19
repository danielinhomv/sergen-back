<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ControlAndBreedingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('es_ES');
        $propertyIds = DB::table('properties')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();
        $femaleBovineIds = DB::table('bovines')->where('sex', 'female')->pluck('id')->toArray();

        // Limitar a 50 bovinos para el control
        $bovinesForControl = array_slice($femaleBovineIds, 0, 50);

        // 1. Crear Toros (Bulls) - 10 registros
        $bulls = [];
        for ($i = 0; $i < 10; $i++) {
            $bulls[] = [
                'name' => $faker->randomElement(['Nelore', 'Braford', 'Guzerá']) . ' ' . $faker->randomLetter() . $faker->numberBetween(100, 999),
                'user_id' => $faker->randomElement($userIds),
            ];
        }
        DB::table('bulls')->insert($bulls);
        $bullIds = DB::table('bulls')->pluck('id')->toArray();

        // 2. Crear un Control principal
        DB::table('controls')->insert([
            'id' => 1,
            'property_id' => $faker->randomElement($propertyIds),
            'created_at' => now()->subMonths(3),
            'updated_at' => now(),
        ]);
        $controlId = 1;

        // 3. Crear Control_Bovines
        $controlBovines = [];
        $controlBovineMap = []; // Para mapear bovine_id a control_bovine_id
        $i = 1;
        foreach ($bovinesForControl as $bovineId) {
            $controlBovines[] = [
                'id' => $i,
                'bovine_id' => $bovineId,
                'control_id' => $controlId,
            ];
            $controlBovineMap[$bovineId] = $i;
            $i++;
        }
        DB::table('control_bovines')->insert($controlBovines);
        $controlBovineIds = DB::table('control_bovines')->pluck('id')->toArray();

        // 4. Crear Registros de Cría para los 50 bovinos
        foreach ($controlBovineIds as $cbId) {
            $inseminationDate = $faker->dateTimeBetween('-3 months', '-2 months');
            $presyncDate = (clone $inseminationDate)->modify('-15 days');
            $ultrasoundDate = (clone $inseminationDate)->modify('+30 days');
            $statusUltrasound = $faker->randomElement(['pregnant', 'implanted', 'not_implanted']);
            $retrievalDate = ($statusUltrasound === 'implanted') ? (clone $ultrasoundDate)->modify('+7 days') : null;
            $confirmatoryDate = ($statusUltrasound === 'pregnant') ? (clone $ultrasoundDate)->modify('+60 days') : null;
            $palpationDate = ($confirmatoryDate) ? (clone $confirmatoryDate)->modify('+60 days') : (clone $ultrasoundDate)->modify('+90 days');
            $birthDate = ($statusUltrasound === 'pregnant') ? (clone $inseminationDate)->modify('+280 days') : null;

            // a) Presincronizaciones
            DB::table('presincronizations')->insert([
                'reproductive_vaccine' => $faker->boolean(70) ? $faker->randomElement(['IBR-BVD', 'Leptospirosis']) : null,
                'sincrogest_product' => $faker->boolean(80) ? $faker->randomElement(['Sincrogest', 'Prostaglandina']) : null,
                'antiparasitic_product' => $faker->boolean(60) ? $faker->randomElement(['Ivermectina', 'Albendazole']) : null,
                'vitamins_and_minerals' => $faker->boolean(90),
                'application_date' => $presyncDate->format('Y-m-d'),
                'control_bovine_id' => $cbId,
            ]);

            // b) Inseminations
            DB::table('inseminations')->insert([
                'body_condition_score' => $faker->randomFloat(1, 3.0, 5.0),
                'heat_quality' => $faker->randomElement(['well', 'regular', 'bad']),
                'observation' => $faker->boolean(30) ? $faker->sentence(3) : null,
                'date' => $inseminationDate->format('Y-m-d'),
                'control_bovine_id' => $cbId,
                'bull_id' => $faker->randomElement($bullIds),
            ]);

            // c) Ultrasounds (Diagnóstico de Gestación/Implantación)
            DB::table('ultrasounds')->insert([
                'vitamins_and_minerals' => $faker->boolean(50),
                'status' => $statusUltrasound,
                'protocol_details' => 'Protocolo ' . $faker->word(),
                'used_products_summary' => $faker->sentence(4),
                'work_team' => $faker->randomElement(['Veterinario A', 'Veterinario B']),
                'date' => $ultrasoundDate->format('Y-m-d'),
                'control_bovine_id' => $cbId,
            ]);
            
            // d) Implant Retrievals (Si se implantó)
            if ($statusUltrasound === 'implanted' && $retrievalDate) {
                DB::table('implant_retrievals')->insert([
                    'status' => $faker->randomElement(['retrieved', 'lost']),
                    'work_team' => $faker->randomElement(['Equipo A', 'Equipo B']),
                    'used_products_summary' => $faker->sentence(4),
                    'date' => $retrievalDate->format('Y-m-d'),
                    'control_bovine_id' => $cbId,
                ]);
            }

            // e) Confirmatory Ultrasounds (Si está preñada)
            if ($statusUltrasound === 'pregnant' && $confirmatoryDate) {
                DB::table('confirmatory_ultrasounds')->insert([
                    'status' => $faker->randomElement(['pregnant', 'empty']),
                    'observation' => $faker->boolean(20) ? $faker->sentence(4) : null,
                    'date' => $confirmatoryDate->format('Y-m-d'),
                    'control_bovine_id' => $cbId,
                ]);
            }
            
            // f) General Palpations (Palpación general)
            DB::table('general_palpations')->insert([
                'status' => $faker->randomElement(['pregnant', 'empty', 'discard']),
                'observation' => $faker->boolean(20) ? $faker->sentence(4) : null,
                'date' => $palpationDate->format('Y-m-d'),
                'control_bovine_id' => $cbId,
            ]);

            // g) Births (Si se simuló el parto)
            if ($statusUltrasound === 'pregnant' && $birthDate && $birthDate < now()) {
                DB::table('births')->insert([
                    'birthdate' => $birthDate->format('Y-m-d'),
                    'sex' => $faker->randomElement(['macho', 'hembra']),
                    'birth_weight' => $faker->randomFloat(2, 30, 50),
                    'rgd' => 'BO' . $faker->numberBetween(10000, 99999),
                    'type_of_birth' => 'premeture', // Simplificando, usa 'premeture' como valor por defecto
                    'control_bovine_id' => $cbId,
                ]);
            }
        }
    }
}