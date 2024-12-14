@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Ediciones de fichadas</h2>
			<p>Aquí puede ver el listado de las ediciones de fichadas</p>
		</div>

		@include('../mensajes_validacion')

		<div class="tarjeta">
			<table class="table table-striped table-hover table-sm">

				<!--Table head-->
				<thead>
					<tr>
						<th>Editó</th>
						<th>Trabajador</th>
						<th>ID fichada</th>
						<th>Fecha Ingreso Anterior</th>
						<th>Fecha Ingreso Nueva</th>
						<th>Fecha Egreso Anterior</th>
						<th>Fecha Egreso Nueva</th>
						<th>IP</th>
						<th>Dispositivo</th>
						<th>Fecha de modificacion</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($ediciones as $edicion)
					<tr>
						<td class="align-middle small">{{$edicion->user->nombre}}</td>
						<td class="align-middle small">{{$edicion->fichada->user->nombre}}</td>
						<td class="align-middle small">{{$edicion->id_fichada}}</td>
						<td class="align-middle small">
							@if ($edicion->old_ingreso != null)
							{{ $edicion->old_ingreso_formatted }}
							@else
							{{ 'N/A' }}
							@endif
						</td>
						<td class="align-middle small">
							@if ($edicion->new_ingreso != null)
							{{ $edicion->new_ingreso_formatted }}
							@else
							{{ 'N/A' }}
							@endif
						</td>
						<td class="align-middle small">
							@if ($edicion->old_egreso != null)
							{{ $edicion->old_egreso_formatted }}
							@else
							{{ 'N/A' }}
							@endif
						</td>
						<td class="align-middle small">
							@if ($edicion->new_egreso != null)
							{{ $edicion->new_egreso_formatted }}
							@else
							{{ 'N/A' }}
							@endif
						</td>
						<td class="align-middle small">{{$edicion->ip}}</td>
						<td class="align-middle small">{{$edicion->dispositivo}}</td>
						<td class="align-middle small">{{$edicion->created_at_formatted}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection