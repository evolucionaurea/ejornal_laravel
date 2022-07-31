@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="container">
			<section>
				<div class="row">

					<div class="col-lg-3 col-md-6 mb-4">
						<a href="{{ route('/admin/clientes') }}" class="media white z-depth-1 rounded">
							<i class="fas fa-briefcase fa-lg blue z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Clientes</small></p>
								<h5 class="font-weight-bold mb-0">{{$clientes}}</h5>
							</div>
						</a>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
						<a href="{{ route('/admin/users',['estado'=>1,'especialidad'=>2]) }}" class="media white z-depth-1 rounded">
							<i class="fas fa-user-times fa-lg pink z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Enfermeros activos</small></p>
								<h5 class="font-weight-bold mb-0">{{$enfermeros}}</h5>
							</div>
						</a>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
						<a href="{{ route('/admin/users',['estado'=>1,'especialidad'=>1]) }}" class="media white z-depth-1 rounded">
							<i class="fas fa-user-md fa-lg teal z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Medicos activos</small></p>
								<h5 class="font-weight-bold mb-0">{{$medicos}}</h5>
							</div>
						</a>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
						<a href="{{ route('/admin/users',['estado'=>1,'especialidad'=>2,'fichada'=>1]) }}" class="media white z-depth-1 rounded">
							<i class="fas fa-download fa-lg pink z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Enfermeros trabajando</small></p>
								<h5 class="font-weight-bold mb-0">{{$enfermeros_trabajando}}</h5>
							</div>
						</a>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
						<a href="{{ route('/admin/users',['estado'=>1,'especialidad'=>1,'fichada'=>1]) }}" class="media white z-depth-1 rounded">
							<i class="fas fa-download fa-lg teal z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Medicos trabajando</small></p>
								<h5 class="font-weight-bold mb-0">{{$medicos_trabajando}}</h5>
							</div>
						</a>
					</div>

					<div class="col-lg-3 col-md-6 mb-4">
						<a href="{{ route('/admin/medicamentos',['medicamento'=>$mas_sumunistrado->id]) }}" class="media white z-depth-1 rounded">
							<i class="fas fa-pills fa-lg teal z-depth-1 p-4 rounded-left text-white mr-3"></i>
							<div class="media-body p-1">
								<p class="text-uppercase text-muted mb-1"><small>Medicamento más suministrado</small></p>
								<h5 class="font-weight-bold mb-0">{{$mas_sumunistrado->nombre}} ({{$mas_sumunistrado->suministrados}})</h5>
							</div>
						</a>
					</div>

				</div>
				<div class="row">
					<div class="col-12">
						<div class="tarjeta medicamentos_disponibles_por_empresa tabla">
							<h4>Medicamentos disponibles</h4>
							<table class="table table-striped">
								<thead>
									<tr>
										<th scope="col">Medicamento</th>
										<th scope="col">Disponibles</th>
										<th scope="col">Empresa</th>
									</tr>
								</thead>
								<tbody>
									{{-- Se carga por JS --}}
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</section>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

@endsection
