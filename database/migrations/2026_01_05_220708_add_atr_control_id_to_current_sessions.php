<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('current_sessions', function (Blueprint $table) {
            //add control_id column after property_id
            $table->unsignedBigInteger('control_id')->nullable()->after('property_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('current_sessions', function (Blueprint $table) {
            //
            $table->dropColumn('control_id');
        });
    }
};
