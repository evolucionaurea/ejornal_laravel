@extends('partials.layout')

@section('title', 'Admin')

@section('content')


<div class="d-flex" id="wrapper">
    @include('partials.sidebar_admin')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        {{-- Contenido de la pagina --}}

        <div class="cabecera">
            <h2>Reportes</h2>
            <p>Aquí puede ver los reportes del sistema</p>
        </div>

        @include('../mensajes_validacion')


        <div class="tarjeta">
          <div class="d-flex justify-content-between row">
              <h4 class="col-md-2 col-lg-5">Consultas Medicas</h4>
              <div class="col-md-10 col-lg-7 d-flex">
                  <input placeholder="Desde" id="reporte_consultas_medicas_desde" name="fecha_inicio" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                  <input placeholder="Hasta" id="reporte_consultas_medicas_hasta" name="fecha_final" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                  <a style="height: 35px; padding-top: 6px;" id="reporte_consultas_medicas_filtro" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-search"></i> Buscar</a>
                  <a style="height: 35px; padding-top: 6px;" id="reporte_consultas_medicas_todo" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</a>
              </div>
          </div>
            <table class="table table-striped table-hover table-sm tabla_reporte_consultas_medicas">

                <!--Table head-->
                <thead>
                    <tr>
                      <th class="th-lg">
                          <a>
                              Nomina
                          </a>
                      </th>
                      <th class="th-lg">
                          <a>
                              Cliente
                          </a>
                      </th>
                        <th class="th-lg">
                            <a>
                                Peso
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Altura
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Derivación
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Diagnostico
                            </a>
                        </th>
                        <th class="th-lg">
                          <a>
                            Fecha
                          </a>
                        </th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody class="resultados_reporte_consultas_medicas">
                    {{-- Se llena por JS --}}
                </tbody>
                <!--Table body-->
            </table>
        </div>




        <div class="tarjeta">
          <div class="d-flex justify-content-between row">
              <h4 class="col-md-2 col-lg-5">Consultas Enfermería</h4>
              <div class="col-md-10 col-lg-7 d-flex">
                  <input placeholder="Desde" id="reporte_consultas_enfermerias_desde" name="fecha_inicio" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                  <input placeholder="Hasta" id="reporte_consultas_enfermerias_hasta" name="fecha_final" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                  <a style="height: 35px; padding-top: 6px;" id="reporte_consultas_enfermerias_filtro" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-search"></i> Buscar</a>
                  <a style="height: 35px; padding-top: 6px;" id="reporte_consultas_enfermerias_todo" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</a>
              </div>
          </div>
            <table class="table table-striped table-hover table-sm tabla_reporte_consultas_enfermerias">

                <!--Table head-->
                <thead>
                    <tr>
                      <th class="th-lg">
                          <a>
                              Nomina
                          </a>
                      </th>
                      <th class="th-lg">
                          <a>
                              Cliente
                          </a>
                      </th>
                        <th class="th-lg">
                            <a>
                                Peso
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Altura
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Derivación
                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Diagnostico
                            </a>
                        </th>
                        <th class="th-lg">
                          <a>
                            Fecha
                          </a>
                        </th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody class="resultados_reporte_consultas_enfermerias">
                    {{-- Se llena por JS --}}
                </tbody>
                <!--Table body-->
            </table>
        </div>


        {{-- Contenido de la pagina --}}
    </div>
</div>

@endsection

@include("../scripts_reportes_consultas")
@include("../modal_reportes")
