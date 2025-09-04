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
        Schema::create('pre-sincronizations', function (Blueprint $table) {
            $table->id();
            $table->string('reproductive_vaccine')->nullable();
            $table->string('sincrogest_product')->nullable();
            $table->string('antiparasitic_product')->nullable();
            $table->boolean('vitamins_and_minerals')->default(false);
            $table->date('application_date');
            $table->foreignId('bovine-controls_id')->constrained('bovine-controls')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pre-sincronizations');
    }
};
