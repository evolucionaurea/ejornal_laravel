@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">

  @include('partials.sidebar_empleados')

  <div id="page-content-wrapper">
    @include('partials.nav_sup')


    {{-- Contenido de la pagina --}}

    <div class="cabecera">
      <h2>Edición de carátula</h2>
      <p>Editando carátula de <b>{{ $caratula->nomina->nombre }}</b></p>
      <div class="cabecera_acciones">
        <a class="btn-ejornal btn-ejornal-gris-claro" href="{{ url('empleados/nominas/'.$caratula->nomina->id) }}">
          <i class="fas fa-arrow-circle-left fa-fw"></i>
          Volver
        </a>
      </div>
    </div>


    @include('../../../mensajes_validacion')

    @if ($errors->any())
    @foreach ($errors->all() as $error)
    <div class="alert alert-danger alert-dismissible fade show mr-4 ml-4" role="alert">
      {{$error}}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endforeach
    @endif

    <div class="tarjeta">
      <form action="{{url('empleados/caratulas/update', $caratula->id)}}" accept-charset="UTF-8" method="post" enctype="multipart/form-data">
				@csrf
				@method('PUT')
				<input type="hidden" name="id_nomina" value="{{ $caratula->nomina->id }}">
				<div class="form-row">
					<div class="form-group col-md-3">
						<label>
							Patología

              @if (auth()->user()->permiso_desplegables == 1)
							<a data-toggle="modal" data-target="#patologiaVerModal" href="#">
								<i class="fas fa-eye"></i>
							</a>
							<a data-toggle="modal" data-target="#patologiaCrearModal" href="#">
								<i class="fas fa-plus-circle"></i>
							</a>
              @endif 
              
						</label>
						<select multiple required name="id_patologia[]" class="form-control form-control-sm select_2">
							@foreach ($patologias as $patologia)
							<option value="{{ $patologia->id }}" {{ $caratula->patologias->contains('id', $patologia->id) ? 'selected' : '' }}>{{ $patologia->nombre }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group col-md-3">
						<label>Peso</label>
						<div class="input-group">
							<input name="peso" type="number" class="form-control" value="{{ old("peso") ?? $caratula->peso }}">
							<div class="input-group-append">
								<span class="input-group-text">kg.</span>
							</div>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>Altura</label>
						<div class="input-group">
							<input name="altura" type="number" class="form-control" value="{{ old("altura") ?? $caratula->altura }}">
							<div class="input-group-append">
								<span class="input-group-text">cm.</span>
							</div>
						</div>
					</div>
					<div class="form-group col-md-3">
						<label>IMC</label>
						<input disabled name="imc_disabled" type="text" class="form-control" value="{{ old("imc") ?? $caratula->imc }}">
						<input name="imc" type="hidden" class="form-control" value="{{ old("imc") ?? $caratula->imc }}">
					</div>
					
          <div class="form-group col-md-4">
						<label>Medicación habitual</label>
						<textarea class="form-control form-control-sm" name="medicacion_habitual" rows="8" >{{ old("medicacion_habitual") ?? $caratula->medicacion_habitual }}</textarea>
					</div>
					<div class="form-group col-md-4">
						<label>Antecedentes</label>
						<textarea class="form-control form-control-sm" name="antecedentes" rows="8" >{{ old("antecedentes") ?? $caratula->antecedentes }}</textarea>
					</div>
					<div class="form-group col-md-4">
						<label>Alergias</label>
						<textarea class="form-control form-control-sm" name="alergias" rows="8" >{{ old("alergias") ?? $caratula->alergias }}</textarea>
					</div>
				</div>
				<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Guardar Carátula</button>
      </form>
    </div>

      {{-- Contenido de la pagina --}}
  </div>
</div>


<!-- Modal Ver Patologias -->
<div class="modal fade" id="patologiaVerModal" tabindex="-1" aria-labelledby="patologiaVerModalLabel"
    aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="patologiaVerModalLabel">Ver patologías</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          @if ($patologias)
          @foreach ($patologias as $patologia)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <span>{{ $patologia->nombre }}</span>
            <div class="d-flex gap-2">
              <!-- Botón Editar -->
              <button type="button" class="p-1 mr-1 text-info btn-editar-patologia" data-toggle="modal" data-target="#editarPatologiaModal" data-id="{{ $patologia->id }}" data-nombre="{{ $patologia->nombre }}" title="Editar">
                <i class="fas fa-edit"></i>
              </button>


              <!-- Botón Eliminar -->
              <form action="{{ route('patologias.destroy', $patologia->id) }}" method="post">
                @csrf
                @method('DELETE')
                <button class="p-1 text-danger" type="submit" title="Eliminar">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
          </li>

          @endforeach
          @else
          <p class="alert alert-warning">
            No hay patologías creadas aún.
          </p>
          @endif
        </ul>
      </div>
    </div>
  </div>
</div>


<!-- Modal Crear Patologias -->
<div class="modal fade" id="patologiaCrearModal" tabindex="-1" aria-labelledby="patologiaCrearModalLabel" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="patologiaCrearModalLabel">Nueva patología</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="{{action('EmpleadosPatologiasController@store')}}" accept-charset="UTF-8" method="post">
          @csrf
          <div class="form-group">
            <label>Nombre</label>
            <input required type="text" class="form-control" placeholder="" name="nombre">
          </div>
          <button type="submit" class="btn-ejornal btn-ejornal-base">Guardar</button>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal Editar Patología Reutilizable -->
<div class="modal fade" id="editarPatologiaModal" tabindex="-1" aria-labelledby="editarPatologiaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editarPatologiaForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editarPatologiaModalLabel">Editar Patología</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Nombre</label>
            <input required type="text" name="nombre" class="form-control" id="editarPatologiaNombre">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn-ejornal btn-ejornal-base"> Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection