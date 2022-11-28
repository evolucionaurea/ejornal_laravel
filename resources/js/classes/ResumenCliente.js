console.log('ResumenCliente')
export default class ResumenCliente {

	constructor(obj){


		this.path = obj.path
		axios.get(this.path)
			.then(response=>{
				this.data = response.data
				this.init()
			})
	}

	render_chart(obj){

		const chart_canvas = document.querySelector(`${obj.chart_canvas}`)

		let data_labels = {
			labels:[],
			datasets:[]
		}


		if(obj.data.length>0){
			let count = 0;
			obj.data.map(item=>{
				if(count>this.colores.length.length) count = 0
				data_labels.datasets.push({
					label:item.tipo.nombre,
					data:[item.total],
					backgroundColor:this.colores[count],
					borderColor:this.colores_hover[count],
					borderWidth:1
				})
				count++
				//ausentismos_count += item.total
			})
			let chart = chart_canvas.getContext("2d");
			new Chart(chart, {
				type: 'bar',
				data: data_labels,
				options: this.chart_options
			})
		}else{
			const tarjeta = $(chart_canvas).closest('.tarjeta')
			tarjeta.find('[data-toggle="blank-chart"]').removeClass('d-none')
			chart_canvas.remove()
		}

	}

	render_tables(obj){

		if(obj.data.length>0){
			let total_count = obj.data.reduce((partial,current)=>partial+current.total,0)

			obj.data.map(row=>{
				const tr = dom('tr')
				const td_nombre = dom('td')
				const td_percent = dom('td')
				const td_value = dom('td')
				const percent = (row.total/total_count*100)
				td_nombre.text(row.tipo.nombre)
				td_percent.text(`${percent.toFixed(2)} %`)
				td_value.text(row.total)
				tr.append(td_nombre,td_percent,td_value)
				$(`[data-table="${obj.table}"]`).append(tr)
			})

		}


		$(`[data-table="${obj.table}"]`).dataTable({
			order:[[1,'desc']],
			dom:'t'
		})


	}


	init(){

		console.log(this.data)


		this.colores = [
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
		this.colores_hover = [
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

		this.chart_options = {
			///cutoutPercentage:40,
			scales:{
				yAxes:[{
					ticks:{
						beginAtZero:true
					}
				}]
			}
		}

		// chart ausentismos mes actual
		this.render_chart({
			chart_canvas:'#chart_ausentismos_mes',
			data:this.data.ausentismos_mes
		})

		// chart ausentismos año actual
		this.render_chart({
			chart_canvas:'#chart_ausentismos_anual',
			data:this.data.ausentismos_anual
		})



		// ausentismos mes actual
		this.render_tables({
			data:this.data.ausentismos_mes,
			table:'ausentismos-mes'
		})

		// ausentismos mes anterior
		this.render_tables({
			data:this.data.ausentismos_mes_anterior,
			table:'ausentismos-mes-anterior'
		})


		// ausentismos mes año anterior
		this.render_tables({
			data:this.data.ausentismos_mes_anio_anterior,
			table:'ausentismos-mes-anio-anterior'
		})

		// ausentismos año actual
		this.render_tables({
			data:this.data.ausentismos_anual,
			table:'ausentismos-anual'
		})


	}

}