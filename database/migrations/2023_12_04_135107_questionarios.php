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

        Schema::create('questionarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('empresa_id');
            $table->string('nome');
            $table->integer('criar_resumo')->default(0);
            $table->integer('envia_email_etapas')->default(0);
            $table->integer('obriga_assinatura')->default(0);
            $table->integer('avisar_dias_antes')->default(0);
            $table->date('data_inicio')->nullable();
            $table->date('data_termino')->nullable();
            $table->integer('status')->default(1);
            $table->text('resumo')->nullable();
            $table->string('arquivo_pdf')->nullable();
            $table->timestamps();
            $table->unsignedInteger('form_id')->nullable();
            $table->foreign('form_id')->references('id')->on('formularios')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');

        });  
        
        Schema::create('questionarios_planos_acao', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('questionario_id');
            $table->unsignedInteger('empresa_id');
            $table->text('causa')->nullable();
            $table->text('acao_corretiva')->nullable();
            $table->integer('user_id');
            $table->date('data_planejada');
            $table->integer('status')->default(1);
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('questionario_id')->references('id')->on('questionarios')->onDelete('cascade');
        }); 

        Schema::create('questionarios_perguntas_blocos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('questionario_id');
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('form_bloco_id')->nullable();
            $table->string('titulo',100)->nullable();
            $table->string('sub_titulo',100)->nullable();
            $table->string('descricao',100);
            $table->integer('ordem');
            $table->timestamps();
            $table->unsignedInteger('form_id')->nullable();
            $table->string('instrucoes', 100)->nullable();
            $table->string('observacoes', 100)->nullable();
            $table->string('icon')->nullable();

            $table->foreign('form_id')->references('id')->on('formularios')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('questionario_id')->references('id')->on('questionarios')->onDelete('cascade');
            $table->foreign('form_bloco_id')->references('id')->on('formularios_perguntas_blocos')->onDelete('cascade');
            
        }); 

        Schema::create('questionarios_perguntas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('pergunta_id')->nullable();
            $table->string('pergunta_nome', 100);
            $table->string('resposta')->nullable();
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('resposta_tipo_id');
            $table->string('resposta_valor_tipo', 30)->nullable();
            $table->unsignedInteger('bloco_id');
            $table->unsignedInteger('questionario_id');
            $table->date('data_termino')->nullable();;
            $table->integer('user_id');
            $table->integer('status')->default(1);
            $table->timestamp('data_resposta')->nullable();;
            $table->integer('ordem')->default(1);
            $table->timestamps();
            $table->unsignedInteger('form_id')->nullable();        

            $table->unsignedInteger('pontuacao_id')->nullable();
            $table->integer('obriga_justificativa')->default(0);
            $table->string('justificativa', 100)->nullable();
            $table->integer('obriga_midia')->default(0);

            $table->foreign('form_id')->references('id')->on('formularios')->onDelete('cascade');
            $table->foreign('bloco_id')->references('id')->on('questionarios_perguntas_blocos');
            $table->foreign('questionario_id')->references('id')->on('questionarios')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('resposta_tipo_id')->references('id')->on('modelo_resposta_tipos');
            $table->foreign('pergunta_id')->references('id')->on('formularios_perguntas')->onDelete('cascade');


        });

        Schema::create('questionarios_respostas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100)->nullable();
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('pergunta_id');
            $table->string('icon')->nullable();
            $table->timestamps();
            $table->foreign('pergunta_id')->references('id')->on('questionarios_perguntas')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');

        });

        Schema::create('questionarios_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('qst_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->string('user_tipo')->default('N')->comment('N-Normal, A-Admin');
            $table->integer('recebe_email')->default(0);
            $table->timestamps();

            $table->foreign('qst_id')->references('id')->on('questionarios')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('questionarios_perguntas_midias', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('empresa_id');
            $table->unsignedInteger('respota_id');
            $table->string('file_name', 100)->nullable();
            $table->timestamps();

            $table->foreign('respota_id')->references('id')->on('questionarios_perguntas')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
