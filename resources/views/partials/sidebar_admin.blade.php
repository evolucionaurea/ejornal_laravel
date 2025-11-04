<div class="bg_sidebar border-right" id="sidebar-wrapper">
	<div class="sidebar-heading logo_sidebar">
		<img src="{{asset('img/logos/isologo.png')}}" alt="">
	</div>

	<div class="sidebar_datos_user">
		<i class="fas fa-user"></i>
		<small>Admin eJornal</small>
		<span>{{auth()->user()->nombre}}</span>
	</div>


	<div class="list-group list-group-flush sidebar_menu">
		<li class="{{ setActive('/admin/resumen') }} menu_sin_sub_menu">
			<a href="{{url('/admin/resumen')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-tachometer-fast"></i>
				<span>Resumen</span>
			</a>
		</li>
		<li class="{{ setActive('/admin/cuenta') }} menu_sin_sub_menu">
			<a href="{{url('/admin/cuenta')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-file-invoice"></i>
				<span>Mi cuenta</span>
			</a>
		</li>

		<li class="{{ setActive('/admin/agendas') }} dropdownMenu">
			<div class="dropdownButton">
				<a href="#!" class="list-group-item list-group-item-action sidebar_item">
					<i class="fas fa-calendar"></i>
					<span>Agendas</span>
				</a>
				<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
			</div>
			<div class="dropdownContent">
				<ul>
					<ol data-route="/admin/agendas" class="{{ setActiveSub('/admin/agendas') }}">
						<a href="{{url('/admin/agendas')}}">Calendario</a>
					</ol>
					<ol data-route="/admin/agenda_motivos" class="{{ setActiveSub('/admin/agenda_motivos') }}">
						<a href="{{url('/admin/agenda_motivos')}}">Motivos</a>
					</ol>
				</ul>
			</div>
		</li>


		<li class="{{ setActive('/admin/monitoreo') }} menu_sin_sub_menu">
			<a href="{{url('/admin/monitoreo')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-desktop"></i>
				<span>Monitoreo</span>
			</a>
		</li>

		<li class="{{ setActive('/admin/users') }} dropdownMenu">
			<div class="dropdownButton">
				<a href="#!" class="list-group-item list-group-item-action sidebar_item">
					<i class="fas fa-user"></i>
					<span>Usuarios</span>
				</a>
				<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
			</div>
			<div class="dropdownContent">
				<ul>
					<ol data-route="/admin/users" class="{{ setActiveSub('/admin/users') }}">
						<a href="{{url('/admin/users')}}">Listado</a>
					</ol>
					<ol data-route="users.create" class="{{ setActiveSub('users.create') }}">
						<a href="{{url('admin/users/create')}}">Crear usuario</a>
					</ol>
					{{-- <ol class="{{ setActiveSub('/admin/users_fichadas_nuevas') }}">
						<a href="{{url('admin/users_fichadas_nuevas')}}">Fichadas</a>
					</ol> --}}
				</ul>
			</div>
		</li>

		<li class="{{ setActive('/admin/clientes') }} dropdownMenu">
			<div class="dropdownButton">
				<a href="#!" class="list-group-item list-group-item-action sidebar_item">
					<i class="fas fa-briefcase"></i>
					<span>Clientes</span>
				</a>
				<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
			</div>
			<div class="dropdownContent">
				<ul>
					<ol data-route="/admin/clientes" class="{{ setActiveSub('/admin/clientes') }}">
						<a href="{{url('/admin/clientes')}}">Listado</a>
					</ol>
					<ol data-route="admin.clientes.create" class="{{ setActiveSub('clientes.create') }}">
						<a href="{{url('/admin/clientes/create')}}">Crear cliente</a>
					</ol>
				</ul>
			</div>
		</li>
		<li class="{{ setActive('/admin/grupos') }} dropdownMenu">
			<div class="dropdownButton">
				<a href="#!" class="list-group-item list-group-item-action sidebar_item">
					<i class="fas fa-building"></i>
					<span>Grupos</span>
				</a>
				<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
			</div>
			<div class="dropdownContent">
				<ul>
					<ol data-route="/admin/grupos" class="{{ setActiveSub('/admin/grupos') }}">
						<a href="{{url('/admin/grupos')}}">Listado</a>
					</ol>
					<ol data-route="grupos.create" class="{{ setActiveSub('grupos.create') }}">
						<a href="{{url('/admin/grupos/create')}}">Crear grupo</a>
					</ol>
				</ul>
			</div>
		</li>
		<li class="{{ setActive('/admin/medicamentos') }} dropdownMenu">
			<div class="dropdownButton">
				<a href="#!" class="list-group-item list-group-item-action sidebar_item">
					<i class="fas fa-clinic-medical"></i>
					<span>Medicamentos</span>
				</a>
				<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
			</div>
			<div class="dropdownContent">
				<ul>
					<ol data-route="/admin/medicamentos" class="{{ setActiveSub('/admin/medicamentos') }}">
						<a href="{{url('/admin/medicamentos')}}">Listado</a>
					</ol>
					<ol data-route="/admin/movimiento_medicamentos"
						class="{{ setActiveSub('/admin/movimiento_medicamentos') }}">
						<a href="{{url('/admin/movimiento_medicamentos')}}">Movimientos</a>
					</ol>
				</ul>
			</div>
		</li>

		<li class="{{ setActive('/admin/recetas') }} menu_sin_sub_menu">
			<a href="{{url('/admin/recetas')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-file-medical"></i>
				<span>Recetas</span>
			</a>
		</li>

		<li class="{{ setActive('/admin/reportes') }} dropdownMenu">
			<div class="dropdownButton">
				<a href="#!" class="list-group-item list-group-item-action sidebar_item">
					<i class="far fa-chart-bar"></i>
					<span>Reportes</span>
				</a>
				<i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
			</div>
			<div class="dropdownContent">
				<ul>

					<ol data-route="/admin/reportes_fichadas_nuevas"
						class="{{ setActiveSub('/admin/reportes_fichadas_nuevas') }}">
						<a href="{{url('/admin/reportes_fichadas_nuevas')}}">Fichadas</a>
					</ol>
					<ol data-route="/admin/reportes/ediciones_fichadas"
						class="{{ setActiveSub('/admin/reportes/ediciones_fichadas') }}">
						<a href="{{url('/admin/reportes/ediciones_fichadas')}}">Ediciones Fichadas</a>
					</ol>

					<ol data-route="reportes_ausentismos" class="{{ setActiveSub('reportes_ausentismos') }}">
						<a href="{{url('/admin/reportes_ausentismos')}}">Ausentismos</a>
					</ol>
					<ol data-route="reportes_certificaciones" class="{{ setActiveSub('reportes_certificaciones') }}">
						<a href="{{url('/admin/reportes_certificaciones')}}">Certificaciones</a>
					</ol>
					<ol data-route="reportes_consultas" class="{{ setActiveSub('reportes_consultas') }}">
						<a href="{{url('/admin/reportes_consultas')}}">Consultas</a>
					</ol>
					<ol data-route="reportes_comunicaciones" class="{{ setActiveSub('reportes_comunicaciones') }}">
						<a href="{{url('/admin/reportes_comunicaciones')}}">Comunicaciones</a>
					</ol>

					<ol data-route="reportes_preocupacionales" class="{{ setActiveSub('reportes_preocupacionales') }}">
						<a href="{{url('/admin/reportes_preocupacionales')}}">Est. Médicos Complementarios</a>
					</ol>

					<ol data-route="reportes_tareas_adecuadas" class="{{ setActiveSub('reportes_tareas_adecuadas') }}">
						<a href="{{url('/admin/reportes_tareas_adecuadas')}}">Tareas Adecuadas</a>
					</ol>

					<ol data-route="/admin/reportes/actividad_usuarios"
						class="{{ setActiveSub('/admin/reportes/actividad_usuarios') }}">
						<a href="{{url('/admin/reportes/actividad_usuarios')}}">Actividad Usuarios</a>
					</ol>

				</ul>
			</div>
		</li>
		@if (auth()->user()->email == 'sebas_admin@ejornal.com.ar')
		<li class="{{ setActive('/admin/migrar') }} menu_sin_sub_menu">
			<a href="{{url('/admin/migrar')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-sync"></i>
				<span>Migraciones de datos</span>
			</a>
		</li>
		@endif
		<li class="{{ setActive('/admin/errores') }} menu_sin_sub_menu">
			<a href="{{url('/admin/errores')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-exclamation-triangle"></i>
				<span>Errores</span>
			</a>
		</li>
		<li class="{{ setActive('/admin/configuraciones') }} menu_sin_sub_menu">
			<a href="{{url('/admin/configuraciones')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-tools"></i>
				<span>Configuraciones</span>
			</a>
		</li>
	</div>
</div>