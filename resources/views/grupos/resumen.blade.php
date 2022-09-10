@extends('partials.layout')

@section('title', 'Grupos')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')

		<div class="container">

			<div class="container">
				<section>
					<div class="row">
						<div class="col-lg-3 col-md-6 mb-4">
							<a href="{{ route('/admin/clientes') }}" class="media white z-depth-1 rounded">
								<i class="fas fa-briefcase fa-lg blue z-depth-1 p-4 rounded-left text-white mr-3"></i>
								<div class="media-body p-1">
									<p class="text-uppercase text-muted mb-1"><small>Empresas del grupo</small></p>
									<h5 class="font-weight-bold mb-0">{{count($clientes_vinculados)}}</h5>
								</div>
							</a>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="tarjeta medicamentos_disponibles_por_empresa tabla">
								<h4>Empresas del grupo</h4>
								<table class="table table-striped">
									<thead>
										<tr>
											<th scope="col"> <b>Nombre</b> </th>
										</tr>
									</thead>
									<tbody>
										@foreach ($clientes_vinculados as $cliente)
										<tr>
											<td>{{$cliente->nombre}}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>

				</section>
			</div>

		</div>

	</div>

</div>


@endsection
