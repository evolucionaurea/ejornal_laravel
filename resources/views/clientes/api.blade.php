@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">
  @include('partials.sidebar_clientes')
  <div id="page-content-wrapper">
    @include('partials.nav_sup')


    {{-- Contenido de la pagina --}}

    <div class="cabecera">
      <h2>Api</h2>
      <p>Aquí puedes ver los recursos de los que dispone tu empresa para utilizar la API</p>
      <div class="cabecera_acciones">
        <a data-toggle="modal" data-target="#explicacion_api" class="btn-ejornal btn-ejornal-gris-claro" href="#">
          <i class="fas fa-question"></i>
          ¿Que es una API?
        </a>
      </div>
    </div>

    <input type="hidden" name="token" value="{{$cliente->token}}">

    <div class="tarjeta">
      <h4>Ausentismos del mes</h4>
      @if (isset($cliente->token) && $cliente->token != null)
      <div class="bloque_codigo">
        <p>Solicitud: <span class="badge badge-info">GET</span>
        <pre>fetch('https://e2.jornalsalud.com/api/ausentismos_mes_actual/{token}')
.then(response => response.json())
.then(data => console.log(data));
              </pre>
        </p>
        <hr>
        <p>Respuesta: <span class="badge badge-light">JSON</span>
          <small class="api_ausentismos_mes_results"></small>
          <br>
          <br>
          <a class="btn-ejornal btn-ejornal-base  click_api_ausentismos_mes" href="#!">Consultar API</a>
          <a class="btn-ejornal btn-ejornal-gris-claro  limpiar_api_ausentismos_mes" href="#!">Limpiar consulta</a>
        <pre class="mostrar_api_ausentismos_mes">
              </pre>
        </p>
      </div>
      @else
      <div class="alert alert-info">
        No puedes utilizar esta seccion hasta que no te hayan creado un token. Solicítaselo a tu administrador.
      </div>
      @endif
    </div>


    <div class="tarjeta">
      <h4>Ausentismos de hoy</h4>
      @if (isset($cliente->token) && $cliente->token != null)
      <div class="bloque_codigo">
        <p>Solicitud: <span class="badge badge-info">GET</span>
        <pre>fetch('https://e2.jornalsalud.com/api/ausentismos_hoy/{token}')
.then(response => response.json())
.then(data => console.log(data));
              </pre>
        </p>
        <hr>
        <p>Respuesta: <span class="badge badge-light">JSON</span>
          <small class="api_ausentismos_hoy_results"></small>
          <br>
          <br>
          <a class="btn-ejornal btn-ejornal-base  click_api_ausentismos_hoy" href="#!">Consultar API</a>
          <a class="btn-ejornal btn-ejornal-gris-claro  limpiar_api_ausentismos_hoy" href="#!">Limpiar consulta</a>
        <pre class="mostrar_api_ausentismos_hoy">
              </pre>
        </p>
      </div>
      @else
      <div class="alert alert-info">
        No puedes utilizar esta seccion hasta que no te hayan creado un token. Solicítaselo a tu administrador.
      </div>
      @endif
    </div>

    <div class="tarjeta">
      <h4>Mi nomina</h4>
      @if (isset($cliente->token) && $cliente->token != null)
      <div class="bloque_codigo">
        <p>Solicitud: <span class="badge badge-info">GET</span>
        <pre>fetch('https://e2.jornalsalud.com/api/get_nominas/{token}')
.then(response => response.json())
.then(data => console.log(data));
              </pre>
        </p>
        <hr>
        <p>Respuesta: <span class="badge badge-light">JSON </span>
          <small class="api_nominas_results"></small>
          <br>
          <br>
          <a class="btn-ejornal btn-ejornal-base  click_api_get_nominas" href="#!">Consultar API</a>
          <a class="btn-ejornal btn-ejornal-gris-claro  limpiar_api_get_nominas" href="#!">Limpiar consulta</a>
        <pre class="mostrar_api_get_nominas">
              </pre>
        </p>
      </div>
      @else
      <div class="alert alert-info">
        No puedes utilizar esta seccion hasta que no te hayan creado un token. Solicítaselo a tu administrador.
      </div>
      @endif
    </div>

    <div class="tarjeta">
      <h4>Agregar trabajador a la nómina</h4>
      @if (isset($cliente->token) && $cliente->token != null)
      <div class="bloque_codigo">
        <p>Solicitud: <span class="badge badge-success">POST</span>
        <pre>https://e2.jornalsalud.com/api/set_nominas
              </pre>
        </p>
        <hr>
        <p>Respuesta: <span class="badge badge-light">JSON</span>
          <br>
          <br>
          {{-- <a class="btn-ejornal btn-ejornal-base  click_api_set_nominas" href="#!">Consultar API</a>
          <a class="btn-ejornal btn-ejornal-gris-claro  limpiar_api_set_nominas" href="#!">Limpiar consulta</a> --}}
        <pre>Formato:
