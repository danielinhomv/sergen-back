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
            UPDATE ultrasounds 
            SET status = 'implanted' 
            WHERE status = 'not_implanted'
        ");

        DB::statement("
            ALTER TABLE ultrasounds 
            MODIFY status ENUM('pregnant', 'implanted', 'discarded') 
            NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE ultrasounds 
            MODIFY status ENUM('pregnant', 'implanted', 'discarded', 'not_implanted') 
            NOT NULL
        ");
    }
};
