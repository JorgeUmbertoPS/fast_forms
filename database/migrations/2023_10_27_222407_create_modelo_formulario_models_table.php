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
        Schema::create('empresas_planos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->integer('status')->default(1);
            $table->string('cnpj', 18)->nullable();
            $table->string('razao_social', 100)->nullable();
            $table->string('endereco')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('telefone', 15)->nullable();
            $table->string('logo')->nullable();
            
            //segmento
            $table->unsignedInteger('segmento_id')->nullable();
            $table->timestamps();
        });

        Schema::create('empresas_users', function (Blueprint $table) {
            $table->id();
            $table->integer('empresa_id')->nullable()->constrained('erp_empresas');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });  

        Schema::create('modelo_resposta_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('componente', 30);
            $table->timestamps();
        });

        Schema::create('segmentos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        Schema::create('modelo_respostas_pontuacoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->timestamps();
        });

        Schema::create('modelo_formularios', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->unsignedInteger('segmento_id');
            $table->unsignedInteger('empresa_id');
            $table->integer('versao')->default(1);
            $table->integer('versao_nova')->default(1);
            $table->integer('status')->default(0)->comment('0 - Não liberado para uso, 1 - Liberado para uso, 2 - Depreciado');
            $table->timestamps();

            $table->foreign('segmento_id')->references('id')->on('segmentos');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });

        Schema::create('modelo_perguntas_blocos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 100)->nullable();
            $table->string('sub_titulo', 100)->nullable();
            $table->string('descricao', 100);
            $table->string('icon')->nullable();
            $table->text('instrucoes')->nullable();
            $table->text('observacoes')->nullable();
            $table->unsignedInteger('modelo_id');
            $table->integer('ordem')->default(1);
            $table->unsignedInteger('empresa_id');
            $table->timestamps();

            $table->foreign('modelo_id')->references('id')->on('modelo_formularios')->cascadeOnDelete();
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });

        Schema::create('modelo_perguntas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->unsignedInteger('resposta_tipo_id');
            $table->unsignedInteger('modelo_id')->nullable();
            $table->unsignedInteger('bloco_id');
            $table->unsignedInteger('empresa_id');
            $table->integer('ordem')->default(1);
            $table->string('resposta_valor_tipo', 30)->nullable();
            $table->integer('obriga_justificativa')->default(0);
            $table->integer('obriga_midia')->default(0);

            $table->timestamps();

            $table->foreign('bloco_id')->references('id')->on('modelo_perguntas_blocos')->cascadeOnDelete();
            $table->foreign('modelo_id')->references('id')->on('modelo_formularios')->cascadeOnDelete();
            $table->foreign('resposta_tipo_id')->references('id')->on('modelo_resposta_tipos');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });

        Schema::create('modelo_respostas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100)->nullable();
            $table->string('resposta')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('pergunta_id');
            $table->unsignedInteger('empresa_id');
            $table->integer('ordem')->default(1);
            $table->timestamps();

            $table->foreign('pergunta_id')->references('id')->on('modelo_perguntas')->cascadeOnDelete();;
            
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });

        Schema::create('modelo_respostas_pontuacoes_items', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->unsignedInteger('ponto_id');
            $table->integer('valor')->default(0);
            $table->string('cor', 100);
            $table->timestamps();

            $table->foreign('ponto_id')->references('id')->on('modelo_respostas_pontuacoes');
        });

        //modelos x empresas
        Schema::create('modelo_formularios_empresas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('modelo_id');
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('user_id');
            $table->integer('status')->default(0)->comment('0 - Não liberado para uso, 1 - Liberado para uso, 2 - Depreciado');
            $table->timestamps();

            $table->foreign('modelo_id')->references('id')->on('modelo_formularios');
            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('user_id')->references('id')->on('users');
        });

        // foregin keys

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });

        Schema::table('empresas', function (Blueprint $table) {
            $table->foreign('segmento_id')->references('id')->on('segmentos');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('modelo_formularios_users');
        Schema::dropIfExists('modelo_respostas');
        Schema::dropIfExists('modelo_perguntas');
        Schema::dropIfExists('modelo_resposta_tipos');
        Schema::dropIfExists('modelo_formularios');
        Schema::dropIfExists('empresas');
        Schema::dropIfExists('segmentos');
        Schema::dropIfExists('modelo_perguntas_blocos');
        Schema::dropIfExists('modelo_respostas_pontuacoes');
        Schema::dropIfExists('modelo_respostas_pontuacoes_items');
        Schema::dropIfExists('empresas_planos');

    }
};
