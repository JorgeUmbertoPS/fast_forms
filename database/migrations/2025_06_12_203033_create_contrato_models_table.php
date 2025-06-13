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
        Schema::create('contratos_saas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contratante_id')->constrained('empresas')->onDelete('cascade');
            $table->string('software_nome')->default('Fast Forms');
            $table->decimal('valor_mensal', 10, 2);
            $table->date('data_inicio');
            $table->text('observacoes')->nullable();
            $table->integer('qtd_licencas')->default(0)->nullable();
            $table->unsignedBigInteger('plano_id')->nullable();
            $table->foreign('plano_id')->references('id')->on('empresas_planos');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();    
            //empresa_id
            $table->unsignedBigInteger('empresa_id')->default(0);
            $table->foreign('empresa_id')->references('id')->on('empresas');        
            //
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos_saas');
    }
};
