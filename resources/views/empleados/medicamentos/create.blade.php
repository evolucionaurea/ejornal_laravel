@extends('partials.layout')

@section('title', 'Empleado')

@section('content')

<div class="d-flex" id="wrapper">
	@include('partials.sidebar_empleados')
	<div id="page-content-wrapper">
		@include('partials.nav_sup')


		{{-- Contenido de la pagina --}}

		<div class="cabecera">
			<h2>Movimiento de medicamentos</h2>
			<p>Aquí puedes generar un movimiento de los medicamentos pertinentes a la empresa en la que estas trabajando</p>
			<div class="cabecera_acciones">
				<a class="btn-ejornal btn-ejornal-gris-claro" href="{{url('empleados/medicamentos')}}"><i class="fas fa-arrow-alt-circle-left"></i>Volver</a>
			</div>
		</div>

		@include('../../mensajes_validacion')
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
			<form action="{{action('EmpleadosStockMedicamentoController@store')}}" accept-charset="UTF-8" method="post">
			@csrf
			<div class="form-row">
				<div class="form-group col-md-4">
					<label>Nombre</label>
					<select name="medicamento" class="form-control form-control-sm select_2" required>
						<option value="">--Seleccionar--</option>
						@foreach ($medicamentos as $medicamento)
						<option value="{{$medicamento->id}}" {{ old('medicamento')==$medicamento->id ? 'selected' : '' }}>{{$medicamento->nombre}}</option>
						@endforeach
				  </select>
				  <div class="small text-muted font-italic" data-content="stock-actual"></div>
				</div>
				<div class="form-group col-md-4">
					<label>
					  Ingreso
					</label>
					<input required name="ingreso" type="number" class="form-control medicamentos_cant_pedida" placeholder="" value="{{ old('ingreso') }}" step="1" min="1">
					<div class="small text-muted font-italic">Es la cantidad de medicamentos que ingresaron</div>
				</div>
				{{-- <div class="form-group col-md-4">
					<label>
					  Stock
					  <i title="Es el stock actual. Comienza siendo la cantidad pedida y luego usted podra ir reduciéndolo a medida que lo consuma" class="fas fa-question-circle"></i>
					</label>
					<input disabled name="stock" type="text" class="form-control form-control-sm medicamentos_stock" placeholder="">
				</div> --}}
				<div class="form-group col-md-4">
					<label>Fecha ingreso</label>
					<input name="fecha_ingreso" type="text" class="form-control" value="{{ old("fecha_ingreso") }}">
				</div>
				<div class="form-group col-md-12">
					<label>Motivo</label>
					<textarea name="motivo" class="form-control" rows="3" required>{{old('motivo')}}</textarea>
				</div>
			</div>
			<button class="btn-ejornal btn-ejornal-base" type="submit" name="button">Cargar medicamento</button>
			</form>
		</div>

		{{-- Contenido de la pagina --}}
	</div>
</div>

<script type="text/javascript">

window.addEventListener("load", function(event) {

	$('.select_2').select2();

});

</script>

@endsection
