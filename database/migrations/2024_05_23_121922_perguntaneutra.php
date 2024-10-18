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
        Schema::table('modalidades_roteiros', function (Blueprint $table) {
            $table->integer('pergunta_neutra')->default(0);
        });

        Schema::table('lacracoes_roteiros', function (Blueprint $table) {
            $table->integer('pergunta_neutra')->default(0);
        });

        //embarques_containers_checklist_modalidades

        Schema::table('embarques_containers_checklist_modalidades', function (Blueprint $table) {
            $table->integer('pergunta_neutra')->default(0);
        });

        //embarques_containers_lacracoes_respostas

        Schema::table('embarques_containers_lacracoes_respostas', function (Blueprint $table) {
            $table->integer('pergunta_neutra')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modalidades_roteiros', function (Blueprint $table) {
            $table->dropColumn('pergunta_neutra');
        });

        Schema::table('lacracoes_roteiros', function (Blueprint $table) {
            $table->dropColumn('pergunta_neutra');
        });

        Schema::table('embarques_containers_checklist_modalidades', function (Blueprint $table) {
            $table->dropColumn('pergunta_neutra');
        });

        Schema::table('embarques_containers_lacracoes_respostas', function (Blueprint $table) {
            $table->dropColumn('pergunta_neutra');
        });
    }
};
