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
        // tabela de parametros com os campos  foto_width foto_height,

        Schema::create('parametros', function (Blueprint $table) {
            $table->id();
            $table->string('foto_width')->default('200px');
            $table->string('foto_height')->default('200px');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('parametros');
    }
};
