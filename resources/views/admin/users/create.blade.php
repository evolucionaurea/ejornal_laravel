@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Creación de usuarios</h2>
			<p>Aquí puede crear todos los tipos de usuario del sistema</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('admin/users')}}"><i class="fas fa-arrow-circle-left"></i>Volver</a>
			</div>
		</div>

		@include('../../mensajes_validacion')
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
			<form action="{{action('AdminUserController@store')}}" accept-charset="UTF-8" method="post">
				@csrf
				<div class="form-row align-items-center">
					<div class="form-group col-md-3">
						<label>Nombre completo</label>
						<input name="nombre" type="text" class="form-control form-control-sm" placeholder="" value="{{old('nombre')}}">
					</div>
					<div class="form-group col-md-3">
						<label>Email</label>
						<input name="email" type="email" class="form-control form-control-sm" placeholder="" value="{{old('email')}}">
					</div>
					<div class="form-group col-md-3">
						<label>Estado</label>
						<select name="estado" class="form-control form-control-sm">
							<option value="1">Activo</option>
							<option value="0">Inactivo</option>
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Rol</label>
						<select name="rol" class="form-control form-control-sm capturar_rol">
							@foreach ($roles as $rol)
							<option value="{{$rol->id}}">{{$rol->nombre}}</option>
							@endforeach
						</select>
					</div>

					<div class="form-group col-md-4 select_contratacion_users">
					  <label>Contratación</label>
					  <select name="contratacion" class="form-control form-control-sm">
						<option value="0">--Seleccionar--</option>
						<option value="1">Efectivo</option>
						<option value="2">Eventual</option>
					  </select>
					</div>

					<div class="form-group col-md-4">
						<label>Contraseña</label>
						<input name="password" type="password" class="form-control form-control-sm" placeholder="">
					</div>
					<div class="form-group col-md-4">
						<label>Confirmar contraseña</label>
						<input name="cpassword" type="password" class="form-control form-control-sm" placeholder="">
					</div>

					<div class="form-group col-md-3 cliente_original">
					  <label>Selecciona el cliente</label>
					  <br>
					  <select class="form-control form-control-sm" id="select_cliente_original" name="id_cliente_original">
						@foreach ($clientes as $cliente)
						  <option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
						@endforeach
					  </select>
					</div>

					<div class="form-group col-md-2 mostrar_personal_interno">
						<div class="form-check">
							<input name="personal_interno" class="form-check-input" type="checkbox" id="personal_interno_check">
							<label class="form-check-label" for="personal_interno_check">Personal interno</label>
						</div>
					</div>
					@if (count($clientes) > 0 && !empty($clientes))
					  <div class="form-group col-md-3 mostrar_clientes">
						<label>¿Para quién trabajará?</label>
						<br>
						<select style="max-width: 500px; min-width: 300px;" id="cliente_select_multiple" multiple="multiple" name="clientes[]">
						  @foreach ($clientes as $cliente)
							<option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
						  @endforeach
						</select>
					  </div>
					  <div class="form-group col-md-2 mostrar_permiso_desplegables">
						  <label>Permiso edición desplegables</label>
						  <select name="permiso_desplegables" class="form-control form-control-sm">
							  <option value="1">Sí puede</option>
							  <option value="0">No puede</option>
						  </select>
					  </div>
					  <div class="form-group col-md-2 mostrar_especialidades">
						  <label>Especialidad</label>
						  <select name="especialidad" class="form-control form-control-sm">
							@foreach ($especialidades as $especialidad)
							  <option value="{{$especialidad->id}}">{{$especialidad->nombre}}</option>
							@endforeach
						  </select>
					  </div>
					  <div class="form-group col-md-3 mostrar_cuil">
							<label>Cuil</label>
						  <input class="form-control form-control-sm" type="text" name="cuil" value="">
					  </div>
						<div class="form-group col-md-3 mostrar_calle">
							<label>Calle</label>
						  <input class="form-control form-control-sm" type="text" name="calle" value="">
					  </div>
						<div class="form-group col-md-3 mostrar_nro">
							<label>Número</label>
						  <input class="form-control form-control-sm" type="text" name="nro" value="">
					  </div>
						<div class="form-group col-md-3 mostrar_entre_calles">
							<label>Entre calles</label>
						  <input class="form-control form-control-sm" type="text" name="entre_calles" value="">
					  </div>
						<div class="form-group col-md-3 mostrar_localidad">
							<label>Localidad</label>
						  <input class="form-control form-control-sm" type="text" name="localidad" value="">
					  </div>
						<div class="form-group col-md-3 mostrar_partido">
							<label>Partido</label>
						  <input class="form-control form-control-sm" type="text" name="partido" value="">
					  </div>
						<div class="form-group col-md-3 mostrar_cod_postal">
							<label>Código postal</label>
						  <input class="form-control form-control-sm" type="text" name="cod_postal" value="">
					  </div>
						<div class="form-group col-md-12 mostrar_observaciones">
							<label>Observaciones</label>
							<textarea class="form-control" rows="2" name="observaciones"></textarea>
					  </div>
					  <div class="form-group col-md-12 liquidacion_onedrive_creacion_users">
							<label>Link Liquidación OneDrive
							  <a href="#" data-toggle="modal" data-target="#question_one_drive">
								<i class="fas fa-question-circle"></i>
							  </a>
							</label>
						  <textarea class="form-control" rows="2" name="onedrive"></textarea>
					  </div>

					  @else
						<div class="form-group col-md-12">
						  No tienes Clientes creados. Vé a su sección y crea Clientes
						</div>

					@endif


					<div class="form-group col-md-3 grupos">
						<label for="">Seleccionar Grupo</label>
						@if($grupos)
						<select name="id_grupo" class="form-control form-control-sm">
							@foreach($grupos as $grupo)
							<option value="{{$grupo->id}}">{{$grupo->nombre}}</option>
							@endforeach
						</select>
						@else
						[Debes crear un grupo primero]
						@endif
					</div>

					<div class="col-12">
					  <button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Crear usuario</button>
					</div>

				</div>
			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>


<!-- Modal Onedrive -->
<div class="modal fade" id="question_one_drive" tabindex="-1" aria-labelledby="question_one_drive_title" aria-hidden="true">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="question_one_drive_title">Liquidación en OneDrive</h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	  <div class="modal-body">
		<h4>Ingrese a Onedrive</h4>
		<br>
		<img width="100%;" src="{{asset('img/admin/onedrive_guia.png')}}" alt="">
		<p class="mt-2">
		  Click derecho en la carpeta que desea compartir y hacer click al boton de "compartir."
		  Allí sigue los pasos para obtener el link para compartir.
		</p>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Ok</button>
	  </div>
	</div>
  </div>
</div>

@endsection
