
<!DOCTYPE html>
<html lang="pt-br" >

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ storage_path('pdf.css') }}" type="text/css"> 
    
    <style>
 
    </style>

    <title>Embarque</title>
</head>
<body >
    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td style="width: 80%; height: 40px; text-align: left"><h2>Controle de Embarque (Boarding Control)</h2></td>

                <td style="text-align: right"><img src={{ storage_path("/images/logo.jpg") }}  style="width: 200px; height: 42px;"></td>
            </tr>

            <tr>
                <td class="w-half">
                    <div><b>Data (Date):  </b>{{ \Carbon\Carbon::parse($embarques[0]->data)->format('d/m/Y') }}</div>
                    <div><b>Cliente (Customer): </b>{{$embarques[0]->clientes->nome}}</div>
                    <div><b>Transportadora (Truck Company): </b>{{$embarques[0]->transportadoras->nome}}</div>
                    <div><b>Embalagens (Packaging): </b>{{$embarques[0]->embalagens}}</div>
                    
                </td>

                <td class="w-half">
                    <div><b>Contrato (Contract):  </b>{{$embarques[0]->contrato}}</div>
                    <div><b>Booking:  </b>{{$embarques[0]->booking}}</div>
                    <div><b>Navio (Ship):  </b>{{$embarques[0]->navio}}</div>
                    <div><b>Total Sacas (Total of sacs):  </b>{{$embarques[0]->total_sacas}}</div>
                </td>
            </tr>

        </table>
        <table class="w-full">
                <tr>
                    <td style="text-align: center"><img src={{ storage_path("/images/container.jpg") }}  style="width: 416; height: 150;"></td>
                </tr>
        </table>
    </div>


@foreach ($containers[0] as $container)
    <table>
    {{ $perguntas_containers_fotos = App\Models\EmbarqueContainerChecklistResposta::where('embarques_containers_id', $container->id)
        ->where('pergunta_imagem', 'I')
        ->where('visivel', 'S')
        ->orderBy('sequencia')->get() }}
        </table>

    <div class="page-break">
        <div class="margin-top">
        <table class="w-full" style="border: 0.01em solid rgb(134, 134, 134);">
            <caption style="font-size: 12pt; font-style:bold;"> Ponto de Verificação (Checking Points):  {{$container->container}} ({{$container->oic}}) ({{$container->lotes}})</caption>
            <tbody style="border: 0.01em solid rgb(134, 134, 134);">
                
                {{ $perguntas_containers = App\Models\EmbarqueContainerChecklistResposta::where('embarques_containers_id', $container->id)
                                    ->where('pergunta_imagem', 'P')
                                    ->orderBy('sequencia')->get() }}

                @if($container->status == 2)
                    <tr style="border: 0.01em solid rgb(134, 134, 134); background-color: red;">
                        <td style="border: 0.01em solid rgb(134, 134, 134); text-align:center; color:white; font-weight: bold" >CONTAINER REPROVADO</td>
                    </tr>
                @endif

                @foreach ($perguntas_containers as $questionario)
                    <tr style="border: 0.01em solid rgb(134, 134, 134);">
                        <td style="border: 0.01em solid rgb(134, 134, 134);">
                                <div style="">
                                <div style="font-size:8pt;" class="w-full"><b> {{$questionario->pergunta }} </b></div>
                                <div style="width: 90%; display:inline-block; font-size:8pt;">{{$questionario->texto}}</div>
                                <div style="display:inline-block; text-align:right;"> {{$questionario->resposta == 'Sim'? $questionario->resposta .' (YES)':$questionario->resposta .' (NO)'}}</div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td>Responsável: {{ $container->user_questionario!= null ? $container->user_questionario['name']:null;}}</td>
                </tr>
            </tbody>
        </table>
        </div>
    </div>

    @if($container->status != 2)
        <div class="page-break">
            <div class="margin-top">

                <div class="w-full" style="">

                        <h3 style="text-align:center;">Verificação Fotográfica (Photo Verification):  {{$container->container}}</h3>

                        @foreach ($perguntas_containers_fotos as $foto)
                                            
                            <div class="w-half-foto" style="">

                                <div class="cabecalho-foto">
                                    {{$foto->pergunta}}
                                </div>

                                <div class="foto">
                                    @if($foto->resposta) 
                                        <img src="{{storage_path('/app/public/'.$foto->resposta)}}" alt="" width="{{$foto_width}}" height="{{$foto_height}}">
                                    
                                    
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="w-full">
                            Responsável: {{ $container->user_modalidade != null ? $container->user_modalidade['name']:null;  }}
                        </div>
                    
                </div>
            </div>
        </div>

        <div class="page-break">
            <table>
                {{ $modalidades_containers = App\Models\EmbarqueContainerModalidade::where('container_id', $container->id)
                    ->orderBy('id')->get() }}
            </table>

            <div class="margin-top">

                <div class="w-full">

                    <h3 style="text-align:center;">Verificação Fotográfica (Photo Verification):  {{$container->container}}</h3>
                        
                        @foreach ($modalidades_containers as $modalidade_container)
                            <table>
                                {{$modalidades_containers_fotos = App\Models\EmbarqueContainerModalidadeResposta::where('modalidade_id', $modalidade_container->modalidade_id)
                                        ->where('embarques_containers_id', $container->id)
                                        ->where('embarque_id', $embarques[0]->id)
                                        ->orderBy('sequencia')->get();
                                    }} 
                            </table>
                            @foreach ($modalidades_containers_fotos as $modalidade)
                                
                            <div class="w-half-foto" style="">

                                <div class="cabecalho-foto">
                                    {{$modalidade->descricao}}
                                </div>

                                <div class="foto">
                                    @if($modalidade->imagem != null) 
                                        <img src="{{storage_path('/app/public/'.$modalidade->imagem)}}" alt="" width="{{$foto_width}}" height="{{$foto_height}}">
                                
                                    @else
                                        <img src="{{storage_path('/images/no-foto.jpg')}}" alt="" width="{{$foto_width}}" height="{{$foto_height}}">
                                    @endif
                                </div>

                            </div>

                            @endforeach 
                        @endforeach 
                        <div class="w-full">
                            Responsável: {{ $container->user_lacres != null ? $container->user_lacres['name']:null;  }}
                        </div>

                </div>
            </div>



        </div>



        <div class="page-break">
            <table>
                {{ $lacracoes = App\Models\EmbarqueContainerLacracaoResposta::where('embarques_containers_id', $container->id)
                    ->orderBy('id')->get() }}
            </table>

            <div class="margin-top">

                <div class="w-full">
                    <h3 style="text-align:center;">Verificação Fotográfica (Photo Verification):  {{$container->container}}</h3>
                        @foreach ($lacracoes as $lacre)
    
                            <div class="w-half-foto" style="">

                                <div class="cabecalho-foto">
                                    {{$lacre->texto}}
                                </div>

                                <div class="foto">
                                    @if($lacre->imagem !== null) 
                                        <img src="{{storage_path('/app/public/'.$lacre->imagem)}}" alt="" width="{{$foto_width}}" height="{{$foto_height}}">
                                    
                                    @else
                                        <img src="{{storage_path('/images/no-foto.jpg')}}" alt="" width="{{$foto_width}}" height="{{$foto_height}}">
                                    @endif
                                </div>
                            </div>
                        @endforeach 
                    
                </div>
            </div>
        </div>
    @endif
@endforeach  

</body>
</html>