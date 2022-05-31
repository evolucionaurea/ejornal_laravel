$(()=>{

	// Accidentes mensual
	let url = 'getAccidentesMesActual';
	axios.get(url)
		.then(response => {
			let datos = [];
			let nombres = [];
			let cantidad = [];
			datos = response.data.datos;

			if (datos.length > 0) {
				datos.map(item =>
					nombres.push(item.nombre)
				)
				datos.map(item =>
					cantidad.push(item.cantidad)
				)
				$('.resumen_graficos_ausentismos_mes').css('display', 'none');
				let ctx = document.getElementById("chart_accidentes");
				let data = {
					labels: nombres,
					datasets: [
						{
							data: cantidad,
							backgroundColor: [
								"#FF6384",
								"#36A2EB",
								"#FFCE56",
								"#327fa8",
								"#474cde",
								"#fa5788",
								"#5ae880",
								"#b7ba65",
								"#61edd6",
								"#c44727",
								"#541d1b",
								"#b59e7f",
								"#5c7e8a",
								"#484f52",
								"#8353c2"
							],
							hoverBackgroundColor: [
								"#FF4394",
								"#36A2EB",
								"#FFCE56",
								"#27678a",
								"#3d40ad",
								"#bf476b",
								"#44b361",
								"#787a42",
								"#43b09e",
								"#8f3a24",
								"#330f0d",
								"#80705b",
								"#3e545c",
								"#2d3133",
								"#644191"
							]
						},
					]
				};

				let options = {
					cutoutPercentage:40,
				};

				let myDoughnutChart = new Chart(ctx, {
					type: 'doughnut',
					data: data,
					options: options
				});

			} else {
				$('.resumen_graficos_ausentismos_mes').css('display', 'block');
			}


			let total = 0;
			datos.map(item =>
				total = total + item.cantidad
			)

			for (let i = 0; i < datos.length; i++) {
				$('.ausentismos_mes_porcentajes table tbody').append(
					$('<tr>', {
						'class': ''
					}).append(
						$('<td>', {
							'text': datos[i].nombre
						})
					)
					.append(
						$('<td>', {
							'text': (datos[i].cantidad * 100 / total).toFixed(2) + '%'
						})
					)
				)
			}

			$('.ausentismos_mes_porcentajes table').dataTable({
				order:[[1,'desc']],
				dom:'t'
			})
		});
	// Accidentes mensual



		// Accidentes anual
	let url_anual = 'getAccidentesAnual';
	axios.get(url_anual)
		.then(response => {
			let datos = [];
			let nombres = [];
			let cantidad = [];
			datos = response.data.datos;

			if (datos.length > 0) {
				datos.map(item =>
					nombres.push(item.nombre)
				)
				datos.map(item =>
					cantidad.push(item.cantidad)
				)
				$('.resumen_graficos_ausentismos_anual').css('display', 'none');
				let chart_accidentes_anual = document.getElementById("chart_accidentes_anual");
				let data_anual = {
					labels: nombres,
					datasets: [
						{
							data: cantidad,
							backgroundColor: [
								"#FF6384",
								"#36A2EB",
								"#FFCE56",
								"#327fa8",
								"#474cde",
								"#fa5788",
								"#5ae880",
								"#b7ba65",
								"#61edd6",
								"#c44727",
								"#541d1b",
								"#b59e7f",
								"#5c7e8a",
								"#484f52",
								"#8353c2"
							],
							hoverBackgroundColor: [
								"#FF4394",
								"#36A2EB",
								"#FFCE56",
								"#27678a",
								"#3d40ad",
								"#bf476b",
								"#44b361",
								"#787a42",
								"#43b09e",
								"#8f3a24",
								"#330f0d",
								"#80705b",
								"#3e545c",
								"#2d3133",
								"#644191"
							]
						},
					]
				};

				let options_anual = {
					cutoutPercentage:40,
				};

				let myDoughnutChart_anual = new Chart(chart_accidentes_anual, {
					type: 'doughnut',
					data: data_anual,
					options: options_anual
				});

			} else {
				$('.resumen_graficos_ausentismos_anual').css('display', 'block');
			}


			let total = 0;
			datos.map(item =>
				total = total + item.cantidad
			)

			for (let i = 0; i < datos.length; i++) {
			$('.ausentismos_anio_porcentajes table tbody').append(
				$('<tr>', {
						'class': ''
					}).append(
						$('<td>', {
							'text': datos[i].nombre
						})
					)
					.append(
						$('<td>', {
							'text': (datos[i].cantidad * 100 / total).toFixed(2) + '%'
						})
					)
				)
			}

			$('.ausentismos_anio_porcentajes table').dataTable({
				order:[[1,'desc']],
				dom:'t'
			})

		});
	// Accidentes anual




	$('[data-table="top_10_faltas"]').dataTable({
		order:[[1,'desc']],
		dom:'t'
	})
	$('[data-table="top_10_solicitudes_faltas"]').dataTable({
		order:[[1,'desc']],
		dom:'t'
	})


})