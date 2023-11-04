@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">
		@include('partials.nav_sup')
		<div class="cabecera">
			<h2>Historial de la nómina</h2>
			<p>Aquí puede ver el historial de la nómina de la empresa seleccionada.</p>

			<div class="form-group">
				<a href="{{route('/grupos/nominas')}}" class="btn btn-dark btn-sm"><i class="fa fa-angle-double-left fa-fw"></i> Volver al listado</a>
			</div>

			<hr>

			<table data-table="historial_nominas" class="table table-striped table-hover table-sm dt-responsive">

				<!--Table head-->
				<thead>
					<tr>
						<th>Año</th>
						<th>Mes</th>
						<th>Total Nómina</th>
						<th>Diferencia Respecto Mes Anterior</th>
					</tr>
				</thead>
				<!--Table head-->

				<!--Table body-->
				<tbody>

				</tbody>
				<!--Table body-->
			</table>

		</div>

	</div>
</div>


@endsection
