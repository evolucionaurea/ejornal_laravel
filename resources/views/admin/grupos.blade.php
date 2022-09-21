@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de grupos empresarios</h2>
			<p>Aquí puede ver el listado de los grupos empresarios del sistema</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('grupos.create')}}"><i class="fas fa-plus-circle"></i> Nuevo grupo</a>
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
			<table class="table table-striped table-hover table-sm tabla">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Direccion</th>
						<th>Clientes asociados</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($grupos as $grupo)
					<tr>
						<td>{{$grupo->nombre}}</td>
						<td>{{$grupo->direccion}}</td>
						<td>
							{{$grupo->clientes->implode('nombre',', ')}}
						</td>
						<td class="acciones_tabla" scope="row">
							<a title="Editar" href="{{route('grupos.edit', $grupo->id)}}">
								<i class="fas fa-pen"></i>
							</a>
							{{-- <form class="" action="{{route('grupos.destroy', $grupo->id)}}" method="post">
								{{ csrf_field() }}
								<input type="hidden" name="_method" value="DELETE">
								<button title="Eliminar" type="submit">
									<i class="fas fa-trash"></i>
								</button>
							</form> --}}
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>


@endsection
