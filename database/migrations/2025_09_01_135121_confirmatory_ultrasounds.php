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
        Schema::create('confirmatory_ultrasounds', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['pregnant', 'empty', 'refuge', 'discart']);
            $table->text('observation')->nullable();
            $table->date('date');
            $table->foreignId('control_bovine_id')->constrained('control_bovines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confirmatory_ultrasounds');
    }
};
