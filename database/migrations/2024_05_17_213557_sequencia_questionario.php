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
        //colocar o campo sequencia na tabela de perguntas dos questionarios
        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->integer('sequencia')->nullable();
        });

        //cria o campo de softdelete
        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('questionarios', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->dropColumn('sequencia');
        });

        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('questionarios', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
