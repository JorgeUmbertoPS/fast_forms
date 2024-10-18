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
        //criar campos embalagens no embarque
        Schema::table('embarques', function (Blueprint $table) {
            $table->text('embalagens')->nullable();
        });

        // criar campos oic e lotes em containers
        Schema::table('embarques_containers', function (Blueprint $table) {
            $table->text('oic')->nullable();
            $table->text('lotes')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('embarques', function (Blueprint $table) {
            $table->dropColumn('embalagens');
        });

        Schema::table('embarques_containers', function (Blueprint $table) {
            $table->dropColumn('oic');
            $table->dropColumn('lotes');
        });
    }
};
