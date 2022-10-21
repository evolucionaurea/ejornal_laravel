$(()=>{

	axios.get('ausentismos_resumen')
		.then(response=>{
			//console.log(response.data)

			const colores = [
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
			]
			const colores_hover = [
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

			let data_mes = {
				labels:[],
				datasets:[
					{
						data:[],
						backgroundColor:colores,
						hoverBackgroundColor:colores_hover,
					}
				]
			}
			let data_anual = {
				labels:[],
				datasets:[
					{
						data:[],
						backgroundColor:colores,
						hoverBackgroundColor:colores_hover,
					}
				]
			}
			let options = {
				cutoutPercentage:40
			}


			// mensual
			if(response.data.ausentismos_mes.length>0){
				response.data.ausentismos_mes.map(item=>{
					data_mes.labels.push(item.tipo.nombre)
					data_mes.datasets[0].data.push(item.total)
				})
				let chart_mes = document.getElementById("chart_ausentismos_mes").getContext("2d");
				new Chart(chart_mes, {
					type: 'doughnut',
					data: data_mes,
					options: options
				})
			}else{
				$('[data-toggle="blank-chart-ausentismos-mes"]').removeClass('d-none')
				$('#chart_ausentismos_mes').remove()
			}

			// anual
			if(response.data.ausentismos_anual.length>0){
				response.data.ausentismos_anual.map(item=>{
					data_anual.labels.push(item.tipo.nombre)
					data_anual.datasets[0].data.push(item.total)
				})
				let chart_anual = document.getElementById("chart_ausentismos_anual").getContext("2d");
				new Chart(chart_anual, {
					type: 'doughnut',
					data: data_anual,
					options: options
				})
			}else{
				$('[data-toggle="blank-chart-ausentismos-anual"]').removeClass('d-none')
				$('#chart_ausentismos_anual').remove()
			}



		})
})
