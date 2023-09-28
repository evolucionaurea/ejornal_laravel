@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_clientes')

	<div id="page-content-wrapper">
		@include('partials.nav_sup')
		<div class="cabecera">
			<h2>Historial de la nómina</h2>
			<p>Aquí puede ver el historial de la nómina de su empresa.</p>

			<div class="form-group">
				<a href="{{url('clientes/nominas')}}" class="btn btn-dark btn-sm"><i class="fa fa-angle-double-left fa-fw"></i> Volver al listado</a>
			</div>



			<hr>


			<table class="table table-striped table-hover table-sm tabla_nominas dt-responsive">

				<!--Table head-->
				<thead>
					<tr>
						<th>Año</th>
						<th>Mes</th>
						<th>Total Nómina</th>
						<th>Dif. respecto mes anterior</th>
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
