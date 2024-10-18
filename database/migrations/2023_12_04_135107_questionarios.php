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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('ativo')->default(1);
            $table->timestamps();
        });

        Schema::create('transportadoras', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('ativo')->default(1);
            $table->timestamps();
        });  

        Schema::create('setores', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
        });        

        Schema::create('modalidades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('texto')->nullable();
            $table->timestamps();
        });

        Schema::create('modalidades_roteiros', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('modalidade_id');
            $table->integer('sequencia')->nullable();
            $table->string('descricao');
            $table->text('texto')->nullable();
            $table->foreign('modalidade_id')->references('id')->on('modalidades')->onDelete('cascade');
            $table->unsignedInteger('pergunta_dependente_id')->nullable();
            $table->text('texto_help')->nullable();
        }); 
        
        Schema::create('lacracoes', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->text('texto')->nullable();
            $table->timestamps();
        });

        Schema::create('lacracoes_roteiros', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('lacracao_id');
            $table->integer('sequencia')->nullable();
            $table->string('descricao');
            $table->text('texto')->nullable();
            $table->foreign('lacracao_id')->references('id')->on('lacracoes')->onDelete('cascade');
            $table->unsignedInteger('pergunta_dependente_id')->nullable();
            $table->text('texto_help')->nullable();
        });         

        Schema::create('questionarios', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->text('texto')->nullable();
            $table->timestamps();
        });

        Schema::create('questionarios_perguntas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('questionario_id');
            $table->string('pergunta_imagem', 1)->default('P'); 
            $table->string('pergunta'); 
            $table->text('texto')->nullable();           
            $table->foreign('questionario_id')->references('id')->on('questionarios')->onDelete('cascade');
            $table->unsignedInteger('pergunta_dependente_id')->nullable();
            $table->text('texto_help')->nullable();
        });              

        Schema::create('embarques', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cliente_id')->nullable();
            $table->unsignedInteger('transportadora_id')->nullable();
            $table->unsignedInteger('em_edicao_usu_id')->nullable();
            $table->integer('em_edicao')->default(1);
            $table->string('contrato')->nullable();    
            $table->string('oic')->nullable();          
            $table->date('data')->nullable();        
            $table->string('booking')->nullable(); 
            $table->string('navio')->nullable(); 
            $table->integer('total_sacas')->default(0)->nullable();
            $table->string('status_embarque', 1)->default('B')->comment('L - Liberado, B - Bloqueado, F - Finalizado');
            $table->unsignedInteger('user_fechamento_id')->nullable(); 
            $table->string('tipo', 1)->default('N')->comment('N - Novo, A - Adiantamento'); 
            $table->string('arquivo_pdf')->nullable();          
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('transportadora_id')->references('id')->on('transportadoras');
            $table->foreign('em_edicao_usu_id')->references('id')->on('users');
            $table->foreign('user_fechamento_id')->references('id')->on('users');
        });  

        Schema::create('embarques_lotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('embarque_id');
            $table->string('lote')->nullable();
            $table->foreign('embarque_id')->references('id')->on('embarques')->onDelete('cascade');
        });

        Schema::create('embarques_oics', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('embarque_id');
            $table->string('oic')->nullable();
            $table->foreign('embarque_id')->references('id')->on('embarques')->onDelete('cascade');
        });

        Schema::create('embarques_containers', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('embarque_id');
            $table->unsignedInteger('questionario_id');
            $table->unsignedInteger('lacracao_id');
            $table->string('container')->nullable();
            $table->foreign('embarque_id')->references('id')->on('embarques')->onDelete('cascade');
            $table->foreign('questionario_id')->references('id')->on('questionarios');
            $table->foreign('lacracao_id')->references('id')->on('lacracoes');
        });  
        
        Schema::create('embarques_containers_modalidades', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('container_id')->nullable();
            $table->unsignedInteger('modalidade_id')->nullable();
            $table->foreign('container_id')->references('id')->on('embarques_containers')->onDelete('cascade');
            $table->foreign('modalidade_id')->references('id')->on('modalidades');
        }); 
       
        Schema::create('embarques_containers_checklist_respostas', function (Blueprint $table) {
            $table->id();

            $table->string('pergunta')->nullable();
            $table->string('texto')->nullable();
            $table->string('resposta')->nullable();
            $table->string('status', 1)->default('A')->comment('Aberta, Finalizada');
            $table->string('pergunta_imagem', 1)->default('P'); 
            $table->integer('sequencia')->defualt(1);
            
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('embarques_containers_id')->nullable();            
            $table->unsignedInteger('questionario_id')->nullable();
            $table->unsignedInteger('questionario_pergunta_id')->nullable();
            $table->unsignedInteger('embarque_id');

            $table->foreign('questionario_id')->references('id')->on('questionarios');
            $table->foreign('questionario_pergunta_id')->references('id')->on('questionarios_perguntas');
            $table->foreign('embarques_containers_id')->references('id')->on('embarques_containers')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('embarque_id')->references('id')->on('embarques')->onDelete('cascade');
            $table->unsignedInteger('pergunta_dependente_id')->nullable();

        });  

        Schema::create('embarques_containers_checklist_modalidades', function (Blueprint $table) {
            $table->id();

            $table->string('descricao')->nullable();
            $table->string('texto')->nullable();
            $table->string('imagem')->nullable();
            $table->string('status', 1)->default('A')->comment('Aberta, Finalizada');
            $table->integer('sequencia')->defualt(1);

            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('embarques_containers_id')->nullable();            
            $table->unsignedInteger('modalidade_id')->nullable();
            $table->unsignedInteger('modalidade_roteiro_id')->nullable();
            $table->unsignedInteger('embarque_id');

            $table->foreign('modalidade_id')->references('id')->on('modalidades');
            $table->foreign('modalidade_roteiro_id')->references('id')->on('modalidades_roteiros');            
            $table->foreign('embarques_containers_id')->references('id')->on('embarques_containers')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('embarque_id')->references('id')->on('embarques')->onDelete('cascade');
            $table->unsignedInteger('pergunta_dependente_id')->nullable();
        }); 
        
        Schema::create('embarques_containers_lacracoes_respostas', function (Blueprint $table) {
            $table->id();

            $table->string('descricao')->nullable();
            $table->string('texto')->nullable();
            $table->string('imagem')->nullable();
            $table->string('resposta')->nullable();
            $table->string('status', 1)->default('A')->comment('Aberta, Finalizada');

            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('embarques_containers_id')->nullable();            
            $table->unsignedInteger('lacracao_id')->nullable();
            $table->unsignedInteger('lacracao_roteiro_id')->nullable();
            $table->unsignedInteger('embarque_id');
            $table->integer('sequencia')->defualt(1);

            $table->foreign('lacracao_id')->references('id')->on('lacracoes');
            $table->foreign('embarques_containers_id')->references('id')->on('embarques_containers')->onDelete('cascade');;
            $table->foreign('lacracao_roteiro_id')->references('id')->on('lacracoes_roteiros')->onDelete('cascade');;
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('embarque_id')->references('id')->on('embarques')->onDelete('cascade');
            $table->unsignedInteger('pergunta_dependente_id')->nullable();
        });        

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('setor_id')->references('id')->on('setores');
        });

        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->foreign('pergunta_dependente_id')->references('id')->on('questionarios_perguntas');
        });

        Schema::table('modalidades_roteiros', function (Blueprint $table) {
            $table->foreign('pergunta_dependente_id')->references('id')->on('modalidades_roteiros');
        });

        Schema::table('lacracoes_roteiros', function (Blueprint $table) {
            $table->foreign('pergunta_dependente_id')->references('id')->on('lacracoes_roteiros');
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
