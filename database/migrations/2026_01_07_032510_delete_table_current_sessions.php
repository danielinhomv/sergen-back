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
        //eliminar la tabla current_sessions
        Schema::dropIfExists('current_sessions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
           Schema::create('current_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('control_id')->constrained('controls')->onDelete('cascade');
            $table->boolean('active')->default(true);            
        });
    }
};
