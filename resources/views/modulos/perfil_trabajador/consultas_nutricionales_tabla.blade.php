<table table data-table="nutricional" class="table small table-bordered table-sm w-100">
    <thead>
        <tr>
            <th>Fecha atencion</th>
            <th>Prox Cita</th>
            <th>Nombre</th>
            <th>Tipo</th>
            <th>Cargado por</th>
            <th>Objetivos</th>
            <th>Gustos alim</th>
            <th>Comidas diarias</th>
            <th>Descanso</th>
            <th>Intol. Diges</th>
            <th>Alergias Alimentarias</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($consultas_nutricionales as $consulta_nutricional)
        <tr>
            <td>{{ $consulta_nutricional->fecha_atencion->format('d/m/Y') }}</td>
            <td>
                @if ($consulta_nutricional->prox_cita)
                {{ $consulta_nutricional->prox_cita->format('d/m/Y') }}
                @else
                {{ '[No cargada]'}}
                @endif
            </td>
            <td>{{ $consulta_nutricional->nomina->nombre ?? '-' }}</td>
            <td>{{ $consulta_nutricional->tipo ?? '-' }}</td>
            <td>{{ $consulta_nutricional->user ?? '[No registrado]' }}</td>
            <td>{{ $consulta_nutricional->objetivos ?? '-' }}</td>
            <td>{{ $consulta_nutricional->gustos_alimentarios ?? '-' }}</td>
            <td>{{ $consulta_nutricional->comidas_diarias ?? '-' }}</td>
            <td>{{ $consulta_nutricional->descanso ?? '-' }}</td>
            <td>{{ $consulta_nutricional->intolerancias_digestivas ?? '-' }}</td>
            <td>{{ $consulta_nutricional->alergias_alimentarias ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>