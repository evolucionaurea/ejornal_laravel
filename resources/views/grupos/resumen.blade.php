@extends('partials.layout')

@section('title', 'Grupos')

@section('content')

@inject('carbon', 'Carbon\Carbon')

<div class="d-flex" id="wrapper">

	@include('partials.sidebar_grupos')

	<div id="page-content-wrapper">

		@include('partials.nav_sup')



		<div class="container">
			<section>


				<div class="row d-none">
					<div class="col-lg-3 col-md-6 mb-4">
						<a href="{{ route('/admin/clientes') }}" class="media white z-depth-1 rounded">
							<i class="fas fa-briefcase fa-lg blue z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Empresas del grupo</small></p>
								<h5 class="font-weight-bold mb-0">{{count($grupo->clientes)}}</h5>
							</div>
						</a>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<div class="tarjeta">
							<h4>Empresas del grupo: {{$grupo->clientes->count()}}</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th scope="col"> <b>Nombre</b></th>
										<th class="text-right">Total Nómina</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($clientes_nominas->clientes as $cliente)
									<tr>
										<td>{{$cliente->nombre}}</td>
										<td class="text-right">{{$cliente->nominas_count}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>


					<div class="col-md-4">
						<div class="tarjeta">

							<h4>Ausentismos de {{ Str::ucfirst($carbon::now()->formatLocalized('%B')) }}</h4>

							<table class="table table-striped">
								<thead>
									<tr>
										<th scope="col"><b>Nombre</b></th>
										<th class="text-right">Total Ausentes</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($clientes_ausentismos->clientes as $cliente)
									<tr>
										<td>{{$cliente->nombre}}</td>
										<td class="text-right">{{$cliente->ausentismos_count}}</td>
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


@endsection
