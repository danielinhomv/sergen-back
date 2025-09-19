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
        Schema::create('inseminations', function (Blueprint $table) {
            $table->id();
            $table->float('body_condition_score');
            $table->enum('heat_quality', ['well', 'regular', 'bad'])->nullable();
            $table->text('observation')->nullable();
            $table->text('others')->nullable();
            $table->date('date');
            $table->foreignId('control_bovine_id')->constrained('control_bovines')->onDelete('cascade');
            $table->foreignId('bull_id')->constrained('bulls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inseminations');
    }
};
