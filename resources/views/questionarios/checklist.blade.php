<div class="margin-top">
        <table class="w-full">
            <tr>
                <td style="width: 80%; height: 40px; text-align: center">Questionário <b> {{($questionario['nome']);}}</b></td>
                <td style="width: 20%; text-align: right; vertical-align: top;">
                <img src="{{ asset('storage/logos/' . $questionario['empresa']['logo']) }}" style="width: 100px; height: 100px;">

                </td>
            </tr>
        </table>

        <div class="w-full">

        @foreach($questionario['blocos'] as $bloco)

            <div style="width: 100%; text-align: center; background-color:#d3d3d3; margin-top:10px; padding:10px 0 10px 0;">
                {{ $bloco->descricao }}
            </div>

            @if($bloco['perguntas_questionario'])
                <table width="100%" style="margin-top: 10px; border-collapse: collapse;">
                    <tr>
                        @php $colCount = 0; @endphp
                        @foreach ($bloco['perguntas_questionario'] as $pergunta)
                            <td style="width: 33%; text-align: center; padding: 10px; border: 1px solid #000;">
                                <b>{{ $pergunta->pergunta_nome }}: </b> <br>{{ $pergunta->resposta }}
                            </td>
                            @php $colCount++; @endphp

                            @if($colCount % 3 == 0)
                                </tr><tr>
                            @endif
                        @endforeach

                        {{-- Preencher as colunas vazias, se o número de perguntas não for múltiplo de 3 --}}
                        @if($colCount % 3 != 0)
                            @for($i = 0; $i < (3 - $colCount % 3); $i++)
                                <td style="width: 33%; padding: 10px; border: 1px solid #000;">&nbsp;</td>
                            @endfor
                        @endif
                    </tr>
                </table>
            @endif

        @endforeach


        </div>

    </div>