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
        Schema::create('bovine-controls',function(Blueprint $table){
            $table->id();
            $table->foreignId('bovine_id')->constrained('bovines')->onDelete('cascade');
            $table->foreignId('control_id')->constrained('controls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
