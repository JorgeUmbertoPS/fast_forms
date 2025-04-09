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
        // logo_base_64 do tipo text na tabela de empresas se nao existir
        if (!Schema::hasColumn('empresas', 'logo_base_64')) {
            Schema::table('empresas', function (Blueprint $table) {
                $table->text('logo_base_64')->nullable();
            });
        }


        // campo midia na tabela de questionarios_perguntas se nao existir
        if (!Schema::hasColumn('questionarios_perguntas', 'midia')) {
            Schema::table('questionarios_perguntas', function (Blueprint $table) {
                $table->string('midia')->nullable();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        // logo_base_64 do tipo text na tabela de empresas
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('logo_base_64');
        });

        // campo midia na tabela de questionarios_perguntas
        Schema::table('questionarios_perguntas', function (Blueprint $table) {
            $table->dropColumn('midia');
        });
    }
};
