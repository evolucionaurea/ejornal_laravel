@extends('partials.layout')

@section('title', 'Admin')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_admin')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Edición de usuarios</h2>
			<p>Aquí podrá editar la información de un usuario</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('admin/users')}}"><i
						class="fas fa-arrow-circle-left"></i>Volver</a>
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
			<form action="{{action('AdminUserController@update', $user->id)}}" accept-charset="UTF-8" method="post">
				{{ csrf_field() }}
				<input name="_method" type="hidden" value="PUT">
				<input type="hidden" name="rol" value="{{$user->id_rol}}">
				<div class="form-row">
					<div class="form-group col-lg-3 col-md-4 col-sm-12">
						<label>Nombre completo</label>
						<input name="nombre" type="text" class="form-control form-control-sm" value="{{$user->nombre}}"
							placeholder="">
					</div>
					<div class="form-group col-lg-3 col-md-4 col-sm-12">
						<label>Email</label>
						<input name="email" type="email" class="form-control form-control-sm" value="{{$user->email}}"
							placeholder="">
					</div>
					<div class="form-group col-lg-3 col-md-4 col-sm-12">
						<label>Estado</label>
						<select name="estado" class="form-control form-control-sm">
							@if ($user->estado == 1)
							<option selected value="1">Activo</option>
							<option value="0">Inactivo</option>
							@else
							<option value="1">Activo</option>
							<option selected value="0">Inactivo</option>
							@endif
						</select>
					</div>
					<div class="form-group col-lg-3 col-md-4 col-sm-12 select_contratacion_users">
						<label>Contratación</label>
						<select name="contratacion" class="form-control form-control-sm">
							<option @if ($user->contratacion === null)
								{{ 'selected' }}
								@endif
								value="0">--Seleccione--</option>
							<option @if ($user->contratacion === 1)
								{{ 'selected' }}
								@endif
								value="1">Efectivo</option>
							<option @if ($user->contratacion === 2)
								{{ 'selected' }}
								@endif
								value="2">Eventual</option>
						</select>
					</div>
					<div class="form-group col-lg-3 col-md-4 col-sm-12">
						<label>Rol</label>
						<select name="rol" class="form-control form-control-sm capturar_rol" disabled>
							@foreach ($roles as $rol)
							@if ($rol->id == $user->id_rol)
							<option selected value="{{$rol->id}}">{{$rol->nombre}}</option>
							@else
							<option value="{{$rol->id}}">{{$rol->nombre}}</option>
							@endif
							@endforeach
						</select>
					</div>
					@if ($user->id_cliente_relacionar != null)
					<div class="form-group col-lg-3 col-md-4 col-sm-12 cliente_original">
						<label>Representa al cliente</label>
						<br>
						<select disabled class="form-control form-control-sm" id="select_cliente_original"
							name="id_cliente_original">
							@foreach ($clientes as $cliente)
							@if ($cliente->id == $user->id_cliente_relacionar)
							<option selected value="{{$cliente->id}}">{{$cliente->nombre}}</option>
							@endif
							@endforeach
						</select>
					</div>
					@endif

					@if ($user->id_grupo != null)
					<div class="form-group col-lg-3 col-md-4 col-sm-12 grupos">
						<label>Pertenece al grupo</label>
						<br>
						<select disabled class="form-control form-control-sm" name="id_grupo">
							<option selected value="{{$user->id_grupo}}">{{$user->grupo->nombre}}</option>
						</select>
					</div>
					@endif


					<div class="form-group col-lg-4 col-md-6 col-sm-12 mostrar_personal_interno">
						<div class="form-check">
							<input class="check_personal_interno" name="personal_interno" class="form-check-input"
								type="checkbox" id="gridCheck" {{$user->personal_interno ? 'checked' : '' }}>
							<label class="form-check-label" for="gridCheck">Personal interno</label>
						</div>
					</div>
					<div class="form-group col-lg-4 col-md-6 col-sm-12 mostrar_clientes">
						<label>El empleado trabaja en</label>
						<br>
						<select style="max-width: 500px; min-width: 300px;" id="cliente_select_multiple"
							multiple="multiple" name="clientes[]">
							@foreach ($clientes as $cliente)
							@if (in_array($cliente->id, $clientes_seleccionados))
							<option selected value="{{$cliente->id}}">{{$cliente->nombre}}</option>
							@else
							<option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
							@endif
							@endforeach
						</select>
					</div>
					<div class="form-group col-lg-4 col-md-6 col-sm-12 mostrar_permiso_desplegables">
						<label>Permiso desplegables</label>
						<select name="permiso_desplegables" class="form-control form-control-sm">
							@if ($user->permiso_desplegables == 1)
							<option selected value="1">Si puede</option>
							<option value="0">No puede</option>
							@else
							<option value="1">Si puede</option>
							<option selected value="0">No puede</option>
							@endif
						</select>
					</div>
					<div class="form-group col-lg-4 col-md-6 col-sm-12 mostrar_especialidades">
						<label>Especialidad</label>
						<select name="especialidad" class="form-control form-control-sm">
							@foreach ($especialidades as $especialidad)
							<option @if($especialidad->id == $user->id_especialidad)
								{{'selected'}}
								@endif
								value="{{$especialidad->id}}">{{$especialidad->nombre}}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-3 mostrar_cuil">
						<label>Cuil</label>
						<input class="form-control form-control-sm" type="text" name="cuil" value="{{$user->cuil}}">
					</div>
					<div class="form-group col-md-3 mostrar_calle">
						<label>Calle</label>
						<input class="form-control form-control-sm" type="text" name="calle" value="{{$user->calle}}">
					</div>
					<div class="form-group col-md-3 mostrar_nro">
						<label>Número</label>
						<input class="form-control form-control-sm" type="text" name="nro" value="{{$user->nro}}">
					</div>
					<div class="form-group col-md-3 mostrar_entre_calles">
						<label>Entre calles</label>
						<input class="form-control form-control-sm" type="text" name="entre_calles"
							value="{{$user->entre_calles}}">
					</div>
					<div class="form-group col-md-3 mostrar_localidad">
						<label>Localidad</label>
						<input class="form-control form-control-sm" type="text" name="localidad"
							value="{{$user->localidad}}">
					</div>
					<div class="form-group col-md-3 mostrar_partido">
						<label>Partido</label>
						<input class="form-control form-control-sm" type="text" name="partido"
							value="{{$user->partido}}">
					</div>
					<div class="form-group col-md-3 mostrar_cod_postal">
						<label>Código postal</label>
						<input class="form-control form-control-sm" type="text" name="cod_postal"
							value="{{$user->cod_postal}}">
					</div>
					<div class="form-group col-md-2 mostrar_permitir_fichada">
						<label>El usuario debe fichar</label>
						<select name="fichar" class="form-control form-control-sm">
							<option @if($user->fichar == 1 && $user->fichar == null)
								{{'selected'}}
								@endif
								value="1">Si</option>
							<option @if($user->fichar == 0)
								{{'selected'}}
								@endif
								value="0">No</option>
						</select>
					</div>
					<div class="form-group col-md-12 mostrar_observaciones">
						<label>observaciones</label>
						<textarea class="form-control" rows="2" name="observaciones">{{$user->observaciones}}</textarea>
					</div>
					<div class="form-group col-md-12 liquidacion_onedrive_creacion_users">
						<label>Link Liquidación OneDrive
							<a href="#" data-toggle="modal" data-target="#question_one_drive">
								<i class="fas fa-question-circle"></i>
							</a>
						</label>
						<textarea class="form-control" rows="2"
							name="onedrive">{{ (!empty($user->onedrive)) ? $user->onedrive : "" }}</textarea>
					</div>
					@if ($user->id_rol == 2)
					<hr>
					<div class="row">
						<div class="form-group col-lg-4 col-md-6 col-sm-12">
							<label>DNI</label>
							<br>
							@if ($user->dni == null)
							<div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
							@else
							<p>{{$user->dni}}</p>
							@endif
						</div>
						<div class="form-group col-lg-4 col-md-6 col-sm-12">
							<label>DNI archivo delantero</label>
							<br>
							@if ($user->archivo_dni == null)
							<div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
							@else
							<div class="alert alert-primary" role="alert">
								<a class="alert-link " href="{{route('users.download_dni', $user->id)}}">
									<i class="fa fa-file ml-2"></i>{{$user->archivo_dni}}
								</a>
							</div>
							@endif
						</div>
						<div class="form-group col-lg-4 col-md-6 col-sm-12">
							<label>DNI archivo trasero</label>
							<br>
							@if ($user->archivo_dni_detras == null)
							<div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
							@else
							<div class="alert alert-primary" role="alert">
								<a class="alert-link " href="{{route('users.download_dni_detras', $user->id)}}">
									<i class="fa fa-file ml-2"></i>{{$user->archivo_dni_detras}}
								</a>
							</div>
							@endif
						</div>
						<div class="form-group col-lg-4 col-md-6 col-sm-12">
							<label>Matricula</label>
							<br>
							@if ($user->matricula == null)
							<div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
							@else
							<p>{{$user->matricula}}</p>
							@endif
						</div>
						<div class="form-group col-lg-4 col-md-6 col-sm-12">
							<label>Matricula archivo delantero</label>
							<br>
							@if ($user->archivo_matricula == null)
							<div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
							@else
							<div class="alert alert-primary" role="alert">
								<a class="alert-link " href="{{route('users.download_matricula', $user->id)}}">
									<i class="fa fa-file ml-2"></i>{{$user->archivo_matricula}}
								</a>
							</div>
							@endif
						</div>
						<div class="form-group col-lg-4 col-md-6 col-sm-12">
							<label>Matricula archivo trasero</label>
							<br>
							@if ($user->archivo_matricula_detras == null)
							<div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
							@else
							<div class="alert alert-primary" role="alert">
								<a class="alert-link " href="{{route('users.download_matricula_detras', $user->id)}}">
									<i class="fa fa-file ml-2"></i>{{$user->archivo_matricula_detras}}
								</a>
							</div>
							@endif
						</div>
						<div class="form-group col-lg-4 col-md-6 col-sm-12">
							<label>Matricula fecha vencimiento</label>
							<br>
							@if ($user->fecha_vencimiento == null)
							<div class="alert alert-info" role="alert">No hay una fecha cargada</div>
							@else
							<p>
								{{ ($user->fecha_vencimiento != null)
								?
								date('d/m/Y',strtotime($user->fecha_vencimiento))
								:
								'' }}
							</p>
							@endif
						</div>
						<div class="form-group col-lg-4 col-md-6 col-sm-12">
							<label>Titulo parte delantera</label>
							<br>
							@if ($user->titulo == null)
							<div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
							@else
							<div class="alert alert-primary" role="alert">
								<a class="alert-link " href="{{route('users.download_titulo', $user->id)}}">
									<i class="fa fa-file ml-2"></i>{{$user->titulo}}
								</a>
							</div>
							@endif
						</div>
						<div class="form-group col-lg-4 col-md-6 col-sm-12">
							<label>Titulo parte trasera</label>
							<br>
							@if ($user->archivo_titulo_detras == null)
							<div class="alert alert-info" role="alert">No hay un archivo adjunto</div>
							@else
							<div class="alert alert-primary" role="alert">
								<a class="alert-link " href="{{route('users.download_titulo_detras', $user->id)}}">
									<i class="fa fa-file ml-2"></i>{{$user->archivo_titulo_detras}}
								</a>
							</div>
							@endif
						</div>
					</div>
					@endif
					<div class="col-12">
						<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar
							cambios</button>
						@if (auth()->user()->id == $user->id)
						<button data-toggle="modal" data-target="#cambiar_pass"
							class="btn-ejornal btn-ejornal-gris-claro" type="button" name="button">Cambiar
							contraseña</button>
						@else
						<button data-toggle="modal" data-target="#cambiar_pass"
							class="btn-ejornal btn-ejornal-gris-claro" type="button" name="button">Resetear
							contraseña</button>
						@endif
					</div>
				</div>
			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>



