@extends('partials.layout')

@section('title', 'Clientes')

@section('content')

<div class="d-flex" id="wrapper">
    @include('partials.sidebar_clientes')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="container">
            <section>

              <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                  <div class="media white z-depth-1 rounded">
                    <i class="fas fa-user-md fa-lg blue z-depth-1 p-4 rounded-left text-white mr-3"></i>
                    <div class="media-body p-1">
                      <p class="text-uppercase text-muted mb-1"><small>Accidentes mes actual</small></p>
                      <h5 class="font-weight-bold mb-0">{{$accidentes_mes_actual}}</h5>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="media white z-depth-1 rounded">
                        <i class="fas fa-user-md fa-lg pink z-depth-1 p-4 rounded-left text-white mr-3"></i>
                        <div class="media-body p-1">
                            <p class="text-uppercase text-muted mb-1"><small>Accidentes mes pasado</small></p>
                            <h5 class="font-weight-bold mb-0">{{$accidentes_mes_pasado}}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="media white z-depth-1 rounded">
                        <i class="fas fa-user-times fa-lg teal z-depth-1 p-4 rounded-left text-white mr-3"></i>
                        <div class="media-body p-1">
                            <p class="text-uppercase text-muted mb-1"><small>Ausentismos mes actual</small></p>
                            <h5 class="font-weight-bold mb-0">{{$ausentismos_mes_actual}}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="media white z-depth-1 rounded">
                        <i class="fas fa-user-times fa-lg pink z-depth-1 p-4 rounded-left text-white mr-3"></i>
                        <div class="media-body p-1">
                            <p class="text-uppercase text-muted mb-1"><small>Ausentismos mes pasado</small></p>
                            <h5 class="font-weight-bold mb-0">{{$ausentismos_mes_pasado}}</h5>
                        </div>
                    </div>
                </div>
              </div>


              <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                  <div class="media white z-depth-1 rounded">
                    <i class="fas fa-user-md fa-lg secondary-color-dark z-depth-1 p-4 rounded-left text-white mr-3"></i>
                    <div class="media-body p-1">
                      <p class="text-uppercase text-muted mb-1"><small>Ausencias del día por Covid</small></p>
                      <h5 class="font-weight-bold mb-0">{{$ausencia_covid}}</h5>
                    </div>
                  </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="media white z-depth-1 rounded">
                        <i class="fas fa-user-md fa-lg secondary-color-dark z-depth-1 p-4 rounded-left text-white mr-3"></i>
                        <div class="media-body p-1">
                            <p class="text-uppercase text-muted mb-1"><small>Con al menos 1 dosis</small></p>
                            <h5 class="font-weight-bold mb-0">{{$cant_vacunados_una_dosis}}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="media white z-depth-1 rounded">
                        <i class="fas fa-user-times fa-lg secondary-color-dark z-depth-1 p-4 rounded-left text-white mr-3"></i>
                        <div class="media-body p-1">
                            <p class="text-uppercase text-muted mb-1"><small>Con 2 dosis</small></p>
                            <h5 class="font-weight-bold mb-0">{{$cant_vacunados_dos_dosis}}</h5>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="media white z-depth-1 rounded">
                        <i class="fas fa-user-times fa-lg secondary-color-dark z-depth-1 p-4 rounded-left text-white mr-3"></i>
                        <div class="media-body p-1">
                            <p class="text-uppercase text-muted mb-1"><small>Con 3 dosis</small></p>
                            <h5 class="font-weight-bold mb-0">{{$cant_vacunados_tres_dosis}}</h5>
                        </div>
                    </div>
                </div>
              </div>



                <div class="row d-flex justify-content-center">
                    <div class="col-md-5 tarjeta">
                        <h2 class="text-center">Ausentismos del mes</h2>
                        <div class="alert alert-info resumen_graficos_ausentismos_mes">
                          No hay datos
                        </div>
                        <canvas id="chart_accidentes"></canvas>
                    </div>
                    <div class="col-md-5 tarjeta">
                        <h2 class="text-center">Ausentismos del año</h2>
                        <div class="alert alert-info resumen_graficos_ausentismos_anual">
                          No hay datos
                        </div>
                        <canvas id="chart_accidentes_anual"></canvas>
                    </div>
                </div>
            </section>
        </div>


        <div class="row">
          <div class="col-6">
            <div class="tarjeta">
              <h4>Top 10 trabajadores que mas dias faltaron</h4>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Trabajador</th>
                    <th scope="col">Dias</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($top_10_ausentismos as $top)
                    <tr>
                      <td>{{$top['info']->trabajador}}</td>
                      <td>{{$top['dias_ausente']}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-6">
            <div class="tarjeta">
              <h4>Top trabajadores que mas veces solicitaron faltar</h4>
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th scope="col">Trabajador</th>
                    <th scope="col">Cantidad</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($faltas_final as $falta)
                    <tr>
                      <td>{{$falta['trabajador']}}</td>
                      <td>{{$falta['cant']}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

        </div>


        {{-- Contenido de la pagina --}}
    </div>
</div>


<script type="application/javascript">

window.onload = function() {

  // Accidentes mensual
  let url = 'getAccidentesMesActual';
  axios.get(url)
  .then(response => {
    let datos = [];
    let nombres = [];
    let cantidad = [];
    datos = response.data.datos;

    if (datos.length > 0) {
      datos.map(item =>
        nombres.push(item.nombre)
      )
      datos.map(item =>
        cantidad.push(item.cantidad)
      )
      $('.resumen_graficos_ausentismos_mes').css('display', 'none');
      let ctx = document.getElementById("chart_accidentes");
      let data = {
        labels: nombres,
        datasets: [
          {
            data: cantidad,
            backgroundColor: [
              "#FF6384",
              "#36A2EB",
              "#FFCE56",
              "#327fa8",
              "#474cde",
              "#fa5788",
              "#5ae880",
              "#b7ba65",
              "#61edd6",
              "#c44727",
              "#541d1b",
              "#b59e7f",
              "#5c7e8a",
              "#484f52",
              "#8353c2"
            ],
            hoverBackgroundColor: [
              "#FF4394",
              "#36A2EB",
              "#FFCE56",
              "#27678a",
              "#3d40ad",
              "#bf476b",
              "#44b361",
              "#787a42",
              "#43b09e",
              "#8f3a24",
              "#330f0d",
              "#80705b",
              "#3e545c",
              "#2d3133",
              "#644191"
            ]
          },
        ]
      };

      let options = {
        cutoutPercentage:40,
      };

      let myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
      });

    } else {
      $('.resumen_graficos_ausentismos_mes').css('display', 'block');
    }


});
// Accidentes mensual


// Accidentes anual
let url_anual = 'getAccidentesAnual';
axios.get(url_anual)
.then(response => {
  let datos = [];
  let nombres = [];
  let cantidad = [];
  datos = response.data.datos;

  if (datos.length > 0) {
    datos.map(item =>
      nombres.push(item.nombre)
    )
    datos.map(item =>
      cantidad.push(item.cantidad)
    )
    $('.resumen_graficos_ausentismos_anual').css('display', 'none');
    let chart_accidentes_anual = document.getElementById("chart_accidentes_anual");
    let data_anual = {
      labels: nombres,
      datasets: [
        {
          data: cantidad,
          backgroundColor: [
            "#FF6384",
            "#36A2EB",
            "#FFCE56",
            "#327fa8",
            "#474cde",
            "#fa5788",
            "#5ae880",
            "#b7ba65",
            "#61edd6",
            "#c44727",
            "#541d1b",
            "#b59e7f",
            "#5c7e8a",
            "#484f52",
            "#8353c2"
          ],
          hoverBackgroundColor: [
            "#FF4394",
            "#36A2EB",
            "#FFCE56",
            "#27678a",
            "#3d40ad",
            "#bf476b",
            "#44b361",
            "#787a42",
            "#43b09e",
            "#8f3a24",
            "#330f0d",
            "#80705b",
            "#3e545c",
            "#2d3133",
            "#644191"
          ]
        },
      ]
    };

    let options_anual = {
      cutoutPercentage:40,
    };

    let myDoughnutChart_anual = new Chart(chart_accidentes_anual, {
      type: 'doughnut',
      data: data_anual,
      options: options_anual
    });

  } else {
    $('.resumen_graficos_ausentismos_anual').css('display', 'block');
  }


});
// Accidentes anual


};


</script>

@endsection
