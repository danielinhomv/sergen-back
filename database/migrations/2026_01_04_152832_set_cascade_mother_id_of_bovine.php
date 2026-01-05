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
        Schema::table('bovines', function (Blueprint $table) {
            $table->dropForeign(['mother_id']);

            $table->foreign('mother_id')
                ->references('id')
                ->on('bovines')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('bovines', function (Blueprint $table) {
            $table->dropForeign(['mother_id']);

            $table->foreign('mother_id')
                ->references('id')
                ->on('bovines')
                ->onDelete('set null');
        });
    }
};
