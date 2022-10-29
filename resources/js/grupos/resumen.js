$(()=>{

	$('[data-table="ausentismos"]').dataTable({
		dom:'t',
		ordering:true,
		responsive:true
	})



	axios.get('index_ajax')
		.then(response=>{
			console.log(response.data.query)

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
				datasets:[]
			}
			let data_anual = {
				labels:[],
				datasets:[]
			}
			let options = {
				///cutoutPercentage:40,
				scales:{
					yAxes:[{
						ticks:{
							beginAtZero:true
						}
					}]
				}
			}


			// mensual
			let total_ausentismos_mes_count = 0
			if(response.data.ausentismos_mes.length>0){
				let mes_count = 0;
				response.data.ausentismos_mes.map(item=>{

					if(mes_count>mes_count.length) mes_count = 0

					data_mes.datasets.push({
						label:item.tipo.nombre,
						data:[item.total],
						backgroundColor:colores[mes_count],
						borderColor:colores_hover[mes_count],
						borderWidth:1
					})
					mes_count++
					total_ausentismos_mes_count += item.total


					//data_mes.labels.push(item.tipo.nombre)
					//data_mes.datasets[0].data.push(item.total)
				})
				let chart_mes = document.getElementById("chart_ausentismos_mes").getContext("2d");
				new Chart(chart_mes, {
					type: 'bar',
					data: data_mes,
					options: options
				})
			}else{
				$('[data-toggle="blank-chart-ausentismos-mes"]').removeClass('d-none')
				$('#chart_ausentismos_mes').remove()
			}

			// anual
			let total_ausentismos_anual_count = 0
			if(response.data.ausentismos_anual.length>0){
				let anual_count = 0;
				response.data.ausentismos_anual.map(item=>{
					//data_anual.labels.push(item.tipo.nombre)
					//data_anual.datasets[0].data.push(item.total)
					if(anual_count>anual_count.length) anual_count = 0
					data_anual.datasets.push({
						label:item.tipo.nombre,
						data:[item.total],
						backgroundColor:colores[anual_count],
						borderColor:colores_hover[anual_count],
						//hoverBackgroundColor:colores_hover[anual_count],
						borderWidth:1
					})
					anual_count++

					total_ausentismos_anual_count += item.total

				})
				let chart_anual = document.getElementById("chart_ausentismos_anual").getContext("2d");

				new Chart(chart_anual, {
					type: 'bar',
					data: data_anual,
					options: options
				})
			}else{
				$('[data-toggle="blank-chart-ausentismos-anual"]').removeClass('d-none')
				$('#chart_ausentismos_anual').remove()
			}


			// % mensual
			if(response.data.ausentismos_mes.length>0){

				response.data.ausentismos_mes.map(row=>{
					const tr = dom('tr')
					const td_nombre = dom('td')
					const td_value = dom('td')
					const percent = Math.round(row.total/total_ausentismos_mes_count*100)
					td_nombre.text(row.tipo.nombre)
					td_value.text( `${percent} %` )
					tr.append(td_nombre,td_value)
					$('[data-table="ausentismos-mes"]').append(tr)
				})

				$('[data-table="ausentismos-mes"]').dataTable({
					order:[[1,'desc']],
					dom:'t'
				})

			}
			// % anual
			if(response.data.ausentismos_anual.length>0){

				response.data.ausentismos_anual.map(row=>{
					const tr = dom('tr')
					const td_nombre = dom('td')
					const td_value = dom('td')
					const percent = Math.round(row.total/total_ausentismos_anual_count*100)
					td_nombre.text(row.tipo.nombre)
					td_value.text( `${percent} %` )
					tr.append(td_nombre,td_value)
					$('[data-table="ausentismos-anual"]').append(tr)
				})

				$('[data-table="ausentismos-anual"]').dataTable({
					order:[[1,'desc']],
					dom:'t'
				})

			}



		})

})
