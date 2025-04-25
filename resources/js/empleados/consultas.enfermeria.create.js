const { isNumber } = require("lodash");

$(()=>{

	let imc;
	let medicamentos = '';
	let medicamentos_suministrados = [];

	$(".form-row .form-group input[name='peso']").keyup(function() {
		let peso = $(this).val();
		let altura = $(".form-row .form-group input[name='altura']").val();
		if (peso != '' && peso != null && peso != undefined && altura != '' && altura != null && altura != undefined && altura !== 0 || peso !== 0) {
		  imc = parseFloat(peso / (Math.pow((altura/100), 2))).toFixed(2);
			$(".form-row .form-group input[name='imc']").val(imc);
			$(".form-row .form-group input[name='imc_disabled']").val(imc);
		} else {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == NaN) {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == Infinity) {
			$(".form-row .form-group input[name='imc']").val("");
		}
	});


	$(".form-row .form-group input[name='altura']").keyup(function() {
		let altura = $(this).val();
		let peso = $(".form-row .form-group input[name='peso']").val();
		if (altura != '' && altura != null && altura != undefined && peso != '' && peso != null && peso != undefined && altura !== 0 || peso !== 0) {
		  imc = parseFloat(peso / (Math.pow((altura/100), 2))).toFixed(2);
			$(".form-row .form-group input[name='imc']").val(imc);
			$(".form-row .form-group input[name='imc_disabled']").val(imc);
		} else {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == NaN) {
			$(".form-row .form-group input[name='imc']").val("");
		}
		if ($(".form-row .form-group input[name='imc']").val() == Infinity) {
			$(".form-row .form-group input[name='imc']").val("");
		}
	});


	$('#cargar_medicacion').click(function() {
		$('#cargar_medicacion_abrir').modal('show');
		medicamentos_suministrados = [];
	});



	$("#aceptar_suministrar_medicamentos").click(function() {

		medicamentos_suministrados = [];
		let sin_stock = []

		$.each($(".modal_medicacion_a_suministrar .btn-toolbar"), (k,v)=>{

			if($(v).find('input').val() == '' || $(v).find('input').val() == '0') return true

			const stock = parseInt($(v).find('[data-content="stock"]').text())
			const suministrados = parseInt($(v).find('input').val())
			const medicamento = $(v).find('[data-content="medicamento"]').text()

			if(suministrados>stock) sin_stock.push(medicamento)

			medicamentos_suministrados.push({
				nombre: medicamento,
				id_medicamento: $(v).find('input').attr('data-medicamentoid'),
				suministrados: suministrados
			})
		})

		if(sin_stock.length>0){
			Swal.fire({
				icon:'error',
				title:`${sin_stock.join(', ')}`,
				html:`no dispone${sin_stock.length>1?'n':''} de suficiente stock para la cantidad a suministrar.`
			})
			return false
		}

		$('#cargar_medicacion_abrir').modal('hide');

		// Remover si el elemento existe
		if ($(".ul_lista_medicamentos").length > 0) {
			$('.ul_lista_medicamentos').remove();
		}
		if ($(".listado_medicaciones_inputs_ocultos input").length > 0) {
			$('.listado_medicaciones_inputs_ocultos input').remove();
		}

		for (i = 0; i < medicamentos_suministrados.length; i++) {
		  if (medicamentos_suministrados[i].suministrados.length !== 0 || medicamentos_suministrados[i].suministrados !== '0') {

				$(".listado_medicaciones").append(
					$('<ul>', {
						'class': 'list-group ul_lista_medicamentos small'
					}).append(
						$('<li>', {
							'class': 'list-group-item p-1'
						}).append(
							$('<div>', {
								'text': `${medicamentos_suministrados[i].nombre}:  ${medicamentos_suministrados[i].suministrados}`
							})
						)
					)
				);

				$(".listado_medicaciones_inputs_ocultos").append(
					$('<input>', {
						'type': 'hidden',
						'name': 'medicaciones[]',
						'value': `${medicamentos_suministrados[i].id_medicamento},${medicamentos_suministrados[i].suministrados}`
					})
				);
		  }
		}

		$('.listado_medicaciones ul li').css('color', 'grey');

	})


	// Evento de búsqueda en tiempo real
	$('#medicamentoSearch').on('input', function () {
		var searchText = $(this).val().toLowerCase(); // Texto de búsqueda en minúsculas

		// Filtra los medicamentos que coinciden con el texto de búsqueda
		$('.btn-toolbar').each(function () {
			var medicamentoNombre = $(this).find('[data-content="medicamento"]').text().toLowerCase(); // Nombre del medicamento

			if (medicamentoNombre.includes(searchText)) {
				$(this).show(); // Muestra el medicamento si coincide
			} else {
				$(this).hide(); // Oculta el medicamento si no coincide
			}
		});
	});

	$("#guarda_consulta").click(function(e) {
		e.preventDefault();
		$('#consulta_confirmacion_final').modal('show');

		$('#consulta_crear_ok').click(function(e) {
			$('#form_guardar_consulta_enfermeria').submit();
			$('#consulta_confirmacion_final').modal('hide');
		});

	});


	$('.select_2').select2();
	$('.select_2').trigger('change');



	////////////////// Caratula ////////////////
	
	function dibujarCaratula(data)
	{
		const caratula = document.getElementById('caratula');
		const formulario = document.getElementById('form_guardar_consulta_enfermeria');
		if (data.estado) {
			
			console.log('dibujar caratula', data.data);
			caratula.innerHTML = `
			<div class="caratula_contenido">
				<h4>Caratula</h4>
				<div class="row">
					<div class="col-md-3">
						<p><strong>Trabajador:</strong> ${data.data.nomina.nombre} </p>
						<p><strong>Patologías:</strong> ${data.data.patologias.map(p => p.nombre).join(', ')}</p>
					</div>
				<div class="col-md-3">
                    <p><strong>Medicación Habitual:</strong> ${data.data.medicacion_habitual}</p>
					<p><strong>Peso:</strong> ${data.data.peso} kg</p>
                </div>
					<div class="col-md-3">
						<p><strong>Altura:</strong> ${data.data.altura} cm</p>
						<p><strong>IMC:</strong> ${data.data.imc}</p>
					</div>
					<div class="col-md-3">
						<p><strong>Alergias:</strong> ${data.data.alergias}</p>
						<p><strong>Antecedentes:</strong> ${data.data.antecedentes}</p>
					</div>
					<div class="col-md-12">
						<button id="usarDatos" class="btn-ejornal btn-ejornal-base">Usar estos datos para IMC</button>
						<button id="editarCaratula" class="btn-ejornal btn-ejornal-gris-claro">Actualizar Caratula</button>
					</div>
				</div>
			</div>
			`;
	
			document.getElementById('usarDatos').addEventListener('click', function () {
				
				// Asignar peso y altura
				formulario.querySelector('[name="peso"]').value = data.data.peso;
				formulario.querySelector('[name="altura"]').value = data.data.altura;
			
				// Verificar si imc tiene un valor válido
				let imc = data.data.imc;
				if (imc !== undefined && imc !== null && !isNaN(imc) && isFinite(imc)) {
					// Asignar el valor al campo oculto (name="imc")
					formulario.querySelector('[name="imc"]').value = imc;
					
					// Asignar el valor al campo deshabilitado (name="imc_disabled")
					formulario.querySelector('[name="imc_disabled"]').value = imc;
				} else {
					// Si el valor de IMC no es válido, limpiar ambos campos
					formulario.querySelector('[name="imc"]').value = "";
					formulario.querySelector('[name="imc_disabled"]').value = "";
				}
			});

			

			document.getElementById('editarCaratula').addEventListener('click', function () {

				// Traer patologias
				fetch('/api/patologias')
					.then(response => response.json())
					.then(json => {
						console.log(json);
						let patologias = json.data; // Ahora la variable patologias se define aquí
			
						// Crear modal dinámicamente
						let modalHtml = `
							<div class="modal fade" id="editarCaratulaModal" tabindex="-1" role="dialog" aria-labelledby="editarCaratulaLabel" aria-hidden="true">
								<div class="modal-dialog modal-lg" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="editarCaratulaLabel">Actualizar Carátula</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<form id="formEdicionCaratula">
												<div class="row">
													<div class="col-md-6">
														<label for="cliente">Cliente</label>
														<input type="text" name="cliente_edit_caratula" class="form-control" value="${data.data.cliente.nombre}" disabled>
														<input type="hidden" name="cliente_id_edit_caratula" value="${data.data.cliente.id}">
													</div>
													<div class="col-md-6">
														<label for="trabajador">Trabajador</label>
														<input type="text" name="trabajador_edit_caratula" class="form-control" value="${data.data.nomina.nombre}" disabled>
														<input type="hidden" name="trabajador_id_edit_caratula" value="${data.data.nomina.id}">
													</div>
												</div>
												
												<div class="row">
													<div class="col-md-6">
														<label for="patologia">
														Patología
														</label>
														<select name="patologia_edit_caratula[]" class="form-control select_2" multiple>
															<!-- Itera sobre las patologías y crea las opciones -->
															${patologias.map(patologia => {
																// Verificar si la patología está seleccionada comparando los ids
																let selected = data.data.patologias.some(p => p.id === patologia.id) ? 'selected' : '';
																return `
																	<option value="${patologia.id}" ${selected}>${patologia.nombre}</option>
																`;
															}).join('')}
														</select>
														<input type="hidden" name="patologia_id_edit_caratula" value="${data.data.patologias.map(patologia => patologia.id).join(',')}">
													</div>
			
													<div class="col-md-6">
														<label for="peso">Peso (kg)</label>
														<input type="number" name="peso_edit_caratula" class="form-control" value="${data.data.peso}">
													</div>
												</div>
												
												<div class="row">
													<div class="col-md-6">
														<label for="altura">Altura (cm)</label>
														<input type="number" name="altura_edit_caratula" class="form-control" value="${data.data.altura}">
													</div>
													<div class="col-md-6">
														<label for="imc">IMC</label>
														<input type="text" name="imc_edit_caratula" class="form-control" value="${data.data.imc}">
														<input type="hidden" name="imc_disabled_edit_caratula" class="form-control" value="${data.data.imc}">
													</div>
												</div>
												
												<div class="row">
													<div class="col-md-4">
														<label for="antecedentes">Antecedentes</label>
														<textarea name="antecedentes_edit_caratula" class="form-control">${data.data.antecedentes}</textarea>
													</div>
													<div class="col-md-4">
														<label for="alergias">Alergias</label>
														<textarea name="alergias_edit_caratula" class="form-control">${data.data.alergias}</textarea>
													</div>
													<div class="col-md-4">
														<label for="medicacion_habitual">Medicación Habitual</label>
														<textarea name="medicacion_habitual_edit_caratula" class="form-control">${data.data.medicacion_habitual}</textarea>
													</div>
												</div>
											</form>
										</div>
										<div class="row">
											<div class="col-12">
												<button type="button" class="btn-ejornal btn-ejornal-gris-claro ml-4" data-dismiss="modal">Cancelar</button>
												<button id="guardarCaratulaConsulta" type="button" class="btn-ejornal btn-ejornal-base">Guardar cambios</button>
											</div>
										</div>
									</div>
								</div>
							</div>`;
						
						// Insertar el modal en el body
						document.body.insertAdjacentHTML('beforeend', modalHtml);
						$('#editarCaratulaModal').modal('show');
					
						// Asegurarse de que el evento se registre después de que el modal se haya mostrado
						$('#editarCaratulaModal').on('shown.bs.modal', function () {
							$('.select_2').select2();
			
							// Lógica para calcular el IMC cuando se cambian los campos de peso o altura dentro del formulario
							$("input[name='peso_edit_caratula'], input[name='altura_edit_caratula']").on('keyup', function() {
								let peso = $("input[name='peso_edit_caratula']").val();
								let altura = $("input[name='altura_edit_caratula']").val();
			
								// Verificar que los valores no estén vacíos y sean válidos
								if (peso && altura && peso > 0 && altura > 0) {
									// Calcular el IMC
									let imc = (parseFloat(peso) / Math.pow((parseFloat(altura) / 100), 2)).toFixed(2);
									// Establecer el IMC calculado en los campos
									$("input[name='imc_edit_caratula']").val(imc);
									$("input[name='imc_disabled_edit_caratula']").val(imc);
								} else {
									// Limpiar el IMC si falta alguno de los campos
									$("input[name='imc_edit_caratula']").val("");
									$("input[name='imc_disabled_edit_caratula']").val("");
								}
							});
						});
					
						// Manejar el botón de guardar cambios
						document.getElementById('guardarCaratulaConsulta').addEventListener('click', function () {                    
							// Crear un objeto vacío para almacenar los datos
							let updatedData = {};
			
							// Obtener los valores de los campos manualmente con querySelector
							updatedData['cliente_id_edit_caratula'] = document.querySelector('[name="cliente_id_edit_caratula"]').value;
							updatedData['trabajador_id_edit_caratula'] = document.querySelector('[name="trabajador_id_edit_caratula"]').value;
							updatedData['patologia_id_edit_caratula'] = Array.from(document.querySelector('[name="patologia_edit_caratula[]"]').selectedOptions).map(option => option.value);
							updatedData['peso_edit_caratula'] = document.querySelector('[name="peso_edit_caratula"]').value;
							updatedData['altura_edit_caratula'] = document.querySelector('[name="altura_edit_caratula"]').value;
							updatedData['imc_edit_caratula'] = document.querySelector('[name="imc_edit_caratula"]').value;
							updatedData['antecedentes_edit_caratula'] = document.querySelector('[name="antecedentes_edit_caratula"]').value;
							updatedData['alergias_edit_caratula'] = document.querySelector('[name="alergias_edit_caratula"]').value;
							updatedData['medicacion_habitual_edit_caratula'] = document.querySelector('[name="medicacion_habitual_edit_caratula"]').value;
							
							// Enviar los datos al servidor
							fetch(`/api/actualizar_caratula`, {
								method: 'POST',
								headers: {
									'Content-Type': 'application/json',
								},
								body: JSON.stringify(updatedData),
							})
								.then(response => response.json())
								.then(data => {
									if (data.estado) {
										alert(data.data);
										// window.location.reload();
										let idNomina = updatedData['trabajador_id_edit_caratula']; 
										let idClienteActual = $('#id_cliente_actual').val();
										let dominio = window.location.origin; 
									
										// Validar que no este seleccionada la opcion "Seleccionar"
										if (idNomina != '') {
											let url = `${dominio}/api/get_caratula_nomina/${idNomina}/${idClienteActual}`; 
											dibujarLoader();
										
											// Realizar la consulta al EndpointsController
											fetch(url, {
												method: 'GET', 
												headers: {	
													'Content-Type': 'application/json', 
												},
											})
											.then(response => {
												if (!response.ok) {
													throw new Error('Error en la respuesta del servidor'); 
												}
												return response.json(); 
											})
											.then(data => {
												$('#loader').remove();
												if ($('#caratula_contenido').length) {
													$('#caratula_contenido').remove();
												}
												dibujarCaratula(data); 
											})
											.catch(error => {
												console.error('Error:', error); 
											});
										}	


									} else {
										alert('Error al actualizar la carátula: ' + (data.data || 'Hubo un problema.'));
									}
								})
								.catch(error => {
									alert('Error en la solicitud. No se pudo conectar con el servidor.');
								})
								.finally(() => {
									// Cerrar el modal y eliminarlo
									$('#editarCaratulaModal').modal('hide');
									$('#editarCaratulaModal').on('hidden.bs.modal', function () {
										$(this).remove();
									});
								});
						});
					
						// Eliminar el modal al cerrarlo
						$('#editarCaratulaModal').on('hidden.bs.modal', function () {
							$(this).remove();
						});
			
					})
					.catch(error => {
						console.error('Error al cargar las patologías:', error);
						alert('Hubo un error al cargar las patologías.');
					});
			});
			
			
			
			
			
			

			
		}else{
			caratula.innerHTML = `
			<div class="caratula_contenido">
				<div class="alert alert-info">
					No se ha creado una caratula para este trabajador de la nomina aun.
				</div>
			</div>
			`;
			formulario.querySelector('[name="peso"]').value = '';
			formulario.querySelector('[name="altura"]').value = '';
			formulario.querySelector('[name="imc"]').value = '';
			formulario.querySelector('[name="imc_disabled"]').value = '';

		}
	}

	function dibujarLoader() {
		const caratula = document.getElementById('caratula');
		caratula.innerHTML = `
		<div id="loader" class="spinner-border text-primary" role="status">
				<span class="sr-only">Loading...</span>
			</div>
		`;
	}


	$('#id_nomina').change(function() {
		let idNomina = $(this).val(); 
		let idClienteActual = $('#id_cliente_actual').val();
		let dominio = window.location.origin; 
	
		// Validar que no este seleccionada la opcion "Seleccionar"
		if (idNomina != '') {
			let url = `${dominio}/api/get_caratula_nomina/${idNomina}/${idClienteActual}`; 
			dibujarLoader();
		
			// Realizar la consulta al EndpointsController
			fetch(url, {
				method: 'GET', 
				headers: {
					'Content-Type': 'application/json', 
				},
			})
			.then(response => {
				if (!response.ok) {
					throw new Error('Error en la respuesta del servidor'); 
				}
				return response.json(); 
			})
			.then(data => {
				$('#loader').remove();
				if ($('#caratula_contenido').length) {
					$('#caratula_contenido').remove();
				  }
				dibujarCaratula(data); 
			})
			.catch(error => {
				console.error('Error:', error); 
			});
		}
	});

	

////////////////// Caratula ////////////////
	

})