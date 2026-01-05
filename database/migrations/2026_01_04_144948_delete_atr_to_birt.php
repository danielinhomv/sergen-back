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
            $table->dropColumn('birthdate');
            $table->dropColumn('sex');
            $table->dropColumn('birth_weight');
            $table->dropColumn('rgd');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('births', function (Blueprint $table) {
            $table->date('birthdate');
            $table->enum('sex', ['macho', 'hembra']);
            $table->double('birth_weight');
            $table->string('rgd');
        });
    }
};
