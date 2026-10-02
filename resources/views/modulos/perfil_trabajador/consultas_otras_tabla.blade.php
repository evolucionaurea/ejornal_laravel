<table table data-table="otras" class="table small table-bordered table-sm w-100">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Tipo de consulta</th>
            <th>Motivo</th>
            <th>Desarrollo</th>
            <th>Empresa</th>      
        </tr>
    </thead>
    <tbody>
        @foreach ($consultas_otras as $consulta_otra)
        <tr>
            <td>{{ $consulta_otra->fecha->format('d/m/Y') }}</td>
            <td>{{ $consulta_otra->profesional_tipo->nombre ?? '-' }}</td>
            <td>{{ $consulta_otra->motivo ?? '-' }}</td>
            <td>{{ $consulta_otra->desarrollo ?? '-' }}</td>
            <td>{{ $consulta_otra->cliente->nombre ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>