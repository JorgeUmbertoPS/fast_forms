
<!DOCTYPE html>
<html lang="pt-br" >

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ storage_path('pdf.css') }}" type="text/css"> 

    <title>Questionário</title>
</head>
<body >

    @include('questionarios.'.$questionario['relatorio'], ['questionario' => $questionario])


</body>
</html>