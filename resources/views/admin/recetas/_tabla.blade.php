@if($recetas->isEmpty())
<div class="p-4 text-muted">No hay recetas emitidas aún.</div>
@else
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Trabajador</th>
                <th>Cliente</th>
                <th>ID Receta</th>
                <th>Estado</th>
                <th>PDF</th>
                <th class="text-right pr-3">Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla-recetas">
            @foreach($recetas as $r)
            @php
            $payload = is_string($r->payload)
            ? json_decode($r->payload, true)
            : (is_array($r->payload) ? $r->payload : []);
            $response = is_string($r->response)
            ? json_decode($r->response, true)
            : (is_array($r->response) ? $r->response : []);
            @endphp
            <tr id="receta-{{ $r->id }}" data-id="{{ $r->id }}">
                <td><span class="text-monospace">{{ $r->id }}</span></td>
                <td>
                    <span>{{ $r->created_at ? $r->created_at->format('d/m/Y H:i') : '-' }}</span>
                </td>
                <td>
                    @if($r->nomina)
                    <a href="{{ url('/empleados/nominas/'.$r->nomina->id) }}" target="_blank">
                        {{ $r->nomina->nombre }}
                    </a>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>
                <td>{{ optional($r->cliente)->nombre ?? '-' }}</td>
                <td>{{ $r->id_receta ?? '-' }}</td>
                <td>
                    @php
                    $estado = strtolower($r->estado ?? '');
                    $badge = $estado === 'anulada'
                    ? 'danger'
                    : ($estado === 'error' ? 'warning' : 'success');
                    @endphp
                    <span style="opacity: 80%;" class="badge p-2 badge-{{ $badge }}">
                        {{ ucfirst($r->estado) }}
                    </span>
                </td>
                <td>
                    @if($r->pdf_url)
                    <a class="btn-ejornal btn-ejornal-dark" href="{{ $r->pdf_url }}" target="_blank">
                        Descargar
                    </a>
                    @else
                    <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="text-right">
                    <a class="btn-ejornal btn-ejornal-base btn-sm mr-2"
                        href="{{ route('admin.recetas.show', $r->id) }}">
                        Ver
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="p-3">
    {{ $recetas->links() }}
</div>
@endif