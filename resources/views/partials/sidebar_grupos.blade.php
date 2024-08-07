<div class="bg_sidebar border-right" id="sidebar-wrapper">
	<div class="sidebar-heading logo_sidebar">
		<img src="{{asset('img/logos/isologo.png')}}" alt="">
	</div>

	<div class="sidebar_datos_user">
    <i class="fas fa-user"></i>
    <small>{{$grupo->nombre}}</small>
    <span>{{auth()->user()->nombre}}</span>
    @if($grupo->clientes)
    <div class="form-group">
    	<select name="select_clientes_sidebar" id="cliente_seleccionado_sidebar_grupo" class="form-control form-control-sm">
        @if (is_null($cliente_actual))
          <option value="">--Seleccionar Cliente--</option>
        @endif
        @foreach ($grupo->clientes as $cliente_grupo)
          <option {{$cliente_grupo->id == auth()->user()->id_cliente_actual ? 'selected' : ''}} value="{{$cliente_grupo->id}}">{{$cliente_grupo->nombre}}</option>
        @endforeach
    	</select>
    </div>
    @else
    [no hay asociado ningún cliente]
    @endif
  </div>


  <div class="list-group list-group-flush sidebar_menu">


    <li class="{{ setActive('/grupos/resumen') }} menu_sin_sub_menu">
      <i class="fas fa-tachometer-fast"></i>
      <a href="{{url('/grupos/resumen')}}" class="list-group-item list-group-item-action sidebar_item">Resumen Global</a>
    </li>

    <li class="{{ setActive('/grupos/resumen_cliente') }} menu_sin_sub_menu">
      <i class="fas fa-tachometer-fast"></i>
      <a href="{{url('/grupos/resumen_cliente')}}" class="list-group-item list-group-item-action sidebar_item">
        <div>Resumen Cliente</div>
        @if (!is_null($cliente_actual))
        <div class="small font-italic">{{$cliente_actual->nombre }}</div>
        @endif
      </a>
    </li>


     <li class="{{ setActive('/grupos/cuenta') }} menu_sin_sub_menu">
      <i class="fas fa-file-invoice"></i>
      <a href="{{url('/grupos/cuenta')}}" class="list-group-item list-group-item-action sidebar_item">Mi cuenta</a>
    </li>

    <li class="{{ setActive('/grupos/nominas') }} menu_sin_sub_menu">
      <i class="fas fa-users"></i>
      <a href="{{url('/grupos/nominas')}}" class="list-group-item list-group-item-action sidebar_item">Nomina</a>
    </li>
    <li class="{{ setActive('/grupos/ausentismos') }} menu_sin_sub_menu">
      <i class="fas fa-user-times"></i>
      <a href="{{url('/grupos/ausentismos')}}" class="list-group-item list-group-item-action sidebar_item">Ausentismos</a>
    </li>
		<li class="{{ setActive('/grupos/api') }} menu_sin_sub_menu">
      <i class="far fa-network-wired"></i>
      <a href="{{url('/grupos/api')}}" class="list-group-item list-group-item-action sidebar_item">Api</a>
    </li>

  </div>

</div>
