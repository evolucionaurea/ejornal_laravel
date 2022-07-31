@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de medicamentos</h2>
			<p>Aquí puede ver el listado de los medicamentos del sistema</p>
			<div class="cabecera_acciones">
				<a data-toggle="modal" data-target="#crear_medicamento" class="btn-ejornal btn-ejornal-base" href="#"><i class="fas fa-plus-circle"></i>Nuevo medicamento</a>
			</div>
		</div>

		@include('../mensajes_validacion')
		@if ($errors->any())
		@foreach ($errors->all() as $error)
		<div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
			{{$error}}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		@endforeach
		@endif

		<div class="tarjeta">

			<div data-toggle="busqueda-filtros" class="d-flex row">

				<div class="col-lg-12 d-flex align-items-center">

					<input type="hidden" name="filtro" value="{{ Request::get('filtro') }}">
					<input type="hidden" name="medicamento" value="{{ Request::get('medicamento') }}">

					<select name="stock" class="form-control form-control-sm w-25 mr-2">
						<option value="">--C/S Stock--</option>
						<option value="1" {{Request::get('stock')=='1' ? 'selected' : '' }} >Con Stock</option>
						<option value="0" {{Request::get('stock')=='0' ? 'selected' : '' }} >Sin Stock</option>
					</select>


					<button data-toggle="search" class="btn-ejornal btn-ejornal-gris-claro" ><i class="fas fa-search"></i> Buscar</button>
					<button data-toggle="clear" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</button>

				</div>
			</div>

			<hr>


			<table class="table table-striped table-hover table-sm tabla">
				<thead>
					<tr>
						<th>Nombre</th>
						<th class="text-center">Stock Actual</th>
						<th class="text-center">Total Suministrados</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="crear_medicamento" tabindex="-1" aria-labelledby="cargar_medicamento_titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cargar_medicamento_titulo">Nuevo medicamento</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<form action="{{action('AdminMedicamentosController@store')}}" enctype="multipart/form-data" accept-charset="UTF-8" method="post">
				  {{ csrf_field() }}
				  <div class="form-group">
					  <label >Nombre del medicamento</label>
					  <input name="nombre" required type="text" class="form-control form-control-sm" placeholder="">
				  </div>
				  <button type="submit" class="btn-ejornal btn-ejornal-base">Crear medicamento</button>
				  <button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</button>
				</form>

			</div>
		</div>
	</div>
</div>

@endsection
