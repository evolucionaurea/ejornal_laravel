@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Listado de clientes</h2>
			<p>Aquí puede ver el listado de los clientes del sistema</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('clientes.create')}}"><i
						class="fas fa-plus-circle"></i> Nuevo cliente</a>
				<a data-toggle="modal" data-target="#cargar_clientes_excel" class="btn-ejornal btn-ejornal-success"
					href="#"><i class="fas fa-file-excel"></i>Carga masiva</a>
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
						<th class="th-lg">
							<a>
								Nombre
								<i class="fas fa-sort ml-1"></i>
							</a>
						</th>
						<th class="th-lg">
							<a href="">
								Direccion
								<i class="fas fa-sort ml-1"></i>
							</a>
						</th>
						<th class="th-lg">
							<a href="">
								Borrado logico
								<i class="fas fa-sort ml-1"></i>
							</a>
						</th>
						<th class="th-lg">
							<a href="">
								Acciones
								<i class="fas fa-sort ml-1"></i>
							</a>
						</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($clientes as $cliente)
					<tr>
						<td>{{$cliente->nombre}}</td>
						<td>{{$cliente->direccion}}</td>
						<td>
							@if ($cliente->trashed())
							<a data-toggle="modal" data-target="#restaurarClientModal" data-info="{{ $cliente->id }}"
								style="cursor: pointer;" class="restaurar_cliente">
								<span class="badge badge-danger">Yes</span>
							</a>
							@else
							<span class="badge badge-success">No</span>
							@endif
						</td>
						<td class="acciones_tabla" scope="row">
							@if (!$cliente->trashed())
							<a title="Ver" href="{{route('clientes.show', $cliente->id)}}">
								<i class="fas fa-eye"></i>
							</a>
							<a title="Editar" href="{{route('clientes.edit', $cliente->id)}}">
								<i class="fas fa-pen"></i>
							</a>
							<form class="" action="{{route('clientes.destroy', $cliente->id)}}" method="post">
								{{ csrf_field() }}
								<input type="hidden" name="_method" value="DELETE">
								<button title="Eliminar" type="submit">
									<i class="fas fa-trash"></i>
								</button>
							</form>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>



{{-- Modal Edit Asset Status --}}
<div class="modal fade" id="restaurarClientModal" tabindex="-1" aria-labelledby="restaurarClientModalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="restaurarClientModalLabel">Restaurar Cliente</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="form_edit_asset_status" action="{{action('AdminClientesController@restaurarCliente')}}"
					accept-charset="UTF-8" method="post" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="id_cliente" value="">
					<div class="row">
						<div class="col-sm-12">
							<p class="alert alert-info">
								Estas seguro que quieres restaurar a este cliente?
							</p>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<button class="btn btn-success btn-sm mt-4">Si, Restaurar</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="cargar_clientes_excel" tabindex="-1" aria-labelledby="clientes_excel_titulo"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="clientes_excel_titulo">Carga masiva</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="card mb-4">
					<h5 class="card-header">Paso 1</h5>
					<div class="card-body">
						<h5 class="card-title">Descargar modelo de excel</h5>
						<p class="card-text">Descarge este excel modelo. Completelo con los campos solicitados y subalo
							en el paso 2</p>
						<a class="btn-ejornal btn-ejornal-dark" href="{{asset('archivos/clientes_carga_masiva.csv')}}"
							download target="_blank">Descargar excel</a>
					</div>
				</div>
				<div class="card mb-4">
					<h5 class="card-header">Paso 2</h5>
					<div class="card-body">
						<h5 class="card-title">Cargar excel de clientes</h5>
						<form action="{{action('AdminClientesController@cargar_excel')}}" enctype="multipart/form-data"
							accept-charset="UTF-8" method="post">
							{{ csrf_field() }}
							<div class="form-group mb-4">
								<input name="archivo" type="file" class="form-control-file form-control-sm">
							</div>
							<button type="submit" class="btn-ejornal btn-ejornal-success">Subir excel</button>
							<button type="button" class="btn-ejornal btn-ejornal-gris-claro"
								data-dismiss="modal">Cerrar</button>
						</form>
					</div>
				</div>

			</div>

		</div>
	</div>
</div>


<script>
	$(document).ready(() => {
  
  $( ".restaurar_cliente" ).on( "click", function() {
    $("input[name='id_cliente']").val($(this).attr('data-info'));
  } );

});

</script>

@endsection