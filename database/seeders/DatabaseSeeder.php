<?php

namespace Database\Seeders;

use App\Models\Bovine;
use App\Models\Bull;
use App\Models\Control;
use App\Models\Control_bovine;
use App\Models\Current_session;
use App\Models\Insemination;
use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//           $user = User::factory()->create([
//             'name' => 'admin',
//            'password' => Hash::make('password'),
//          ]);

//         // Crear una propiedad asociada al usuario
//         $property = Property::create([
//             'name' => 'Hacienda El Progreso',
//             'place' => 'santa cruz',
//             'phone_number' => '73456787',
//             'owner_name' => 'danielinho',
//             'user_id' => $user->id
//         ]);

//         // Crear un control asociado a la propiedad
//         $control = Control::create([
//             'status' => 'in progress',   // o 'finished'
//             'property_id' => $property->id,
//         ]);

//         Current_session::create([
//             'user_id'=>$user->id,
//             'property_id' => $property->id,
//             'active'=> true
//         ]);
//  // Crear algunos bovinos asociados a la propiedad
//         $bovine1 = Bovine::create([
//             'mother_id' => null,
//             'serie' => 'BOV001',
//             'rgd' => 'RGD001',
//             'sex' => 'female',
//             'weight' => 350,
//             'birthdate' => Carbon::now()->subMonths(12),
//             'property_id' => $property->id,
//         ]);

//         $bovine2 = Bovine::create([
//             'mother_id' => $bovine1->id,
//             'serie' => 'BOV002',
//             'rgd' => 'RGD002',
//             'sex' => 'male',
//             'weight' => 360,
//             'birthdate' => Carbon::now()->subMonths(10),
//             'property_id' => $property->id,
//         ]);

        // Crear relación control_bovine
        $controlBovine1 = Control_bovine::create([
            'bovine_id' => 7,
           'control_id' => 18,
        ]);

        // $controlBovine2 = Control_bovine::create([
        //     'bovine_id' => $bovine2->id,
        //     'control_id' => $control->id,
        // ]);

        // // Crear algunos toros (Bull) para inseminaciones
        //  $bull1 = Bull::create([
        //      'name' => 'Toro A',
        //      'user_id' => 1,
        //  ]);

        // $bull2 = Bull::create([
        //     'name' => 'Toro B',
        //     'user_id' => $user->id,
        // ]);

        // // Crear inseminaciones
        // Insemination::create([
        //     'body_condition_score' => 8,
        //     'heat_quality' => 'well',
        //     'observation' => 'Celo normal',
        //     'others' => null,
        //     'date' => Carbon::now()->subDays(3),
        //     'control_bovine_id' => $controlBovine1->id,
        //     'bull_id' => $bull1->id,
        // ]);

        // Insemination::create([
        //     'body_condition_score' => 7.5,
        //     'heat_quality' => 'regular',
        //     'observation' => 'Celo débil',
        //     'others' => null,
        //     'date' => Carbon::now()->subDays(2),
        //     'control_bovine_id' => $controlBovine2->id,
        //     'bull_id' => $bull2->id,
        // ]);


    }
}
