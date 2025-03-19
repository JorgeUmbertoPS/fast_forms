<?php

use App\Livewire\CreateQuestionario;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionarioRespostasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('questionario-report/{record}', [App\Http\Controllers\Questionario::class, 'report'])->name('questionario.report');