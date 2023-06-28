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
                <h4 class="col-md-2 col-lg-5">Fichadas</h4>
                <div class="col-md-10 col-lg-7 d-flex">
                    <input placeholder="Desde" id="reporte_fichadas_desde" name="fecha_inicio" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                    <input placeholder="Hasta" id="reporte_fichadas_hasta" name="fecha_final" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                    <a style="height: 35px; padding-top: 6px;" id="reporte_fichada_filtro" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-search"></i> Buscar</a>
                    <a style="height: 35px; padding-top: 6px;" id="reporte_fichada_todo" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</a>
                </div>
            </div>
            <table class="table table-striped table-hover table-sm tabla_reporte_fichadas">

                <!--Table head-->
                <thead>
                    <tr>
                        <th class="th-lg">
                            <a>
                                Empleado
                            </a>
                        </th>
                        <th class="th-lg">
                            <a>
                                Empresa
                            </a>
                        </th>
                        <th class="th-lg">
                            <a>
                                Ingreso / Egreso
                            </a>
                        </th>
                        <th class="th-lg">
                          <a>
                            Tiempo trabajado
                          </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                IP
                            </a>
                        </th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody class="resultados_reporte_fichadas">
                    {{-- Se llena por JS --}}
                </tbody>
                <!--Table body-->
            </table>
        </div>



        <div class="tarjeta">
          <div class="d-flex justify-content-between row">
              <h4 class="col-md-2 col-lg-5">Ausentismos</h4>
              <div class="col-md-10 col-lg-7 d-flex">
                  <input placeholder="Desde" id="reporte_ausentismos_desde" name="fecha_inicio" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                  <input placeholder="Hasta" id="reporte_ausentismos_hasta" name="fecha_final" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                  <a style="height: 35px; padding-top: 6px;" id="reporte_ausentismo_filtro" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-search"></i> Buscar</a>
                  <a style="height: 35px; padding-top: 6px;" id="reporte_ausentismo_todo" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</a>
              </div>
          </div>
            <table class="table table-striped table-hover table-sm tabla_reporte_ausentismos">

                <!--Table head-->
                <thead>
                    <tr>
                      <th class="th-lg">
                          <a>
                              Cliente
                          </a>
                      </th>
                        <th class="th-lg">
                            <a>
                                Trabajador

                            </a>
                        </th>
                        <th class="th-lg">
                            <a>
                                User que registra

                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Tipo

                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Fecha inicio

                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Fecha final

                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Días ausente

                            </a>
                        </th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody class="resultados_reporte_ausentismos">
                    {{-- Se llena por JS --}}
                </tbody>
                <!--Table body-->
            </table>
        </div>

        <div class="tarjeta">
          <div class="d-flex justify-content-between row">
              <h4 class="col-md-2 col-lg-5">Certificaciones</h4>
              <div class="col-md-10 col-lg-7 d-flex">
                  <input placeholder="Desde" id="reporte_certificaciones_desde" name="fecha_inicio" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                  <input placeholder="Hasta" id="reporte_certificaciones_hasta" name="fecha_final" type="datetime" class="form-control form-control-sm mr-2 w-25 p-3" value="">
                  <a style="height: 35px; padding-top: 6px;" id="reporte_certificacion_filtro" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-search"></i> Buscar</a>
                  <a style="height: 35px; padding-top: 6px;" id="reporte_certificacion_todo" class="btn-ejornal btn-ejornal-gris-claro" href="#!"><i class="fas fa-list"></i> Mostrar todo</a>
              </div>
          </div>
            <table class="table table-striped table-hover table-sm tabla_reporte_certificaciones">

                <!--Table head-->
                <thead>
                    <tr>
                      <th class="th-lg">
                          <a>
                              Cliente

                          </a>
                      </th>
                        <th class="th-lg">
                            <a>
                                Trabajador

                            </a>
                        </th>
                        <th class="th-lg">
                            <a>
                                User que registra

                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Tipo

                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Fecha inicio

                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Fecha final

                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Días ausente

                            </a>
                        </th>
                        <th class="th-lg">
                            <a href="">
                                Documentaciones

                            </a>
                        </th>
                    </tr>
                </thead>
                <!--Table head-->

                <!--Table body-->
                <tbody class="resultados_reporte_certificaciones">
                    {{-- Se llena por JS --}}
                </tbody>
                <!--Table body-->
            </table>
        </div>


        {{-- Contenido de la pagina --}}
    </div>
</div>


@include("../scripts_reportes")
@include("../modal_reportes")

@endsection