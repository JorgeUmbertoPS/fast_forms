<div>
    @if (session()->has('message'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">Respostas salvas com sucesso.</span>

    </div>
    @endif

    <form wire:submit.prevent="saveText">
 
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
                            
                            <x-filament::input.wrapper>
                                <x-filament::input 
                                    type="{{ $pergunta->mascara->mascara }}" 
                                    required 
                                    wire:model="respostas.{{$pergunta->id}}" 
                                    wire:blur="saveText({{ $pergunta->id }}, $event.target.value)"
                                    value="{{$pergunta->resposta}}" 
                                    placeholder="Digite sua resposta no formato {{$pergunta->mascara->mascara }}" />
                            </x-filament::input.wrapper>

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

                                    @php
                                        // Define a cor com base na resposta
                                        $color = 'gray'; // Cor padrão
                                        if ($resposta->nome === 'OK') {
                                            $color = 'success';
                                        } elseif ($resposta->nome === 'NOK') {
                                            $color = 'danger';
                                        } elseif ($resposta->nome === 'N/A') {
                                            $color = 'info';
                                        }
                                    @endphp

                                    <x-filament::icon-button
                                        icon="heroicon-m-{{ $resposta->icon }}"
                                        wire:click="saveRadio({{ $pergunta->id }}, {{ $resposta->id }})"
                                        label="{{ $resposta->nome }}"
                                        color="{{ $resposta->nome == $pergunta->resposta ? $color : 'gray' }}"
                                        size="xl"
                                    />
                                    <span>{{ $resposta->nome }}</span>
                                </label>
                            @endforeach

                            @if($pergunta->obriga_justificativa == 1)
                                <div class="mt-2">
                                    <x-filament::input.wrapper>
                                        <x-filament::input 
                                            type="text"
                                            wire:model.lazy="justificativas.{{ $pergunta->id }}"
                                            wire:blur="salvarJustificativa({{ $pergunta->id }})"
                                            wire:model="justificativas.{{$pergunta->id}}"
                                            placeholder="Digite sua justificativa..."

                                        />
                                    </x-filament::input.wrapper>
                                </div>
                            @endif

                            @if($pergunta->obriga_midia == 1)
                                <div class="mt-2">
                                    <input 
                                        type="file" 
                                        accept="image/*" 
                                        wire:model="uploads.{{ $pergunta->id }}"
                                    />

                                    @if(isset($imagensBase64[$pergunta->id]))
                                        <div class="mt-2">
                                            <img src="{{ $imagensBase64[$pergunta->id] }}" style="width: 100px;">
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </x-filament::fieldset>

                        @endif                                     
                  @endforeach

              </div>
            </x-filament::section>
     
        @endforeach

    </form>

    


</div>
