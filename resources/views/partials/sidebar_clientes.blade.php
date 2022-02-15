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
            <i class="fas fa-tachometer-fast"></i>
            <a href="{{url('/clientes/resumen')}}" class="list-group-item list-group-item-action sidebar_item">Resumen</a>
        </li>
        <li class="{{ setActive('/clientes/cuenta') }} menu_sin_sub_menu">
            <i class="fas fa-file-invoice"></i>
            <a href="{{url('/clientes/cuenta')}}" class="list-group-item list-group-item-action sidebar_item">Mi cuenta</a>
        </li>
        <li class="{{ setActive('/clientes/nominas') }} menu_sin_sub_menu">
            <i class="fas fa-users"></i>
            <a href="{{url('/clientes/nominas')}}" class="list-group-item list-group-item-action sidebar_item">Nomina</a>
        </li>
        <li class="{{ setActive('/clientes/ausentismos') }} menu_sin_sub_menu">
            <i class="fas fa-user-times"></i>
            <a href="{{url('/clientes/ausentismos')}}" class="list-group-item list-group-item-action sidebar_item">Ausentismos</a>
        </li>
        <li class="{{ setActive('/clientes/api') }} menu_sin_sub_menu">
            <i class="far fa-network-wired"></i>
            <a href="{{url('/clientes/api')}}" class="list-group-item list-group-item-action sidebar_item">Api</a>
        </li>
    </div>
</div>
