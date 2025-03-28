<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //tabela de mascaras com campo de nome e mascara
        Schema::create('modelos_mascaras', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 60);
            $table->string('mascara', 60);
            $table->timestamps();
        });
        
        DB::table('modelos_mascaras')->insert([
            ['nome' => 'Texto', 'mascara' => 'text'],
            ['nome' => 'Número', 'mascara' => 'number'],
            ['nome' => 'Data', 'mascara' => 'date'],
            ['nome' => 'Hora', 'mascara' => 'time'],
            ['nome' => 'Placa Mercosul', 'mascara' => '***-****']
        ]);

        // campo id mascaras na tabela de modelo_perguntas

        Schema::table('modelo_perguntas', function (Blueprint $table) {
            $table->foreignId('id_mascara')->nullable()->constrained('modelos_mascaras');
        });

        //formularios_perguntas
        Schema::table('formularios_perguntas', function (Blueprint $table) {
            $table->foreignId('id_mascara')->nullable()->constrained('modelos_mascaras');
        });

        // questionarios_perguntas
        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->foreignId('id_mascara')->nullable()->constrained('modelos_mascaras');
        });

        // remover campo mascara da tabela modelo_perguntas o campo resposta_valor_tipo
        Schema::table('modelo_perguntas', function (Blueprint $table) {
            $table->dropColumn('resposta_valor_tipo');
        });

        // remover campo mascara da tabela formularios_perguntas o campo resposta_valor_tipo
        Schema::table('formularios_perguntas', function (Blueprint $table) {
            $table->dropColumn('resposta_valor_tipo');
        });

        // remover campo mascara da tabela questionarios_perguntas o campo resposta_valor_tipo
        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->dropColumn('resposta_valor_tipo');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('modelos_mascaras');

        Schema::table('modelo_perguntas', function (Blueprint $table) {
            $table->dropForeign(['id_mascara']);
            $table->dropColumn('id_mascara');
            $table->string('resposta_valor_tipo', 60);
        });

        Schema::table('formularios_perguntas', function (Blueprint $table) {
            $table->dropForeign(['id_mascara']);
            $table->dropColumn('id_mascara');
            $table->string('resposta_valor_tipo', 60);
        });

        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->dropForeign(['id_mascara']);
            $table->dropColumn('id_mascara');
            $table->string('resposta_valor_tipo', 60);
        });

    }
};
