

@if (!empty($getState()))
    <ul class="list-disc list-inside text-sm text-red-600">
        @foreach ($getState() as $inconsistencia)
            <li style="color: red;">{{ $inconsistencia }}</li>
        @endforeach
    </ul>
@else
    <p class="text-sm text-green-600">Nenhuma inconsistência encontrada.</p>
@endif
