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
            $table->string('contratante_nome');
            $table->string('contratante_cnpj');
            $table->string('contratante_endereco')->nullable();
            $table->string('software_nome');
            $table->decimal('valor_mensal', 10, 2);
            $table->date('data_inicio');
            $table->string('cidade');
            $table->string('uf');
            $table->string('representante_contratada');
            $table->string('cnpj_contratada');
            $table->string('nome_contratada');
            $table->text('observacoes')->nullable();
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
