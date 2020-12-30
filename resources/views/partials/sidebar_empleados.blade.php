<div class="bg_sidebar border-right" id="sidebar-wrapper">
    <div class="sidebar-heading logo_sidebar">
        <img src="{{asset('img/logo_white.png')}}" alt="">
    </div>
    <div class="sidebar_datos_user">
        <i class="fas fa-user"></i>
        <small>Empleado eJornal</small>
        <span>{{auth()->user()->nombre}}</span>
        @if (!isset($clientes) || empty($clientes))
        <p>Sin empresas asignadas</p>
        @else
        <div class="form-group">
            <select class="form-control form-control-sm">
              @foreach ($clientes as $cliente)
              <option value="{{$cliente->id}}">{{$cliente->nombre}}</option>
              @endforeach
            </select>
        </div>
        @endif
    </div>
    <div class="list-group list-group-flush sidebar_menu">
        <li class="{{ setActive('/empleados/resumen') }} menu_sin_sub_menu">
            <i class="fas fa-tachometer-fast"></i>
            <a href="{{url('/empleados/resumen')}}" class="list-group-item list-group-item-action sidebar_item">Resumen</a>
        </li>
        <li class="{{ setActive('/empleados/cuenta') }} dropdownMenu">
            <div class="dropdownButton">
                <i class="far fa-id-card"></i>
                <a href="#!" class="list-group-item list-group-item-action sidebar_item">Mi eJornal</a>
                <i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
            </div>
            <div class="dropdownContent">
                <ul>
                    <ol class="{{ setActiveSub('/empleados/cuenta') }}" onclick="javascript:location.href='{{url('/empleados/cuenta')}}'">
                        <a href="{{url('/empleados/cuenta')}}">Mi cuenta</a>
                    </ol>
                    <ol class="{{ setActiveSub('/empleados/liquidacion') }}" onclick="javascript:location.href='{{url('/empleados/liquidacion')}}'">
                        <a href="{{url('/empleados/liquidacion')}}">Liquidación</a>
                    </ol>
                </ul>
            </div>
        </li>
        <li class="{{ setActive('/empleados/nomina') }} dropdownMenu">
            <div class="dropdownButton">
                <i class="fas fa-users"></i>
                <a href="#!" class="list-group-item list-group-item-action sidebar_item">Nomina</a>
                <i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
            </div>
            <div class="dropdownContent">
                <ul>
                    <ol class="{{ setActiveSub('/empleados/nomina') }}" onclick="javascript:location.href='{{url('/empleados/nomina')}}'">
                        <a href="{{url('/empleados/nomina')}}">listado</a>
                    </ol>
                    <ol class="{{ setActiveSub('/empleados/importar') }}" onclick="javascript:location.href='{{url('/empleados/importar')}}'">
                        <a href="{{url('/empleados/importar')}}">importar</a>
                    </ol>
                </ul>
            </div>
        </li>
        <li class="{{ setActive('/empleados/ausentismos') }} menu_sin_sub_menu">
            <i class="fas fa-user-times"></i>
            <a href="{{url('/empleados/ausentismos')}}" class="list-group-item list-group-item-action sidebar_item">Ausentismos</a>
        </li>
        <li class="{{ setActive('/empleados/preocupacionales') }} menu_sin_sub_menu">
            <i class="fas fa-briefcase-medical"></i>
            <a href="{{url('/empleados/preocupacionales')}}" class="list-group-item list-group-item-action sidebar_item">Preocupacionales</a>
        </li>
        <li class="{{ setActive('/empleados/medicamentos') }} dropdownMenu">
            <div class="dropdownButton">
                <i class="fas fa-syringe"></i>
                <a href="#!" class="list-group-item list-group-item-action sidebar_item">Medicamentos</a>
                <i class="fal fa-chevron-circle-down flecha_sub_menu"></i>
            </div>
            <div class="dropdownContent">
                <ul>
                    <ol class="{{ setActiveSub('/empleados/medicamentos') }}" onclick="javascript:location.href='{{url('/empleados/medicamentos')}}'">
                        <a href="{{url('/empleados/medicamentos')}}">Listado</a>
                    </ol>
                    <ol class="{{ setActiveSub('/empleados/medicamentos/movimientos') }}" onclick="javascript:location.href='{{url('/empleados/medicamentos/movimientos')}}'">
                        <a href="{{url('/empleados/medicamentos/movimientos')}}">Movimientos</a>
                    </ol>
                </ul>
            </div>
        </li>
    </div>
</div>
