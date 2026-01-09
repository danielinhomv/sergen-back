<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE births 
            MODIFY type_of_birth 
            ENUM('premeture', 'abort', 'stillbirth', 'normal') 
            NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('births', function (Blueprint $table) {
            //
            DB::statement("
            ALTER TABLE births 
            MODIFY type_of_birth 
            ENUM('premeture', 'abort', 'stillbirth') 
            NOT NULL
        ");
        });
    }
};
