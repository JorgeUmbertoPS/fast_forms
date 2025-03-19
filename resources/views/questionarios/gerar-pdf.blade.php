
<!DOCTYPE html>
<html lang="pt-br" >

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ storage_path('pdf.css') }}" type="text/css"> 

    <title>Questionário</title>
</head>
<body >
    <div class="margin-top">
        <table class="w-full">
            <tr>
                <td style="width: 80%; height: 40px; text-align: center">Questionário <b> {{($questionario['nome']);}}</b></td>
                <td style="text-align: right"><img src={{ storage_path("/app/public/".$questionario['empresa']['logo']) }}  style="width: 100px; height: 100px;"></td>
            </tr>


        </table>

        <div class="w-full">

            @foreach($questionario['blocos'] as $bloco)
                    
                    <div style="width: 100%; text-align: center; background-color:#d3d3d3; margin-top:10px; padding:10px 0 10px 0">{{$bloco->descricao;}}</div>
              
                    @if($bloco['perguntas_questionario']) 
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">

                            @foreach ($bloco['perguntas_questionario'] as $pergunta)
                                <div style="width: 100%; text-align: left; margin-top:10px; padding:10px 0 10px 0">{{ $pergunta->pergunta_nome }} {{$pergunta->resposta}}</div>
                            @endforeach
                            
                        </div>
                    @endif
    
            @endforeach
        </div>

    </div>




</body>
</html>