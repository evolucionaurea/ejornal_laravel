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
			<p>Aquí puedes ver el listado de los grupos empresarios del sistema</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-base" href="{{route('grupos.create')}}"><i
						class="fas fa-plus-circle"></i> Nuevo grupo</a>
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
			<table data-table="grupos" class="table table-striped table-hover table-sm tabla">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Dirección</th>
						<th>Clientes asociados</th>
						<th>Acciones</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($grupos as $grupo)
					<tr>
						<td>{{$grupo->nombre}}</td>
						<td>{{$grupo->direccion}}</td>
						{{-- <td>
							{{$grupo->clientes->implode('nombre',', ')}}
						</td> --}}
						<td>
							<a href="#" class="ver-clientes btn btn-info btn-sm"
								data-grupo="{{ json_encode($grupo->clientes) }}">{{$grupo->clientes->count()}}</a>
						</td>

						<td class="acciones_tabla" scope="row">
							<a title="Editar" href="{{route('grupos.edit', $grupo->id)}}">
								<i class="fas fa-pen"></i>
							</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

<!-- Modal Clientes -->
<div class="modal fade" id="modalClientes" tabindex="-1" aria-labelledby="modalClientesLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalClientesLabel">Clientes Asociados</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="modalClientesBody">
				<ul class="list-group">
					<!-- Contenido de clientes asociados se actualizará aquí -->
				</ul>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function(){
    $('.ver-clientes').click(function(e){
        e.preventDefault();
        var clientesJson = JSON.stringify($(this).data('grupo'));
        var clientes = JSON.parse(clientesJson);
        var clientesHtml = '';
        $.each(clientes, function(index, cliente){
            clientesHtml += '<li class="list-group-item">' + cliente.nombre + '</li>';
        });
        $('#modalClientesBody').html('<ul>' + clientesHtml + '</ul>');
        $('#modalClientes').modal('show');
    });
});

</script>

@endsection