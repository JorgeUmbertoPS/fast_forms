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
        // alterar campo status para inteiro
        Schema::table('questionarios', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('questionarios', function (Blueprint $table) {
            $table->integer('status')->default(1);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        // reverter alteração do campo status para string
        Schema::table('questionarios', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('status')->default('1');
        });
    }
};
