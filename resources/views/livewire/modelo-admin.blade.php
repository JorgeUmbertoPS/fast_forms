<div>
    @if (session()->has('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">Respostas salvas com sucesso.</span>

    </div>
    @endif

    <form wire:submit.prevent="saveText">
 

    </form>

    


</div>
