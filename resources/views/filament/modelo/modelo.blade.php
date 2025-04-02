<x-filament::page>
    @if(!empty($dados['bloco']))
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            {{-- O grid irá usar 1 coluna no mobile, 2 no médio, e 4 no desktop --}}
            @foreach($dados['bloco'] as $bloco)
                <div class="p-4 bg-white shadow rounded">
                    <h2 class="text-lg font-semibold text-center"> {{ $bloco['nome'] }}</h2>

                    @if (!empty($bloco['perguntas']))
                        {{-- Aplicando grid diretamente nas perguntas --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            @foreach($bloco['perguntas'] as $pergunta)
                                <div class="bg-white shadow rounded p-4">
                                    <strong class="text-center block">{{ $pergunta['nome'] }}</strong>  <br>
                                    Tipo de Resposta: {{ $pergunta['resposta_tipo'] }} <br>
                                    Mascara: {{ $pergunta['mascara'] }} <br>
                                    Obriga Justificativa: {{ $pergunta['obriga_justificativa'] }} <br>
                                    Obriga Mídia: {{ $pergunta['obriga_midia'] }}
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>Nenhuma pergunta encontrada.</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="p-4 bg-white shadow rounded">
            <h2 class="text-lg font-semibold">Nenhum dado encontrado</h2>
            <p>Não há dados disponíveis para exibir.</p>
        </div>
    @endif
</x-filament::page>
