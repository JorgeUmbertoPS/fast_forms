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
        // tabela de perfil
        Schema::create('perfis', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('descricao')->nullable();
            $table->integer('perfil_admin')->default(0);
            $table->integer('perfil_cliente')->default(0);
            $table->timestamps();
        });
        // tabela de permissao
        Schema::create('permissoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->string('descricao')->nullable();
            $table->string('slug');
            $table->timestamps();
        });
        // tabela de perfil_permissao
        Schema::create('perfil_permissao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perfil_id')->constrained('perfis')->onDelete('cascade');
            $table->foreignId('permissao_id')->constrained('permissoes')->onDelete('cascade');
            $table->unsignedInteger('empresa_id');
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
        });

        // fk de usuarios e perfis
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('perfil_id')->nullable()->constrained('perfis')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['perfil_id']);
            $table->dropColumn('perfil_id');
        });
        
        Schema::dropIfExists('perfil_permissao');
        Schema::dropIfExists('permissoes');
        Schema::dropIfExists('perfis');




    }
};
