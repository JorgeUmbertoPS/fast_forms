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
            $table->integer('pergunta_finaliza_negativa')->default(0);
        });

        Schema::table('lacracoes_roteiros', function (Blueprint $table) {
            $table->integer('pergunta_finaliza_negativa')->default(0);
        });

        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->integer('pergunta_finaliza_negativa')->default(0);
        });

        Schema::table('embarques_containers_checklist_respostas', function (Blueprint $table) {
            $table->integer('pergunta_finaliza_negativa')->default(0);
        });

        Schema::table('embarques_containers_checklist_modalidades', function (Blueprint $table) {
            $table->integer('pergunta_finaliza_negativa')->default(0);
        });

        //embarques_containers_lacracoes_respostas

        Schema::table('embarques_containers_lacracoes_respostas', function (Blueprint $table) {
            $table->integer('pergunta_finaliza_negativa')->default(0);
        });

        // criar campo para status do container
        Schema::table('embarques_containers', function (Blueprint $table) {
            $table->integer('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modalidades_roteiros', function (Blueprint $table) {
            $table->dropColumn('pergunta_finaliza_negativa');
        });

        Schema::table('lacracoes_roteiros', function (Blueprint $table) {
            $table->dropColumn('pergunta_finaliza_negativa');
        });

        Schema::table('embarques_containers_checklist_modalidades', function (Blueprint $table) {
            $table->dropColumn('pergunta_finaliza_negativa');
        });

        Schema::table('embarques_containers_lacracoes_respostas', function (Blueprint $table) {
            $table->dropColumn('pergunta_finaliza_negativa');
        });

        Schema::table('embarques_containers_checklist_respostas', function (Blueprint $table) {
            $table->dropColumn('pergunta_finaliza_negativa');
        });

        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->dropColumn('pergunta_finaliza_negativa');
        });

        Schema::table('embarques_containers', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
