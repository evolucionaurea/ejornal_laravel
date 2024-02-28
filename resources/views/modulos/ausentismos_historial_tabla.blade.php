<table data-table="ausentismos" class="table small table-bordered table-sm w-100">
	<thead>
		<tr>
			<th>Fecha Inicio</th>
			<th>Fecha Final</th>
			<th>Fecha Regreso a Trabajar</th>
			<th>Tipo</th>
			<th>Ususario que registró</th>
			<th>Comunicación</th>
			<th>Archivo</th>
			<th>Certificados</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($ausentismos as $ausentismo)
		<tr>
			<td class="align-middle">{{ $ausentismo->fecha_inicio->format('d/m/Y') }}</td>
			<td class="align-middle">{{ $ausentismo->fecha_final->format('d/m/Y') }}</td>
			<td class="align-middle">{{ $ausentismo->fecha_regreso_trabajar->format('d/m/Y') }}</td>
			<td class="align-middle">{{ $ausentismo->tipo->nombre }}</td>
			<td class="align-middle">{{ $ausentismo->user }}</td>
			<td class="align-middle">
				@if($ausentismo->comunicacion)
				<b>{{ $ausentismo->comunicacion->tipo->nombre }}:</b> {{ $ausentismo->comunicacion->descripcion}}
				@else
				<span class="text-muted font-italic">[No se cargó]</span>
				@endif
			</td>
			<td class="align-middle">
				@if ($ausentismo->archivo)
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{route('ausentismos.archivo', $ausentismo->id)}}">
					<i class="fa fa-file"></i>{{$ausentismo->archivo}}
				</a>
				@else
				<span class="text-muted font-italic">[No se adjuntó ningún archivo]</span>
				@endif
			</td>
			<td>
				@if($ausentismo->documentaciones->count())
				<a href="{{url('empleados/documentaciones/'.$ausentismo->id)}}" class="btn-ejornal btn-ejornal-success" target="_blank">Ver Certificados ({{$ausentismo->documentaciones->count()}})</a>
				@else
				<span class="text-muted font-italic">[No se adjuntó documentación]</span>
				@endif
			</td>
		</tr>
		@endforeach
	</tbody>
</table>