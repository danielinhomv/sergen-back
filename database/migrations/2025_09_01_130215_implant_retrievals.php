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
        Schema::create('implant_retrievals', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['retrieved', 'lost']);
            $table->string('work_team')->nullable();
            $table->text('used_products_summary')->nullable();
            $table->date('  ');
            $table->foreignId('control_bovine_id')->constrained('control_bovines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('implant_retrievals');
    }
};
