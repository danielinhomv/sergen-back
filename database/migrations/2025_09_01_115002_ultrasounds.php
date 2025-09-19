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
        Schema::create('ultrasounds', function (Blueprint $table) {
            $table->id();
            $table->boolean('vitamins_and_minerals')->default(false);
            $table->enum('status', ['pregnant', 'implanted', 'discarded', 'not_implanted']);
            $table->text('protocol_details')->nullable();
            $table->text('used_products_summary')->nullable();
            $table->string('work_team')->nullable();
            $table->date('date');
            $table->foreignId('control_bovine_id')->constrained('control_bovines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ultrasounds');
    }
};
