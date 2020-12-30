@extends('partials.layout')

  @section('title', 'Empleado')

@section('content')


<div class="d-flex" id="wrapper">
    @include('partials.sidebar_empleados')
    <div id="page-content-wrapper">
        @include('partials.nav_sup')


        <div class="contenedor_gral">

            <div class="contenedor_resumen py-5">
                <div class="row contenedor_resumen_bloque_principal">
                    <div class="resumen_bloque_principal">
                        <div class="resumen_bloque_principal_titulo">
                            <h6>Nomina</h6>
                        </div>
                        <div class="resumen_bloque_datos">
                            <h2>150</h2>
                            <span>trabajadores</span>
                        </div>
                    </div>
                    <div class="resumen_bloque_principal">
                        <div class="resumen_bloque_principal_titulo">
                            <h6>Medicamentos</h6>
                        </div>
                        <div class="resumen_bloque_datos">
                            <h2>1050</h2>
                            <span>en stock</span>
                        </div>
                    </div>
                    <div class="resumen_bloque_principal">
                        <div class="resumen_bloque_principal_titulo">
                            <h6>Fichadas hoy</h6>
                        </div>
                        <div class="resumen_bloque_datos">
                            <h2>140</h2>
                            <span>Faltan 10</span>
                        </div>
                    </div>
                    <div class="resumen_bloque_principal">
                        <div class="resumen_bloque_principal_titulo">
                            <h6>Otro mas</h6>
                        </div>
                        <div class="resumen_bloque_datos">
                            <h2>8</h2>
                            <span>algun dato</span>
                        </div>
                    </div>
                    <div class="resumen_bloque_principal">
                        <div class="resumen_bloque_principal_titulo">
                            <h6>Otro</h6>
                        </div>
                        <div class="resumen_bloque_datos">
                            <h2>230</h2>
                            <span>Mas info</span>
                        </div>
                    </div>
                </div>
            </div>



            <div class="contenedor_resumen resumen_tablas">
                <div class="row">
                    <div class="tarjeta col-md-6">
                        <div class="table-responsive text-nowrap tabla">
                            <div class="tabla_titulo">
                                <h4>Algun dato</h4>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nro</th>
                                        <th>Dato 1</th>
                                        <th>Dato 2</th>
                                        <th>Dato 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Info 1</td>
                                        <td>Info 2</td>
                                        <td>Info 3</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Info 1</td>
                                        <td>Info 2</td>
                                        <td>Info 3</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tarjeta col-md-6">
                        <div class="table-responsive text-nowrap tabla">
                            <div class="tabla_titulo">
                                <h4>Top Otro info</h4>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nro</th>
                                        <th>Dato 1</th>
                                        <th>Dato 2</th>
                                        <th>Dato 3</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Info 1</td>
                                        <td>Info 2</td>
                                        <td>Info 3</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">1</th>
                                        <td>Info 1</td>
                                        <td>Info 2</td>
                                        <td>Info 3</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        {{-- Contenido de la pagina --}}
    </div>
</div>






@endsection
