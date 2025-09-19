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
        Schema::create('births',function(Blueprint $table){
            $table->id();
            $table->date('birthdate');
            $table->enum('sex',['macho','hembra']);
            $table->double('birth_weight');
            $table->string('rgd');
            $table->enum('type_of_birth',['premeture','abort','stillbirth']);
            $table->foreignId('control_bovine_id')->constrained('control_bovines')->onDelete('cascade');
            $table->foreignId('bull_id')->constrained('bulls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('births');
    }
};
