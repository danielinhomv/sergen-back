<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            UPDATE confirmatory_ultrasounds 
            SET status = 'empty' 
            WHERE status = 'refuge'
        ");

        DB::statement("
            ALTER TABLE confirmatory_ultrasounds 
            MODIFY status ENUM('pregnant', 'empty', 'discart') NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE confirmatory_ultrasounds 
            MODIFY status ENUM('pregnant', 'empty', 'refuge', 'discart') NOT NULL
        ");
    }
};
