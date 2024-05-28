<div class="bg_sidebar border-right" id="sidebar-wrapper">
	<div class="sidebar-heading logo_sidebar">
		<img src="{{asset('img/logos/isologo.png')}}" alt="">
	</div>
	<div class="sidebar_datos_user">
		<i class="fas fa-user"></i>
		<small>Empleado eJornal</small>
		<span>{{auth()->user()->nombre}}</span>
		@if (!isset($clientes) || empty($clientes) || count($clientes) < 1) <p>Sin empresas asignadas</p>
			@else
			<div class="form-group">
				@php
				$url_actual = request()->url();
				@endphp

				@if (strpos($url_actual, 'create') == true || strpos($url_actual, 'edit') == true || strpos($url_actual,
				'show') == true)
				<select disabled name="select_clientes_sidebar" id="cliente_seleccionado_sidebar" class="form-control form-control-sm">
				@else
				<select name="select_clientes_sidebar" id="cliente_seleccionado_sidebar" class="form-control form-control-sm">
				@endif

					@foreach ($clientes as $cliente)
					@if ($cliente->id == auth()->user()->id_cliente_actual)
					<option selected value="{{$cliente->id}}">{{$cliente->nombre}}</option>
					@else
					<option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
					@endif
					@endforeach

				</select>
			</div>
			@endif
	</div>

	{{-- Este dato es para tomarlo por JS y saber si está trabajando actualmente (osea si fichó). En sidebar.js se usa
	el dato --}}
	<input class="id_usuario" type="hidden" name="" value="{{auth()->user()->id}}">
	<input class="debe_fichar" type="hidden" name="" value="{{(int) auth()->user()->fichar}}">
	<input class="empleado_trabajando_saber" type="hidden" name="" value="{{auth()->user()->fichada}}">
	<input class="id_cliente_actual" type="hidden" name="" value="{{auth()->user()->id_cliente_actual}}">
	{{-- Este dato es para tomarlo por JS y saber si está trabajando actualmente (osea si fichó). En sidebar.js se usa
	el dato --}}

	@if (!isset($clientes) || empty($clientes) || count($clientes) < 1) <ul
		class="list-group list-group-flush sidebar_menu">
		<li class="{{ setActive('/empleados/resumen') }} menu_sin_sub_menu">
			<i class="fas fa-tachometer-fast"></i>
			<a href="{{url('/empleados/resumen')}}"
				class="list-group-item list-group-item-action sidebar_item">Resumen</a>
		</li>
		</ul>
		@else

		<ul class="list-group list-group-flush sidebar_menu">
			<li class="{{ setActive('/empleados/resumen') }} menu_sin_sub_menu">
				<i class="fas fa-tachometer-fast"></i>
				<a href="{{url('/empleados/resumen')}}"
					class="list-group-item list-group-item-action sidebar_item">Resumen</a>
			</li>
			<li class="{{ setActive('/empleados/nominas') }} dropdownMenu">
				<div class="dropdownButton">
					<i class="fas fa-user"></i>
					<a href="#!" class="list-group-item list-group-item-action sidebar_item">Nóminas</a>
					<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
				</div>
				<div class="dropdownContent">

					<ul>
						<ol data-route="/empleados/nominas" class="{{ setActiveSub('/empleados/nominas') }}"
							onclick="javascript:location.href='{{url('/empleados/nominas')}}'">
							<a href="{{url('/empleados/nominas')}}">Listado</a>
						</ol>
						@if(auth()->user()->fichada == 1 || !auth()->user()->fichar)
						<ol data-route="nominas.create" class="{{ setActiveSub('nominas.create') }}"
							onclick="javascript:location.href='{{route('nominas.create')}}'">
							<a href="{{route('nominas.create')}}">Crear Trabajador</a>
						</ol>
						@endif
					</ul>
				</div>
			</li>
			<li class="{{ setActive('/empleados/ausentismos') }} dropdownMenu">
				<div class="dropdownButton">
					<i class="fas fa-user-times"></i>
					<a href="#!" class="list-group-item list-group-item-action sidebar_item">Ausentismos</a>
					<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
				</div>
				<div class="dropdownContent">
					<ul>
						<ol data-route="/empleados/ausentismos" class="{{ setActiveSub('/empleados/ausentismos') }}"
							onclick="javascript:location.href='{{url('/empleados/ausentismos')}}'">
							<a href="{{url('/empleados/ausentismos')}}">Listado</a>
						</ol>
						@if (auth()->user()->fichada == 1 || !auth()->user()->fichar)
						<ol data-route="ausentismos.create" class="{{ setActiveSub('ausentismos.create') }}"
							onclick="javascript:location.href='{{url('/empleados/ausentismos/create')}}'">
							<a href="{{url('/empleados/ausentismos/create')}}">Agregar Ausentismo</a>
						</ol>
						@endif
						<ol data-route="/empleados/comunicaciones"
							class="{{ setActiveSub('/empleados/comunicaciones') }}"
							onclick="javascript:location.href='{{url('/empleados/comunicaciones')}}'">
							<a href="{{url('/empleados/comunicaciones')}}">Comunicaciones</a>
						</ol>
						<ol data-route="/empleados/certificados" class="{{ setActiveSub('/empleados/certificados') }}"
							onclick="javascript:location.href='{{url('/empleados/certificados')}}'">
							<a href="{{url('/empleados/certificados')}}">Certificados</a>
						</ol>
					</ul>
				</div>
			</li>
			<li class="{{ setActive('/empleados/tareas_livianas') }} dropdownMenu">
				<div class="dropdownButton">
					<i class="fas fa-laptop-medical"></i>
					<a href="#!" class="list-group-item list-group-item-action sidebar_item">Tareas adecuadas</a>
					<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
				</div>
				<div class="dropdownContent">
					<ul>
						<ol data-route="/empleados/tareas_livianas"
							class="{{ setActiveSub('/empleados/tareas_livianas') }}"
							onclick="javascript:location.href='{{url('/empleados/tareas_livianas')}}'">
							<a href="{{url('/empleados/tareas_livianas')}}">Listado</a>
						</ol>
						@if (auth()->user()->fichada == 1 || !auth()->user()->fichar)
						<ol data-route="tareas_livianas.create" class="{{ setActiveSub('tareas_livianas.create') }}"
							onclick="javascript:location.href='{{url('/empleados/tareas_livianas/create')}}'">
							<a href="{{url('/empleados/tareas_livianas/create')}}">Agregar Tarea Adecuada</a>
						</ol>
						@endif
						<ol data-route="/empleados/comunicaciones_livianas"
							class="{{ setActiveSub('/empleados/comunicaciones_livianas') }}"
							onclick="javascript:location.href='{{url('/empleados/comunicaciones_livianas')}}'">
							<a href="{{url('/empleados/comunicaciones_livianas')}}">Comunicaciones</a>
						</ol>
						<ol data-route="/empleados/certificados_livianos"
							class="{{ setActiveSub('/empleados/certificados_livianos') }}"
							onclick="javascript:location.href='{{url('/empleados/certificados_livianos')}}'">
							<a href="{{url('/empleados/certificados_livianos')}}">Certificados</a>
						</ol>
					</ul>
				</div>
			</li>
			<li class="{{ setActive('/empleados/consultas') }} dropdownMenu">
				<div class="dropdownButton">
					<i class="fas fa-user-md"></i>
					<a href="#!" class="list-group-item list-group-item-action sidebar_item">Consultas</a>
					<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
				</div>
				<div class="dropdownContent">
					<ul>
						<ol data-route="empleados.consultas.todas"
							class="{{ setActiveSub('empleados.consultas.todas') }}"
							onclick="javascript:location.href='{{url('/empleados/consultas/todas')}}'">
							<a href="{{url('/empleados/consultas/todas')}}">Ver todas</a>
						</ol>
						<ol data-route="empleados.consultas.medicas"
							class="{{ setActiveSub('empleados.consultas.medicas') }}"
							onclick="javascript:location.href='{{url('/empleados/consultas/medicas')}}'">
							<a href="{{url('/empleados/consultas/medicas')}}">Médicas</a>
						</ol>
						<ol data-route="empleados.consultas.enfermeria"
							class="{{ setActiveSub('empleados.consultas.enfermeria') }}"
							onclick="javascript:location.href='{{url('/empleados/consultas/enfermeria')}}'">
							<a href="{{url('/empleados/consultas/enfermeria')}}">Enfermería</a>
						</ol>
					</ul>
				</div>
			</li>
			{{-- <li class="{{ setActive('/empleados/covid') }} dropdownMenu">
				<div class="dropdownButton">
					<i class="fas fa-clinic-medical"></i>
					<a href="#!" class="list-group-item list-group-item-action sidebar_item">Covid 19</a>
					<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
				</div>
				<div class="dropdownContent">
					<ul>
						<ol data-route="empleados.covid.testeos" class="{{ setActiveSub('empleados.covid.testeos') }}"
							onclick="javascript:location.href='{{url('/empleados/covid/testeos')}}'">
							<a href="{{url('/empleados/covid/testeos')}}">Testeos</a>
						</ol>
						<ol data-route="empleados.covid.vacunas" class="{{ setActiveSub('empleados.covid.vacunas') }}"
							onclick="javascript:location.href='{{url('/empleados/covid/vacunas')}}'">
							<a href="{{url('/empleados/covid/vacunas')}}">Vacunas</a>
						</ol>
					</ul>
				</div>
			</li> --}}
			<li class="{{ setActive('/empleados/medicamentos') }} dropdownMenu">
				<div class="dropdownButton">
					<i class="fas fa-syringe"></i>
					<a href="#!" class="list-group-item list-group-item-action sidebar_item">Medicamentos</a>
					<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
				</div>
				<div class="dropdownContent">
					<ul>
						<ol data-route="/empleados/medicamentos" class="{{ setActiveSub('/empleados/medicamentos') }}"
							onclick="javascript:location.href='{{url('/empleados/medicamentos')}}'">
							<a href="{{url('/empleados/medicamentos')}}">Listado</a>
						</ol>
						<ol data-route="/empleados/medicamentos_movimientos"
							class="{{ setActiveSub('/empleados/medicamentos_movimientos') }}"
							onclick="javascript:location.href='{{url('/empleados/medicamentos_movimientos')}}'">
							<a href="{{url('/empleados/medicamentos_movimientos')}}">Movimientos</a>
						</ol>
					</ul>
				</div>
			</li>
			<li class="{{ setActive('/empleados/preocupacionales') }} dropdownMenu">
				<div class="dropdownButton">
					<i class="fas fa-book-medical"></i>
					{{-- Est Med Comp es: Estudios medicos complementarios. La seccion real se llama preocupacionales
					pero luego
					decidieron cambiarle el nombre. Solo cambio los "label digamos". Rutas y demas queda igual --}}
					<a href="#!" class="list-group-item list-group-item-action sidebar_item">Estudio Médico Complementario</a>
					<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
				</div>
				<div class="dropdownContent">
					<ul>
						<ol data-route="/empleados/preocupacionales" class="{{ setActiveSub('/empleados/preocupacionales') }}" >
							<a href="{{url('/empleados/preocupacionales')}}">Listado</a>
						</ol>

						<ol data-route="preocupacionales.create" class="{{ setActiveSub('preocupacionales.create') }}">
							<a href="{{url('/empleados/preocupacionales/create')}}">Nuevo estudio médico compl.</a>
						</ol>

					</ul>
				</div>
			</li>
			<li class="{{ setActive('/empleados/cuenta') }} dropdownMenu">
				<div class="dropdownButton">
					<i class="fas fa-file-invoice"></i>
					<a href="#!" class="list-group-item list-group-item-action sidebar_item">Mi eJornal</a>
					<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
				</div>
				<div class="dropdownContent">
					<ul>
						<ol data-route="/empleados/cuenta" class="{{ setActiveSub('/empleados/cuenta') }}"
							onclick="javascript:location.href='{{url('/empleados/cuenta')}}'">
							<a href="{{url('/empleados/cuenta')}}">Mi cuenta</a>
						</ol>
						<ol data-route="/empleados/liquidacion" class="{{ setActiveSub('/empleados/liquidacion') }}"
							onclick="javascript:location.href='{{url('/empleados/liquidacion')}}'">
							<a href="{{url('/empleados/liquidacion')}}">Liquidacion</a>
						</ol>
					</ul>
				</div>
			</li>
		</ul>
		@endif

</div>



<!-- Modal -->
<div class="modal fade" id="modal_alerta_cliente_trabajando" tabindex="-1"
	aria-labelledby="cliente_trabajando_modal_titulo" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cliente_trabajando_modal_titulo">Advertencia</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<i class="fas fa-exclamation-triangle fa-8x text_danger"></i>
					</div>
					<div class="col-md-12">
						<p>
							No puedes cambiar de cliente mientras estás trabajando. Debes fichar tu salida del cliente
							actual para poder cambiar a otro. Arriba a la derecha veras la huella de tu fichada en
							verde.
							Dale click para fichar tu salida y pasará a roja.
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>