<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use App\Models\QuestionarioPergunta;
use App\Models\QuestionarioResposta;
use App\Models\Questionario as QuestionarioModel;

class Questionario extends Component
{
    public $questionario;

    public $respostas = [];

    public $justificativas = [];

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
        dd($this->respostas);

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

    public function saveText($pergunta_id, $resposta)
    {
        $pergunta = QuestionarioPergunta::find($pergunta_id);
        
        if ($pergunta) {
            $pergunta->resposta = $resposta;
            $pergunta->save();
        }
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
            $this->justificativas[$resposta->id] = $resposta->justificativa ?? '';
        }

     
      //  dd($this->respostas);

    }

    use WithFileUploads;

    public $uploads = []; // arquivos temporários
    public $imagensBase64 = []; // imagens convertidas

    public function updatedUploads($value, $key)
    {
        // pega o ID da pergunta
        $perguntaId = explode('.', $key)[1];

        if (isset($this->uploads[$perguntaId])) {
            $file = $this->uploads[$perguntaId];

            $this->imagensBase64[$perguntaId] =
                'data:image/' . $file->getClientOriginalExtension() . ';base64,' .
                base64_encode(file_get_contents($file->getRealPath()));
        }
    }

    public function salvarJustificativa($perguntaId)
    {
        $justificativa = $this->justificativas[$perguntaId] ?? null;

        if ($justificativa !== null) {
            DB::table('questionarios_perguntas')->updateOrInsert(
                ['pergunta_id' => $perguntaId],
                ['justificativa' => $justificativa, 'updated_at' => now()]
            );
        }
    }

    public function render()
    {
        return view('livewire.questionario');
    }
}



