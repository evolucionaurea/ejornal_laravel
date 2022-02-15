<div class="bg_sidebar border-right" id="sidebar-wrapper">
    <div class="sidebar-heading logo_sidebar">
        <img src="{{asset('img/logo_white.png')}}" alt="">
    </div>
    <div class="sidebar_datos_user">
        <i class="fas fa-user"></i>
        <small>Admin eJornal</small>
        <span>{{auth()->user()->nombre}}</span>
    </div>
    <div class="list-group list-group-flush sidebar_menu">
        <li class="{{ setActive('/admin/resumen') }} menu_sin_sub_menu">
            <i class="fas fa-tachometer-fast"></i>
            <a href="{{url('/admin/resumen')}}" class="list-group-item list-group-item-action sidebar_item">Resumen</a>
        </li>
        <li class="{{ setActive('/admin/cuenta') }} menu_sin_sub_menu">
            <i class="fas fa-file-invoice"></i>
            <a href="{{url('/admin/cuenta')}}" class="list-group-item list-group-item-action sidebar_item">Mi cuenta</a>
        </li>
        <li class="{{ setActive('/admin/users') }} dropdownMenu">
            <div class="dropdownButton">
                <i class="fas fa-user"></i>
                <a href="#!" class="list-group-item list-group-item-action sidebar_item">Usuarios</a>
                <i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
            </div>
            <div class="dropdownContent">
                <ul>
                    <ol class="{{ setActiveSub('/admin/users') }}" onclick="javascript:location.href='{{url('/admin/users')}}'">
                        <a href="{{url('/admin/users')}}">Listado</a>
                    </ol>
                    <ol class="{{ setActiveSub('/admin/users/create') }}" onclick="javascript:location.href='{{url('admin/users/create')}}'">
                        <a href="{{url('admin/users/create')}}">Crear usuario</a>
                    </ol>
                    <ol class="{{ setActiveSub('/admin/users_fichadas_nuevas') }}" onclick="javascript:location.href='{{url('admin/users_fichadas_nuevas')}}'">
                        <a href="{{url('admin/users_fichadas_nuevas')}}">Fichadas</a>
                    </ol>
                </ul>
            </div>
        </li>
        <li class="{{ setActive('/admin/clientes') }} dropdownMenu">
            <div class="dropdownButton">
                <i class="fas fa-briefcase"></i>
                <a href="#!" class="list-group-item list-group-item-action sidebar_item">Clientes</a>
                <i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
            </div>
            <div class="dropdownContent">
                <ul>
                    <ol class="{{ setActiveSub('/admin/clientes') }}" onclick="javascript:location.href='{{url('/admin/clientes')}}'">
                        <a href="{{url('/admin/clientes')}}">Listado</a>
                    </ol>
                    <ol class="{{ setActiveSub('/admin/clientes/create') }}" onclick="javascript:location.href='{{url('/admin/create')}}'">
                        <a href="{{url('/admin/clientes/create')}}">Crear cliente</a>
                    </ol>
                </ul>
            </div>
        </li>
        <li class="{{ setActive('/admin/medicamentos') }} dropdownMenu">
            <div class="dropdownButton">
                <i class="fas fa-clinic-medical"></i>
                <a href="#!" class="list-group-item list-group-item-action sidebar_item">Medicamentos</a>
                <i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
            </div>
            <div class="dropdownContent">
                <ul>
                    <ol class="{{ setActiveSub('/admin/medicamentos') }}" onclick="javascript:location.href='{{url('/admin/medicamentos')}}'">
                        <a href="{{url('/admin/medicamentos')}}">Listado</a>
                    </ol>
                    <ol class="{{ setActiveSub('/admin/movimiento_medicamentos') }}" onclick="javascript:location.href='{{url('/admin/movimiento_medicamentos')}}'">
                        <a href="{{url('/admin/movimiento_medicamentos')}}">Movimientos</a>
                    </ol>
                </ul>
            </div>
        </li>
        <li class="{{ setActive('/admin/medicamentos') }} dropdownMenu">
            <div class="dropdownButton">
                <i class="far fa-chart-bar"></i>
                <a href="#!" class="list-group-item list-group-item-action sidebar_item">Reportes</a>
                <i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
            </div>
            <div class="dropdownContent">
                <ul>
                    <ol class="{{ setActiveSub('/admin/reportes_fichadas_nuevas') }}" onclick="javascript:location.href='{{url('/admin/reportes_fichadas_nuevas')}}'">
                        <a href="{{url('/admin/reportes_fichadas_nuevas')}}">Fichadas</a>
                    </ol>
                    <ol class="{{ setActiveSub('/admin/reportes_ausentismos') }}" onclick="javascript:location.href='{{url('/admin/reportes_ausentismos')}}'">
                        <a href="{{url('/admin/reportes_ausentismos')}}">Ausentismos</a>
                    </ol>
                    <ol class="{{ setActiveSub('/admin/reportes_certificaciones') }}" onclick="javascript:location.href='{{url('/admin/reportes_certificaciones')}}'">
                        <a href="{{url('/admin/reportes_certificaciones')}}">Certificaciones</a>
                    </ol>
                    <ol class="{{ setActiveSub('/admin/reportes_consultas') }}" onclick="javascript:location.href='{{url('/admin/reportes_consultas')}}'">
                        <a href="{{url('/admin/reportes_consultas')}}">Consultas</a>
                    </ol>
                    <ol class="{{ setActiveSub('/admin/reportes_comunicaciones') }}" onclick="javascript:location.href='{{url('/admin/reportes_comunicaciones')}}'">
                        <a href="{{url('/admin/reportes_comunicaciones')}}">Comunicaciones</a>
                    </ol>
                </ul>
            </div>
        </li>
        @if (auth()->user()->email == 'sebas_admin@ejornal.com.ar')
            <li class="{{ setActive('/admin/migrar') }} menu_sin_sub_menu">
              <i class="fas fa-sync"></i>
              <a href="{{url('/admin/migrar')}}" class="list-group-item list-group-item-action sidebar_item">Migraciones de datos</a>
            </li>
        @endif
    </div>
</div>
