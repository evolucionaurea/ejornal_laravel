<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
  <button id="hamburguesa"><i class="fas fa-bars"></i></button>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <i class="fas fa-ellipsis-v"></i>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto mt-2 mt-lg-0 d-flex align-items-center">
      @if (auth()->user()->id_rol == 2)
        <li class="nav-item" style="margin-right: 15px;">
          <a class="click_fichada_huella" data-toggle="modal" data-target="#fichada_huella" class="nav-link" href="#">
            <i class="fas fa-fingerprint fa-lg @php
            if (auth()->user()->fichada == 0) {
              echo "text-danger";
            }else {
              echo "text-success";
            }
            @endphp "></i>
          </a>
        </li>
      @endif
      <li class="nav-item">
        <a class="nav-link" href="{{route('logout')}}"><i class="fas fa-sign-out-alt"></i></i>Salir</a>
      </li>
    </ul>
  </div>
</nav>



<!-- Modal -->
<div class="modal fade" id="fichada_huella" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="fichada_modal_titulo" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="fichada_modal_titulo">Fichada</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container text-center">
          <i class="fas fa-fingerprint fa-10x grey-text"></i>
        </div>
        <div class="container">
          <form class="" action="{{action('EmpleadosFichadasNuevasController@store')}}" method="post">
            @csrf
            <input type="hidden" name="id_user" value="{{auth()->user()->id}}">
            @if (auth()->user()->fichada == 0)
                <button type="submit" class="btn btn-default btn-md btn-block text-white">Fichar entrada</button>
              @else
                <button type="submit" class="btn btn-danger btn-md btn-block text-white">Fichar salida</button>
            @endif
          </form>
        </div>
        <div class="row container">
          <div class="col-md-6 text-left">
            <h6>Fecha actual:</h6>
            <small class="fichada_fecha_actual"></small>
          </div>
          <div id="nombre_fichada_empresa" class="col-md-6 text-right">
            <h6>Empresa</h6>
            <small class="trabajando_para"></small>
          </div>
          <div class="col-md-6 text-left">
            <h6>Hora actual:</h6>
            <small class="reloj_hora_actual"></small>
          </div>
          <div class="col-md-6 text-right">
            <h6>Trabajando desde:</h6>
            @if (auth()->user()->fichada == 0)
                No has fichado
              @else
                <small class="estado_trabajando_desde"></small>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
