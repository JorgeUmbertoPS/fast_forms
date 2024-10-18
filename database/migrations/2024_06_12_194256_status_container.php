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
            //$table->integer('status')->default(0)->comment('0 - Em avaliação, 1 - Aprovado, 2 - Reprovado');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('embarques_containers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
