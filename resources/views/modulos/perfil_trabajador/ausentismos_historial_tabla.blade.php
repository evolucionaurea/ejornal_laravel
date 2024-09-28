<table data-table="ausentismos" class="table small table-bordered table-sm w-100">
	<thead>
		<tr>
			<th>Fecha Inicio</th>
			<th>Fecha Final</th>
			<th>Fecha Regreso a Trabajar</th>
			<th>Tipo</th>
			<th>Usuario que registró</th>
			<th>Comunicación</th>
			<th>Cliente</th>
			<th>Certificados</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($ausentismos as $ausentismo)
		<tr>
			<td class="align-middle">{{ $ausentismo->fecha_inicio->format('d/m/Y') }}</td>
			<td class="align-middle">{{ $ausentismo->fecha_final->format('d/m/Y') }}</td>
			<td class="align-middle">{{ $ausentismo->fecha_regreso_trabajar ?
				$ausentismo->fecha_regreso_trabajar->format('d/m/Y') : '[no cargada]' }}</td>
			<td class="align-middle">{{ $ausentismo->tipo->nombre }}</td>
			<td class="align-middle">{{ $ausentismo->user }}</td>
			<td class="align-middle">
				@if ($ausentismo->comunicaciones->isNotEmpty())
				@foreach ($ausentismo->comunicaciones as $comunicacion)
				<div>
					<span class="font-weight-bold">1ra. Comunicación ({{ $comunicacion->tipo->nombre }}):</span>
					{{ $comunicacion->descripcion }}
				</div>

				@if ($comunicacion->archivos->isNotEmpty())
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-info btn-sm dropdown-toggle text-white" data-toggle="dropdown"
						aria-expanded="false">
						Archivos
					</button>
					<div class="dropdown-menu">
						@foreach ($comunicacion->archivos as $archivo)
						<a class="dropdown-item"
							href="{{ route('comunicaciones.verArchivo', ['id' => $archivo->id_comunicacion, 'hash' => $archivo->hash_archivo]) }}"
							target="_blank">
							{{ $archivo->archivo }}
						</a>
						@endforeach
					</div>
				</div>
				@else
				<span class="text-muted font-italic">No se adjuntó</span>
				@endif
				@endforeach

				@if($ausentismo->comunicaciones_count > 1)
				<a href="{{ url('empleados/comunicaciones/'.$ausentismo->id) }}" class="small text-info">ver todas las
					comunicaciones ({{ $ausentismo->comunicaciones_count }})</a>
				@endif
				@else
				<span class="text-muted font-italic">[No se cargó]</span>
				@endif
			</td>

			<td class="align-middle">
				{{ $ausentismo->cliente->nombre }}
			</td>
			<td class="align-middle">
				@if($ausentismo->documentaciones->count())
				<a href="{{ url('empleados/documentaciones/'.$ausentismo->id) }}"
					class="btn-ejornal btn-ejornal-success" target="_blank">Ver Certificados
					({{$ausentismo->documentaciones->count()}})</a>
				@else
				<span class="text-muted font-italic">[No se adjuntó documentación]</span>
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>