$(()=>{

    let url = '/admin/get_medicamentos'
    fetch(url)
    .then(response => response.json())
    .then(json => {
      showMedicamentosDisponibles(json);
    });

    function showMedicamentosDisponibles(datos){
      for (let i = 0; i < datos.length; i++) {
				$('.medicamentos_disponibles_por_empresa table tbody').append(
					$('<tr>', {
						'class': ''
					}).append(
						$('<td>', {
							'text': datos[i].nombre
						})
					)
					.append(
						$('<td>', {
							'text': datos[i].stock
						})
					)
          .append(
						$('<td>', {
							'text': datos[i].cliente
						})
					)
				)
			}

    }


});
