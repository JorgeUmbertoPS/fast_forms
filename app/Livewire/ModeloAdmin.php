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

class ModeloAdmin extends Component
{


 
    public function mount(Request $request)
    {
        $id = $request->id;
        

    }


    public function render()
    {
        return view('livewire.modelo-admin');
    }
}



