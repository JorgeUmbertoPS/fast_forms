<div>
    @if (session()->has('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">Respostas salvas com sucesso.</span>

    </div>
    @endif

    <form wire:submit.prevent="saveTexto">
 
        {{ csrf_field() }}
    
        @foreach ($questionario[0]->questionario_perguntas_blocos as $blocos)

            <x-filament::section
                style="margin-top: 15px;"
              icon="heroicon-o-{{ $blocos->icon }}"
              icon-color="success"
              collapsible
              collapsed
              >
              <x-slot name="heading">
              {{ $blocos->descricao }}
              </x-slot>
              <div class="grid grid-cols-3 gap-3 m-1 "> 
                  @foreach ($blocos->perguntas_questionario as $pergunta)           
                        
                        @if($pergunta->resposta_tipo_id == 3)
                               
                                  <label class="text-lg font-bold"> {{ $pergunta->pergunta_nome }} </label>
                                  
                                    <!-- Cria um campo de texto vinculado à pergunta, e o valor será a resposta -->
                                    <x-filament::input.wrapper>
                                    <x-filament::input 
                                    type="{{ $pergunta->mascara->mascara }}" 
                                    require wire:model="respostas.{{$pergunta->id}}" 
                                    value="{{$pergunta->resposta}}" 
                                    placeholder="Digite sua resposta no formato {{$pergunta->mascara->mascara }}" />
                                    </x-filament::input.wrapper>
                                    
                                    <!-- Exibe a mensagem de erro se houver um problema com este campo -->
                                    @error('respostas.'.$pergunta->id)
                                        <span class="text-red-700 text-sm">{{ $message }}</span>
                                    @enderror
                  
                        
                        @endif

                        @if($pergunta->resposta_tipo_id == 2)
                        <x-filament::fieldset>
                            <x-slot name="label">
                            {{ $pergunta->pergunta_nome }}
                            </x-slot>
                            
                            @foreach ($pergunta->questionario_respostas as $resposta)
                                        <label class="inline-flex items-center" style="margin-left: 20px;">

                                            @if($resposta->nome == $pergunta->resposta)
                                                
                                            <x-filament::icon-button
                                                    icon='heroicon-m-{{ $resposta->icon }}'
                                                    wire:click="saveRadio({{ $pergunta->id }},{{ $resposta->id }})"
                                                    label="{{ $resposta->nome }}"
                                                    class="info"
                                                    size="xl"
                                                />
                                                <span>{{ $resposta->nome }}</span>
                                            @else
                                            <x-filament::icon-button
                                                    icon='heroicon-m-{{ $resposta->icon }}'
                                                    wire:click="saveRadio({{ $pergunta->id }},{{ $resposta->id }})"
                                                    label="{{ $resposta->nome }}"
                                                    color="gray"
                                                    size="xl"
                                                />
                                                <span>{{ $resposta->nome }}</span>
                                            @endif
                                            
                                        </label>
                            @endforeach
                        </x-filament::fieldset>

                        @endif                                     
                  @endforeach
              </div>
            </x-filament::section>
     
        @endforeach

        <x-filament::button 
        style="margin-top: 15px;"
        type="submit"
            icon="heroicon-m-circle-stack"
            icon-position="after"
        >
            Salvar
        </x-filament::button>
    </form>

    


</div>
