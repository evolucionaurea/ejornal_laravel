$(document).ready(() => {

	$("#hamburguesa").click(function(e) {
		e.preventDefault();
		$("#wrapper").toggleClass("toggled");
	});


	// Capturar el cliente seleccionado en la sidebar
	var cliente_select = $('#cliente_seleccionado_sidebar').val();
	var obtenerDatoSessionStore;


	// Para mostrar en fichada
	var trabajando_para = $('#cliente_seleccionado_sidebar option:selected').text();
	$('.trabajando_para').text(trabajando_para);


	// Cuando se cambia el cliente para el que se está trabajando
	$('#cliente_seleccionado_sidebar').on('change', function() {

		cliente_select = this.value;

		// Validar si está trabajando o no empezó
		let trabajando = $('.empleado_trabajando_saber').val();
		console.log(trabajando);

		if (trabajando == 1) {
			let id_cliente_actual = $('.id_cliente_actual').val();
			$('#cliente_seleccionado_sidebar').val(id_cliente_actual);
			$('#modal_alerta_cliente_trabajando').modal('show');
		}else {

			// Session Storage
			sessionStorage.setItem("cliente_seleccionado_storage", cliente_select);
			obtenerDatoSessionStore = sessionStorage.getItem("cliente_seleccionado_storage");

			// Para actualizar en fichada
			trabajando_para = $('#cliente_seleccionado_sidebar option:selected').text();
			$('.trabajando_para').text(trabajando_para);

			let cliente_seleccionado_axios = {
				cliente: parseInt(cliente_select),
			}
			let regex = /(\d+)/g;

			axios.post('actualizar_cliente_actual', cliente_seleccionado_axios)
			.then(response => {
				// const respuesta = response.data.match(regex)
				location.reload();
			});

		}


	});


	if (obtenerDatoSessionStore > 0  && obtenerDatoSessionStore != null && obtenerDatoSessionStore != '') {
		$('#cliente_seleccionado_sidebar').val(obtenerDatoSessionStore);
	}

	$('.dropdownContent').css('display', 'block');

	//Botón de acción del acordeón
	$('.dropdownButton').click(function() {
		//Elimina la clase on de todos los botones
		$('.dropdownButton').removeClass('on');
		//Plegamos todo el contenido que esta abierto
		$('.dropdownContent').slideUp('slow');
		//Si el siguiente slide no esta abierto lo abrimos
		if($(this).next().is(':hidden') == true) {
			//Añade la clase on en el botón
			$(this).addClass('on');
			$(this).next().find('ul .activo_sub a').css('color', '#61d6f2');
			//Abre el slide
			$(this).next().slideDown('slow');
		 }
	 });
	// Cerramos todo el contenido al cargar la página
	$('.dropdownContent').hide();


	$.each($('.sidebar_menu ol[data-route]'),(k,v)=>{
		if($(v).attr('data-route')==route){
			let li = $(v).closest('li')
			li.find('.dropdownButton').trigger('click')
		}
	})

});
