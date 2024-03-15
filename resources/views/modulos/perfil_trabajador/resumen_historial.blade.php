<table data-table="resumen_historial" class="table small table-bordered table-sm w-100">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Evento</th>
            <th>Tipo</th>
            <th>Observaciones</th>
            <th>Usuario</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($resumen_historial as $resumen)
        <tr>
            <td class="align-middle">{{ date('d/m/Y',strtotime($resumen->fecha)) }}</td>
            <td class="align-middle">{{ $resumen->evento }}</td>
            <td class="align-middle">{{ $resumen->tipo }}</td>
            <td class="align-middle">
                {{ strlen($resumen->observaciones) > 100
                ? substr($resumen->observaciones, 0, 100) . '...' : $resumen->observaciones }}
            </td>
            <td class="align-middle">{{ $resumen->usuario }}</td>
        </tr>
        @endforeach
    </tbody>
</table>