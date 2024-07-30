<!-- Modal Cargar tipo de medicación -->
<div class="modal fade" id="cargar_medicacion_abrir" tabindex="-1" aria-labelledby="cargar_medicacion_titulo"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="cargar_medicacion_titulo">Suministrar medicación</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" >
				<div class="form-group">
					<input type="text" class="form-control" id="medicamentoSearch" placeholder="Busca tu medicamento...">
				</div>
				<div class="row">
					<div class="col-md-12">
						<form class="modal_medicacion_a_suministrar" action="" accept-charset="UTF-8" method="">

							<div style="max-height:480px;overflow-y:auto;padding:1rem;background-color:rgb(248,248,248)" class="border" >
								@foreach ($stock_medicamentos as $medicamento)
								<div class="btn-toolbar mb-3 pt-2" role="toolbar" aria-label="Toolbar with button groups">
									<h6 class="m-0 font-weight-bold" data-content="medicamento">{{$medicamento->nombre}}</h6>
									<div style="width: 100%;" class="input-group input-group-sm">
										<div class="input-group-prepend">
											<div class="input-group-text">En stock: <span data-content="stock">{{$medicamento->stock}}</span></div>
										</div>
										<input data-medicamentoid="{{$medicamento->id}}" type="number" min="1" step="1" class="form-control" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
									</div>
								</div>
								@endforeach
							</div>

							<div class="mt-2">
								<button id="aceptar_suministrar_medicamentos" type="button" class="btn-ejornal btn-ejornal-success">Aceptar</button>
								<button type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</button>
							</div>


						</form>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>




<!-- Modal confirmación Final -->
<div class="modal fade" id="consulta_confirmacion_final" tabindex="-1"
	aria-labelledby="consulta_confirmacion_final_Titulo" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="consulta_confirmacion_final_Titulo">Advertencia</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-md-12">
						<div class="alert alert-light" role="alert">
							<p>Una vez creada la consulta no podrá editarse. Presione Aceptar para continuar o cancelar
								para revisar la consulta.</p>
							<hr>
							<a id="consulta_crear_ok" type="button" class="btn-ejornal btn-ejornal-success">Aceptar</a>
							<a type="button" class="btn-ejornal btn-ejornal-gris-claro" data-dismiss="modal">Cerrar</a>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>