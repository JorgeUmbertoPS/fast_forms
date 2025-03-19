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

        Schema::create('formularios', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedInteger('modelo_id');
            $table->unsignedInteger('empresa_id');
            $table->integer('criar_resumo')->default(0);
            $table->integer('gerar_plano_acao')->default(0);
            $table->integer('envia_email_etapas')->default(0);
            $table->integer('obriga_assinatura')->default(0);
            $table->integer('avisar_dias_antes')->default(0);
            $table->date('data_inicio')->nullable();
            $table->date('data_termino')->nullable();
            $table->string('status',1)->default('A')->comment('Ativo, Inativo');
            $table->timestamps();

            $table->foreign('modelo_id')->references('id')->on('modelo_formularios');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        }); 
        
        Schema::create('formularios_perguntas_blocos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('form_id');
            $table->unsignedInteger('empresa_id');
            $table->string('titulo',100)->nullable();
            $table->string('sub_titulo',100)->nullable();
            $table->string('descricao',100);
            $table->string('icon')->nullable();
            $table->integer('ordem');
            //instrucoes
            $table->text('instrucoes')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->foreign('form_id')->references('id')->on('formularios')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });

        Schema::create('formularios_perguntas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('resposta_tipo_id');
            $table->string('resposta_valor_tipo', 30)->nullable();
            $table->unsignedInteger('bloco_id');
            $table->unsignedInteger('form_id')->nullable();
            $table->date('data_termino')->nullable();;
            $table->unsignedInteger('user_id');
            $table->integer('ordem')->default(1);
            $table->unsignedInteger('pontuacao_id')->nullable();
            $table->integer('obriga_justificativa')->default(0);
            $table->string('justificativa', 100)->nullable();
            $table->integer('obriga_midia')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('bloco_id')->references('id')->on('formularios_perguntas_blocos')->onDelete('cascade');
            $table->foreign('form_id')->references('id')->on('formularios')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('resposta_tipo_id')->references('id')->on('modelo_resposta_tipos');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pontuacao_id')->references('id')->on('modelo_respostas_pontuacoes');
            
        });

        Schema::create('formularios_respostas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100)->nullable();
            $table->string('resposta')->nullable();
            $table->unsignedInteger('pergunta_id');
            $table->unsignedInteger('empresa_id');
            $table->string('icon')->nullable();
            $table->integer('ordem')->default(1);
            $table->timestamps();

            $table->foreign('pergunta_id')->references('id')->on('formularios_perguntas')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            

        });

        // ------------------------------- FKs ----------------------------------------------


     


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
