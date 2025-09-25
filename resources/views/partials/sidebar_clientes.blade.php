<div class="bg_sidebar border-right" id="sidebar-wrapper">
	<div class="sidebar-heading logo_sidebar">
		<img src="{{asset('img/logos/isologo.png')}}" alt="">
	</div>
	<div class="sidebar_datos_user">
		<i class="fas fa-user"></i>
		<small>{{$cliente->nombre}}</small>
		<span>{{auth()->user()->nombre}}</span>
	</div>
	<div class="list-group list-group-flush sidebar_menu">
		<li class="{{ setActive('/clientes/resumen') }} menu_sin_sub_menu">
			<a href="{{url('/clientes/resumen')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-tachometer-fast"></i>
				<span>Resumen</span>
			</a>
		</li>
		<li class="{{ setActive('/clientes/cuenta') }} menu_sin_sub_menu">
			<a href="{{url('/clientes/cuenta')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-file-invoice"></i>
				<span>Mi cuenta</span>
			</a>
		</li>
		<li class="{{ setActive('/clientes/nominas') }} menu_sin_sub_menu">
			<a href="{{url('/clientes/nominas')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-users"></i>
				<span>Nómina</span>
			</a>
		</li>
		<li class="{{ setActive('/clientes/ausentismos') }} menu_sin_sub_menu">
			<a href="{{url('/clientes/ausentismos')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-user-times"></i>
				<span>Ausentismos</span>
			</a>
		</li>
		<li class="{{ setActive('/clientes/preocupacionales') }} menu_sin_sub_menu">
			<a href="{{url('/clientes/preocupacionales')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="fas fa-book-medical"></i>
				<span>Est. Méd. Complement</span>
			</a>
		</li>
		<li class="{{ setActive('/clientes/api') }} menu_sin_sub_menu">
			<a href="{{url('/clientes/api')}}" class="list-group-item list-group-item-action sidebar_item">
				<i class="far fa-network-wired"></i>
				<span>Api</span>
			</a>
		</li>
	</div>
</div>