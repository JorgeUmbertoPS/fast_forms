<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Models\QuestionarioPergunta;
use App\Models\QuestionarioResposta;
use App\Models\Questionario as QuestionarioModel;

class Questionario extends Component
{
    public $questionario;

    public $respostas = [];

    public $respostas_radio = [];

    public $resposta_id;
   
    public function saveTexto()
    {
            // Definindo regras de validação específicas para cada resposta
       /* $this->validate([
            'respostas.*' => 'required',  // Valida que cada resposta seja uma string obrigatória de até 255 caracteres
        ], [
            'respostas.*.required' => 'Este campo é obrigatório.', // Mensagem personalizada para campos obrigatórios
            'respostas.*.string' => 'Este campo deve ser um texto.',
            'respostas.*.max' => 'O campo não pode ter mais que 255 caracteres.',
        ]);*/
        
       foreach ($this->respostas as $key => $resposta) {
            $resposta_salva = QuestionarioPergunta::find($key);
            $resposta_salva->resposta = $resposta;
            $resposta_salva->save();
        }

        /*
        foreach ($this->respostas_radio as $key => $resposta) {
            $resposta_salva = QuestionarioPergunta::find($key);
            $resposta_salva->resposta = $resposta;
            $resposta_salva->save();
        }*/

        session()->flash('message', 'Respostas salvas com sucesso!');

         $this->dispatch('$refresh');

    }

    public function saveRadio($pergunta_id, $resposta)
    {
        //dd($pergunta_id, $resposta);

        $resposta_salva = QuestionarioResposta::where('id', $resposta)->where('pergunta_id', $pergunta_id)->first();
        $pergunta = QuestionarioPergunta::where('id', $pergunta_id)->first();
        $pergunta->resposta = $resposta_salva->nome;
        //dd($resposta_salva->nome);
        $pergunta->save();

    }
 
    public function mount(Request $request)
    {
        $id = $request->id;
        // Carregar o questionário
        $this->questionario = QuestionarioModel::where('id', $id)->get();

        $respostas = QuestionarioPergunta::where('questionario_id', $id)->get();

        // Preenche o array de respostas com os valores salvos no banco de dados
        foreach ($respostas as $resposta) {
            $this->respostas[$resposta->id] = $resposta->resposta; // 'resposta_salva' é o campo com o valor no banco
            
        }

      //  dd($this->respostas);

    }

    public function render()
    {
        return view('livewire.questionario');
    }
}



