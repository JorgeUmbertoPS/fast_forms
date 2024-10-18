<?php



use App\Models\Embarque;
use App\Livewire\CreateQuestionario;
use App\Livewire\RelatorioChecklist;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmbarqueController;

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


Route::get('/gerar-pdf-conta', [App\Http\Controllers\EmbarqueController::class, 'gerarPdf'])->name('conta.gerar-pdf');

Route::get('embarque-report/{record}', [App\Http\Controllers\EmbarqueController::class, 'report'])->name('embarque.report');