token: Enviar el token del cliente | required,
nombre: string|required
email: string|required
telefono: string|required
dni: string|required
estado: integer|required ( 1 es activo - 0 es inactivo)
sector: string|required</pre>
        </p>
      </div>
      @else
      <div class="alert alert-info">
        No puedes utilizar esta seccion hasta que no te hayan creado un token. Solicítaselo a tu administrador.
      </div>
      @endif
    </div>

    <div class="tarjeta">
      <h4>Eliminar trabajador de la nómina</h4>
      @if (isset($cliente->token) && $cliente->token != null)
      <p class="alert alert-danger">
        <b>¡Cuidado!</b>
        Utilice este recurso con mucho cuidado. El trabajador puede
        tener asociadas ausencias, preocupacionales, estudios medicos asociados,
        entre otras vinculaciones.
      </p>
      <div class="bloque_codigo">
        <p>Solicitud: <span class="badge badge-danger">DELETE</span>
        <pre>https://e2.jornalsalud.com/api/delete_nominas
              </pre>
        </p>
        <hr>
        <p>Respuesta: <span class="badge badge-light">JSON</span>
          <br>
          <br>
          {{-- <a class="btn-ejornal btn-ejornal-base click_api_delete_nominas" href="#">Consultar API</a> --}}
        <pre>Formato:
token: Enviar el token del cliente|required
id_nomina: integer|required
              </pre>
        </p>
      </div>
      @else
      <div class="alert alert-info">
        No puedes utilizar esta seccion hasta que no te hayan creado un token. Solicítaselo a tu administrador.
      </div>
      @endif
    </div>

    {{-- Contenido de la pagina --}}
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="explicacion_api" tabindex="-1" aria-labelledby="title_explicaicon_api" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title_explicaicon_api">API</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <div class="card mb-4">
          <h5 class="card-header">¿Que es una API?</h5>
          <div class="card-body">
            <p class="card-text">
              Una API es un puente entre sistemas. Es la "autopista" que conecta dos sistemas o que
              permite acceso a un sistema. Esa autopista tiene carriles. Estos carriles se llaman
              "Endpoints". Los endpoints son lo que encontrará a continuación. Parecen links visualmente.
            </p>
          </div>
        </div>
        <div class="card mb-4">
          <h5 class="card-header">¿Como se usa?</h5>
          <div class="card-body">
            <p>
              Al ser una herramienta técnica debe tener un programador para hacer uso de la misma.
            </p>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  window.onload = function() {

  let token = $("input[name='token']").val();

  // Ausentismos del mes actual //
  $( ".click_api_ausentismos_mes" ).click(function() {
    let url = '/api/ausentismos_mes_actual/';

    fetch(url+token)
    .then(response => response.json())
    .then(data =>{
      $('.mostrar_api_ausentismos_mes').html(JSON.stringify(data, undefined, 2))
      $('.api_ausentismos_mes_results').html(' ( ' + data.length + ' resultados )')
    });
    //api_ausentismos_mes_results
  });

  $( ".limpiar_api_ausentismos_mes" ).click(function() {
    $('.mostrar_api_ausentismos_mes').empty();
    $('.api_ausentismos_mes_results').empty();
  });
  // Ausentismos del mes actual //



  // Ausentismos de hoy //
  $( ".click_api_ausentismos_hoy" ).click(function() {
    let url = '/api/ausentismos_hoy/';

    fetch(url+token)
    .then(response => response.json())
    .then(data =>{
      $('.mostrar_api_ausentismos_hoy').html(JSON.stringify(data, undefined, 2))
      $('.api_ausentismos_hoy_results').html(' ( ' + data.length + ' resultados )')
    });

  });

  $( ".limpiar_api_ausentismos_hoy" ).click(function() {
    $('.mostrar_api_ausentismos_hoy').empty();
    $('.api_ausentismos_hoy_results').empty();
  });
  // Ausentismos de hoy //


  // Get Nominas //
  $( ".click_api_get_nominas" ).click(function() {
    let url = '/api/get_nominas/';

    fetch(url+token)
    .then(response => response.json())
    .then(data =>{
      $('.mostrar_api_get_nominas').html(JSON.stringify(data, undefined, 2))
      $('.api_nominas_results').html(' ( ' + data.length + ' resultados )')
    });

  });

  $( ".limpiar_api_get_nominas" ).click(function() {
    $('.mostrar_api_get_nominas').empty();
    $('.api_nominas_results').empty();
  });
  // Get Nominas //




  // Post Nominas //

  // $( ".click_api_set_nominas" ).click(function() {
  // let url = '/api/set_nominas';
  //
  // axios.post(url, {
  //     token: token,
  //     nombre: 'Persona Omega',
  //     email: 'omega@prueba.com',
  //     telefono: '25617854',
  //     dni: '203251587',
  //     estado: 1,
  //     sector: 'GERENCIA DE  LOGISTICA'
  //   })
  //   .then(function (response) {
  //     console.log(response);
  //   })
  //   .catch(function (error) {
  //     console.log(error);
  //   });
  //
  // });
  //
  //
  // $( ".limpiar_api_set_nominas" ).click(function() {
  //   $('.mostrar_api_set_nominas').empty();
  //   $('.api_set_nominas_results').empty();
  // });


  // Post Nominas //



  // Delete Nominas //

  // $( ".click_api_delete_nominas" ).click(function() {
  // let url = '/api/delete_nominas';
  //
  // axios.delete(url, {
  //     data: {
  //       token: token,
  //       id_nomina: 5047
  //     }
  //   })
  //   .then(function (response) {
  //     console.log(response);
  //   })
  //   .catch(function (error) {
  //     console.log(error);
  //   });
  //
  // });
  //
  //
  // $( ".limpiar_api_set_nominas" ).click(function() {
  //   $('.mostrar_api_set_nominas').empty();
  //   $('.api_set_nominas_results').empty();
  // });


  // Delete Nominas //


};



</script>

@endsection