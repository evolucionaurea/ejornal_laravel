$(() => {
    $('.select_2').select2();

    const tipoSelect = document.getElementById('tipo-consulta');
    const camposInicial = document.querySelectorAll('.campos-inicial');
    const camposSeguimiento = document.querySelectorAll('.campos-seguimiento');
    const decimalInputs = document.querySelectorAll('input[type="number"]');

    function toggleCampos() {
        const tipo = tipoSelect.value;
        if (tipo === 'inicial') {
            camposInicial.forEach(campo => campo.style.display = 'block');
            camposSeguimiento.forEach(campo => campo.style.display = 'none');
        } else if (tipo === 'seguimiento') {
            camposInicial.forEach(campo => campo.style.display = 'none');
            camposSeguimiento.forEach(campo => campo.style.display = 'block');
        }
    }

    // Validación para números decimales
    decimalInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            const value = e.target.value;
            const regex = /^\d{0,3}(\.\d{0,2})?$/;

            if (!regex.test(value)) {
                alert('Por favor ingrese un valor válido (máximo 3 dígitos enteros y 2 decimales).');
                e.target.value = ''; // Limpiar el campo si el valor es inválido
            }
        });
    });

    tipoSelect.addEventListener('change', toggleCampos);
    toggleCampos(); // Ejecutar al cargar la página


    $('#prox_cita').datepicker({
        format: 'yyyy-mm-dd',
        language: 'es',  
        autoclose: true,
    });
    


    ////////////////// Caratula ////////////////
	
	function dibujarCaratula(data)
	{
		const caratula = document.getElementById('caratula');
		if (data.estado) {
			
			console.log('dibujar caratula', data.data);
			caratula.innerHTML = `
			<div class="caratula_contenido">
				<h4>Caratula</h4>
				<div class="row">
					<div class="col-md-3">
						<p><strong>Cliente:</strong> ${data.data.cliente.nombre} kg</p>
						<p><strong>Trabajador:</strong> ${data.data.nomina.nombre} cm</p>
						</div>
					<div class="col-md-3">
						<p><strong>Patologia:</strong> ${data.data.patologia.nombre}</p>
						<p><strong>Medicación Habitual:</strong> ${data.data.medicacion_habitual}</p>
					</div>
					<div class="col-md-3">
						<p><strong>Peso:</strong> ${data.data.peso} kg</p>
						<p><strong>Altura:</strong> ${data.data.altura} cm</p>
					</div>
					<div class="col-md-3">
						<p><strong>IMC:</strong> ${data.data.imc}</p>
						<p><strong>Alergias:</strong> ${data.data.alergias}</p>
					</div>
					<div class="col-md-12">
						<button id="editarCaratula" class="btn-ejornal btn-ejornal-gris-claro">Actualizar Caratula</button>
					</div>
				</div>
			</div>
			`;
	
			

			document.getElementById('editarCaratula').addEventListener('click', function () {
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
											<label for="patologia">Patología</label>
											<input type="text" name="patologia_edit_caratula" class="form-control" value="${data.data.patologia.nombre}" disabled>
											<input type="hidden" name="patologia_id_edit_caratula" value="${data.data.patologia.id}">
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
					updatedData['patologia_id_edit_caratula'] = document.querySelector('[name="patologia_id_edit_caratula"]').value;
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
								window.location.reload();
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
			});
			
			
			
			
			

			
		}else{
			caratula.innerHTML = `
			<div class="caratula_contenido">
				<div class="alert alert-info">
					No se ha creado una caratula para este trabajador de la nomina aun.
				</div>
			</div>
			`;
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


});
