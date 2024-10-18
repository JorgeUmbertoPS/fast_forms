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
        // criar 3 campos na tabela de embarques_containers: user_id_questionario, user_id_modalidade, user_id_lacres, data_fechamento_questionario, data_fechamento_modalidade, data_fechamento_lacres
        Schema::table('embarques_containers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id_questionario')->nullable();
            $table->unsignedBigInteger('user_id_modalidade')->nullable();
            $table->unsignedBigInteger('user_id_lacres')->nullable();
            $table->dateTime('data_fechamento_questionario')->nullable();
            $table->dateTime('data_fechamento_modalidade')->nullable();
            $table->dateTime('data_fechamento_lacres')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // remover os campos criados
        Schema::table('embarques', function (Blueprint $table) {
            $table->dropColumn('user_id_questionario');
            $table->dropColumn('user_id_modalidade');
            $table->dropColumn('user_id_lacres');
            $table->dropColumn('data_fechamento_questionario');
            $table->dropColumn('data_fechamento_modalidade');
            $table->dropColumn('data_fechamento_lacres');
        });
    }
};
