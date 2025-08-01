<div class="caratula_contenido">
	<h4 class="mb-0">Carátula</h4>
	<div class="small text-muted font-italic">Última actualización: {{ $caratula->created_at_formatted}} hs.</div>
	<hr>
	<div class="row">
		<div class="col-md-3">
			<p><strong>Trabajador:</strong> {{$caratula->nomina->nombre}} </p>
			<p><strong>Patologías:</strong> {{$caratula->patologias->pluck('nombre')->implode(', ') }}</p>
		</div>
		<div class="col-md-3">
			<p><strong>Medicación Habitual:</strong> {{$caratula->medicacion_habitual}}</p>
			<p><strong>Peso:</strong> <span data-content="peso">{{$caratula->peso}}</span> kg</p>
		</div>
		<div class="col-md-3">
			<p><strong>Altura:</strong> <span data-content="altura">{{$caratula->altura}}</span> cm</p>
			<p><strong>IMC:</strong> {{$caratula->imc}}</p>
		</div>
		<div class="col-md-3">
			<p><strong>Alergias:</strong> {{$caratula->alergias}}</p>
			<p><strong>Antecedentes:</strong> {{$caratula->antecedentes}}</p>
		</div>
		<div class="col-md-12 border-top pt-3">
			<button data-toggle="usar-datos-caratula" class="btn-ejornal btn-ejornal-base">Usar estos datos para IMC</button>
			<button data-toggle="editar-caratula" class="btn-ejornal btn-ejornal-gris-claro">Actualizar Caratula</button>
		</div>
	</div>
</div>