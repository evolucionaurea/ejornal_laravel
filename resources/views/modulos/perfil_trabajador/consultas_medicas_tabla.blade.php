<table table data-table="medicas" class="table small table-bordered table-sm w-100">
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
			<th style="width:220px">Anamnesis</th>
			<th style="width:220px">Tratamiento</th>
			<th style="width:220px">Observaciones</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($consultas_medicas as $consulta_medica)
		<tr>
			<td>{{ $consulta_medica->fecha->format('d/m/Y') }}</td>
			<td>{{ $consulta_medica->diagnostico->nombre ?? '-' }}</td>
			<td>{{ $consulta_medica->temperatura_auxiliar ?? '-' }}</td>
			<td>{{ $consulta_medica->peso ?? '-' }}</td>
			<td>{{ $consulta_medica->altura ?? '-' }}</td>
			<td>{{ $consulta_medica->imc ?? '-' }}</td>
			<td>{{ $consulta_medica->glucemia ?? '-' }}</td>
			<td>{{ $consulta_medica->saturacion_oxigeno ?? '-' }}</td>
			<td>{{ $consulta_medica->tension_arterial ?? '-' }}</td>
			<td>{{ $consulta_medica->frec_cardiaca ?? '-' }}</td>
			<td>{{ $consulta_medica->anamnesis }}</td>
			<td>{{ $consulta_medica->tratamiento }}</td>
			<td>{{ $consulta_medica->observaciones }}</td>
		</tr>
		@endforeach
	</tbody>
</table>