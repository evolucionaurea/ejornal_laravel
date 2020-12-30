<div class="bg_sidebar border-right" id="sidebar-wrapper">
    <div class="sidebar-heading logo_sidebar">
        <img src="{{asset('img/logo_white.png')}}" alt="">
    </div>
    <div class="sidebar_datos_user">
        <i class="fas fa-user"></i>
        <small>{{$cliente->nombre}}</small>
        <span>{{auth()->user()->nombre}}</span>
    </div>
    <div class="list-group list-group-flush sidebar_menu">
        <li class="{{ setActive('/empleados/resumen') }} menu_sin_sub_menu">
            <i class="fas fa-tachometer-fast"></i>
            <a href="{{url('/empleados/resumen')}}" class="list-group-item list-group-item-action sidebar_item">Resumen</a>
        </li>
    </div>
</div>