<!-- Modal reset password-->
<div class="modal fade" id="cambiar_pass" tabindex="-1" aria-labelledby="cambiar_pass_titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cambiar_pass_titulo">Cambiar contraseña</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<form action="{{action('AdminUserController@reset_password', $user->id)}}" accept-charset="UTF-8"
					method="post">
					{{ csrf_field() }}
					{{-- <input name="_method" type="hidden" value="PUT"> --}}
					<input type="hidden" name="id_user" value="{{$user->id}}">
					<div class="row">
						<div class="form-group col-md-4">
							<label>Contraseña nueva</label>
							<input name="nueva_pass" type="password" class="form-control form-control-sm"
								placeholder="">
						</div>
						<div class="form-group col-md-4">
							<label>Confirmar contraseña nueva</label>
							<input name="confirm_nueva_pass" type="password" class="form-control form-control-sm"
								placeholder="">
						</div>
					</div>
					<button type="submit" class="btn-ejornal btn-ejornal-base">Guardar cambios</button>
					<button type="button" class="btn-ejornal btn-ejornal-gris-claro"
						data-dismiss="modal">Cerrar</button>
				</form>

			</div>

		</div>
	</div>
</div>



<!-- Modal Onedrive -->
<div class="modal fade" id="question_one_drive" tabindex="-1" aria-labelledby="question_one_drive_title"
	aria-hidden="true">
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
					De click derecho en la carpeta que desea compartir y dele al boton de "compartir."
					Allí siga los pasos para obtener el link para compartir.
				</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Ok</button>
			</div>
		</div>
	</div>
</div>

@endsection