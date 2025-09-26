@extends('partials.layout')

@section('title', 'Estudio Médico Complementario')

@section('content')

<div class="d-flex" id="wrapper">

    @include('partials.sidebar_clientes')

    <div id="page-content-wrapper">
        @include('partials.nav_sup')

        <div class="cabecera">
            <h2>Estudio Médico Complementario</h2>
            <p>Visualización en detalle de un estudio médico complementario.</p>

            <div class="cabecera_acciones">
                <a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('clientes/preocupacionales')}}">
                    <i class="fas fa-arrow-alt-circle-left"></i> <span>Volver</span>
                </a>
            </div>
        </div>


        <div class="tarjeta">

            <div class="row">
                <div class="col-lg-3 text-center">

                    @if ($preocupacional->trabajador->foto)
                    <div class="foto-perfil"
                        style="background-image: url({{ $preocupacional->trabajador->photo_url }})">
                    </div>
                    @else
                    <i class="fas fa-user fa-10x"></i>
                    @endif

                </div>
                <div class="col-lg-4">
                    <div class="font-weight-bold">{{ $preocupacional->trabajador->nombre }}</div>
                    <ul class="list-group">
                        <li class="list-group-item p-2">DNI: {!! $preocupacional->trabajador->dni ?? '<i
                                class="text-muted">[no cargado]</i>' !!}</li>
                        <li class="list-group-item p-2">CUIL: {!! $preocupacional->trabajador->email ?? '<i
                                class="text-muted">[no cargado]</i>' !!}</li>
                        <li class="list-group-item p-2">Teléfono: {!! $preocupacional->trabajador->telefono ?? '<i
                                class="text-muted">[no cargado]</i>' !!}</li>
                        <li class="list-group-item p-2">
                            Estado:
                            @if( $preocupacional->id_cliente != $preocupacional->trabajador->id_cliente)
                            <span class="badge badge-dark">transferido</span>
                            @else
                            <span
                                class="badge badge-{{ $preocupacional->trabajador->estado ? 'success' : 'danger' }}">{{
                                $preocupacional->trabajador->estado ? 'activo' : 'inactivo' }}</span>
                            @endif
                        </li>
                        <li class="list-group-item p-2">Sector: {{ $preocupacional->trabajador->sector }}</li>
                        <li class="list-group-item p-2">Fecha Alta: {{
                            $preocupacional->trabajador->created_at->format('d/m/Y') }}</li>
                        <li class="list-group-item p-2">Legajo: {{ $preocupacional->trabajador->legajo ?: 'No fue
                            cargado' }}</li>
                    </ul>
                </div>

                <div class="col-lg-4 border-left">
                    <div class="font-weight-bold">Datos del Estudio</div>
                    <ul class="list-group">
                        <li class="list-group-item p-2">Tipo: {{ $preocupacional->tipo->name }}</li>
                        <li class="list-group-item p-2">Resultado: {{ $preocupacional->resultado }}</li>
                        <li class="list-group-item p-2">Fecha Estudio: {{ $preocupacional->fecha->format('d/m/Y') }}
                        </li>
                        <li class="list-group-item p-2">Fecha de Carga: {{ $preocupacional->created_at_formatted }}</li>
                        <li class="list-group-item p-2">Última Modificación: {{ $preocupacional->updated_at_formatted }}
                        </li>
                        <li class="list-group-item p-2">Usuario que registró: {{ $preocupacional->user }}</li>
                        <li class="list-group-item p-2">Vencimiento: {!! is_null($preocupacional->fecha_vencimiento) ?
                            '<span class="font-italic text-muted">[no tiene]</span>' :
                            $preocupacional->fecha_vencimiento->format('d/m/Y').' '.$preocupacional->vencimiento_label
                            !!}</li>

                        @if(!is_null($preocupacional->fecha_vencimiento))
                        <li class="list-group-item p-2">Completado: {{ $preocupacional->completado ? 'SI' : 'NO' }}</li>
                        <li class="list-group-item p-2">Comentarios: {!! $preocupacional->completado_comentarios ??
                            '<span class="font-italic text-muted">[sin comentarios]</span>' !!}</li>
                        @endif

                    </ul>

                </div>

            </div>

        </div>

        <div class="tarjeta">

            <div class="row">
                <div class="col-lg-6">
                    <h6>Archivos</h6>
                    <table class="table table-sm small table-bordered">
                        <tbody>
                            @if($preocupacional->archivos->count()) @foreach($preocupacional->archivos as $archivo)
                            <tr>
                                <td>
                                    <a href="{{ $archivo->file_path_cliente }}" target="_blank"
                                        class="btn btn-sm btn-info">
                                        <i class="fa fa-download fa-fw"></i>
                                        <span>{{ $archivo->archivo }}</span>
                                    </a>
                                </td>
                            </tr>
                            @endforeach @else
                            <tr>
                                <td class="font-italic text-muted">[sin archivos adjuntos]</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                </div>
                <div class="col-lg-6 border-left">
                    <h6>Observaciones</h6>
                    <div class="font-italic">{{ $preocupacional->observaciones }}</div>
                </div>
            </div>
        </div>

    </div>

</div>



@endsection