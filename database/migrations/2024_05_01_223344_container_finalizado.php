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
        Schema::table('embarques_containers', function (Blueprint $table) {
            $table->integer('finalizado')->default(0);
        });

        Schema::table('embarques_containers_checklist_respostas', function (Blueprint $table) {
            $table->string('visivel', 1)->default('S');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('embarques_containers', function (Blueprint $table) {
            $table->dropColumn('finalizado');
        });

        
    }
};
