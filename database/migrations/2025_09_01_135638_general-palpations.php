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
        Schema::create('general_palpations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bovino_id')->constrained('bovines')->onDelete('cascade');
            $table->enum('status', ['pregnant', 'empty', 'discard', 'abort']);
            $table->text('observation')->nullable();
            $table->date('date');
            $table->foreignId('bovine-controls_id')->constrained('bovine-controls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_palpations');
    }
};
