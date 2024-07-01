<!-- Modal Ver y eliminar-->
<div class="modal fade" id="tipoPreocupacional" tabindex="-1" aria-labelledby="tipoPreocupacionalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tipos de preocupacional</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<ul class="list-group">
					@foreach($tipos as $tipo)
					<li class="list-group-item d-flex justify-content-between align-items-center">
						{{ $tipo->name }}
						<form action="{{route('preocupacionales_tipos.delete', $tipo->id)}}" method="post">
							{{ csrf_field() }}
							<input type="hidden" name="_method" value="DELETE">
							<button title="Eliminar" type="submit">
								<i style="color: rgb(116, 38, 38);" class="fas fa-trash"></i>
							</button>
						</form>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
</div>

<!-- Modal Agregar-->
<div class="modal fade" id="tipoPreocupacionalAdd" tabindex="-1" aria-labelledby="tipoPreocupacionalAddLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Nuevo tipo de preocupacional</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="{{action('EmpleadosPreocupacionalesTipoController@store')}}" accept-charset="UTF-8"
					method="post" enctype="multipart/form-data">
					@csrf
					<div class="form-group">
						<label>Nombre</label>
						<input type="text" class="form-control" name="name" required>
					</div>
					<button type="submit" class="btn-ejornal btn-ejornal-success">Guardar</button>
				</form>
			</div>
		</div>
	</div>
</div>