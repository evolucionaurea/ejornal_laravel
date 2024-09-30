<table table data-table="enfermeria" class="table small table-bordered table-sm w-100">
	<thead>
		<tr>
			<th>Fecha</th>
			<th>Tipo</th>
			<th>Temp. Aux.</th>
			<th>Peso</th>
			<th>Altura</th>
			<th>IMC</th>
			<th>Glucemia</th>
			<th>Sat. Oxígeno</th>
			<th>Tensión Art.</th>
			<th>Frec. Cardíaca</th>
			<th>Derivación</th>
			<th>Observaciones</th>
			<th>Cliente</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($consultas_enfermeria as $consulta_enfermeria)
		<tr>
			<td>{{ $consulta_enfermeria->fecha->format('d/m/Y') }}</td>
			<td>{{ $consulta_enfermeria->diagnostico->nombre ?? '-' }}</td>
			<td>{{ $consulta_enfermeria->temperatura_auxiliar ?? '-' }}</td>
			<td>{{ $consulta_enfermeria->peso ?? '-' }}</td>
			<td>{{ $consulta_enfermeria->altura ?? '-' }}</td>
			<td>{{ $consulta_enfermeria->imc ?? '-' }}</td>
			<td>{{ $consulta_enfermeria->glucemia ?? '-' }}</td>
			<td>{{ $consulta_enfermeria->saturacion_oxigeno ?? '-' }}</td>
			<td>{{ $consulta_enfermeria->tension_arterial ?? '-' }}</td>
			<td>{{ $consulta_enfermeria->frec_cardiaca ?? '-' }}</td>
			<td>{{ $consulta_enfermeria->derivacion_consulta }}</td>
			<td>{{ $consulta_enfermeria->observaciones }}</td>
			<td>{{ $consulta_enfermeria->cliente->nombre }}</td>
		</tr>
		@endforeach
	</tbody>
</table>