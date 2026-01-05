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
        Schema::table('births', function (Blueprint $table) {
            //crea bovine_id
            $table->unsignedBigInteger('bovine_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('births', function (Blueprint $table) {
            //
            $table->dropColumn('bovine_id');
        });
    }
};
