<table data-table="preocupacionales" class="table small table-bordered table-sm w-100">
	<thead>
		<tr>
			<th>Fecha</th>
			<th>Tipo Estudio</th>
			<th>Observaciones</th>
			<th>Archivo</th>
			<th>Cliente</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($preocupacionales as $preocupacional)
		<tr>
			<td>{{ (!empty($preocupacional->fecha)) ? date('d/m/Y',strtotime($preocupacional->fecha)) : "" }}</td>
			<td>{{ $preocupacional->tipo->name ?? 'No aplica' }}</td>
			<td>{{ $preocupacional->observaciones }}</td>
			<td>
				@if ($preocupacional->archivo)
				<a class="btn-ejornal btn-ejornal-gris-claro"
					href="{{route('preocupacionales.archivo', $preocupacional->id)}}">
					<i class="fa fa-file"></i>{{$preocupacional->archivo}}
				</a>
				@else
				<span class="text-muted font-italic">No se adjuntó documentación</span>
				@endif
			</td>
			<td>
				{{ $preocupacional->cliente->nombre }}
			</td>
		</tr>
		@endforeach
	</tbody>
</table>