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
        Schema::create('bovines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mother_id')->nullable();
            $table->string('serie');
            $table->string('rgd');
            $table->enum('sex', ['male', 'female']);
            $table->double('weight');
            $table->date('birthdate');
            $table->foreign('mother_id')->references('id')->on('bovines'); //activar eliminacion en cascada
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bovines');
    }
};
