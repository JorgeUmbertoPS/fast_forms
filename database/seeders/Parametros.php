<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Parametros extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // seeder que preenche os parametrso com foto_width = 200px e foto_height = 200px
        \App\Models\ParametroSistema::create([
            'foto_width' => '200px',
            'foto_height' => '200px',
        ]);
    }
}
