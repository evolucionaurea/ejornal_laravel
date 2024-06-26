console.log('ResumenCliente')
export default class ResumenCliente {

	constructor(obj){


		this.path = obj.path

		axios.get(this.path)
			.then(response=>{
				this.data = response.data
				///return console.log(response)
				this.init()
			})
	}

	render_chart(obj){

		const chart_canvas = document.querySelector(`${obj.chart_canvas}`)

		///console.log(obj)

		let data_labels = {
			labels:[],
			datasets:[{
				label:obj.title,
				backgroundColor:[],
				//borderColor:this.colores_hover,
				data:[]
			}]
		}

		/*if(obj.chart_canvas=='#chart_ausentismos_anual'){
			console.log(this.data)
		}*/

		if(obj.data.length>0){
			let count = 0;
			let colour_assign = {}
			let total_dias = []

			obj.data.map(item=>{
				data_labels.labels.push(item.tipo.nombre)
				//data_labels.datasets[0].data.push(item.dias)
				data_labels.datasets[0].data.push( obj.nomina!=0 ? (item.dias/(obj.nomina*obj.dias_periodo)*100).toFixed(2) : 0 )
				data_labels.datasets[0].backgroundColor.push(item.tipo.color)
				total_dias.push(item.dias)
				count++
			})

			///console.log(colour_assign)

			let chart = chart_canvas.getContext("2d");
			this.chart_options.tooltips = {
				callbacks:{
					label:(tooltipItem,data)=>{
						const indx = tooltipItem.index
						//return `Cant. días: ${data.datasets[0].data[indx]}`
						return [
							`Indice: ${ data.datasets[0].data[indx] }%`,
							`Nómina: ${ obj.nomina }`,
							`Total días: ${total_dias[indx]}`
						]
					}
				}
			}
			new Chart(chart, {
				type: 'horizontalBar',
				data: data_labels,
				options: this.chart_options
			})
		}else{
			const tarjeta = $(chart_canvas).closest('.tarjeta')
			tarjeta.find('[data-toggle="blank-chart"]').removeClass('d-none')
			chart_canvas.remove()
		}

	}

	render_tables(){

		////let total_count = obj.data.reduce((partial,current)=>partial+current.total,0)


		const datos = [
			{
				tabla:'ausentismos-mes',
				data:'ausentismos_mes',
				nomina:'nomina_actual',
				dias_periodo:'cant_dias_mes'
			},
			{
				tabla:'ausentismos-mes-anterior',
				data:'ausentismos_mes_anterior',
				nomina:'nomina_mes_anterior',
				dias_periodo:'cant_dias_mes_anterior'
			},
			{
				tabla:'ausentismos-mes-anio-anterior',
				data:'ausentismos_mes_anio_anterior',
				nomina:'nomina_mes_anio_anterior',
				dias_periodo:'cant_dias_mes_anio_anterior'
			},
			{
				tabla:'ausentismos-anual',
				data:'ausentismos_anual',
				nomina:'nomina_promedio_actual',
				dias_periodo:'cant_dias_anio'
			}
		]

		//return console.log( this.data[datos[0].data] )



		datos.map(dt=>{

			let total_dias = 0
			let total_percent = 0
			let total_ausentismos = 0

			this.data[dt.data].map(row=>{

				const tr = dom('tr')
				const td_nombre = dom('td')
				const td_percent = dom('td')
				const td_value = dom('td')
				const td_dias = dom('td')

				//const percent = (row.total/total_count*100)
				//const percent = nomina_actual*

				td_nombre.html( `<i class="fa fa-square" style="color:${row.tipo.color}"></i> ${row.tipo.nombre}` )
				///console.log(row.tipo)
				td_value.text( row.total )
				td_dias.text(row.dias)

				total_ausentismos += row.total

				const percent = this.data[dt.nomina]!= 0 ? ((parseInt(row.dias)/(this.data[dt.nomina]*this.data[dt.dias_periodo]))*100) : 0
				td_percent.text(`${percent.toFixed(2)} %`)
				total_dias += parseInt(row.dias)
				total_percent += percent
				//total_nomina += this.data[dt.nomina]

				tr.append(td_nombre,td_value,td_dias,td_percent)
				$(`[data-table="${dt.tabla}"]`).append(tr)

			})

			//console.log(dt.data,total_dias,this.data[dt.nomina],this.data[dt.dias_periodo],total_percent)
			$(`[data-table="${dt.tabla}"] tfoot`).find('[data-content="total-ausentismos"]').text(total_ausentismos)
			$(`[data-table="${dt.tabla}"] tfoot`).find('[data-content="total-dias"]').text(total_dias)
			$(`[data-table="${dt.tabla}"] tfoot`).find('[data-content="total-percent"]').text(`${total_percent.toFixed(2)} %`)


			$(`[data-table="${dt.tabla}"]`).dataTable({
				order:[[1,'desc']],
				dom:'t',
				pageLength:40
			})

		})


	}


	init(){

		//console.log(this.data)


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
			"#000000",
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
			legend:{
				display:false
			},
			scales:{
				xAxes:[{
					ticks:{
						beginAtZero:true
					}
				}]
			}
		}

		// chart ausentismos mes actual
		this.render_chart({
			chart_canvas:'#chart_ausentismos_mes',
			data:this.data.ausentismos_mes,
			title:'Ausentismos Mes Actual',
			nomina:this.data.nomina_actual,
			dias_periodo:this.data.cant_dias_mes
		})

		// chart ausentismos año actual
		this.render_chart({
			chart_canvas:'#chart_ausentismos_anual',
			data:this.data.ausentismos_anual,
			title:'Ausentismos del Año',
			nomina:this.data.nomina_promedio_actual,
			dias_periodo:this.data.cant_dias_anio
		})



		// ausentismos mes actual
		this.render_tables()



		if('indices_mes_a_mes' in this.data){
			const chart_canvas = document.querySelector(`#chart_indice_ausentismos_anual`)
			const chart_test = chart_canvas.getContext("2d");
			const colours = [
				'ff0029',
				'377eb8',
				'66a61e',
				'984ea3',
				'00d2d5',
				'ff7f00',
				'af8d00',
				'7f80cd',
				'b3e900',
				'c42e60',
				'a65628'
			]

			const data = {
				labels:[],
				datasets:[{
					data:[],
					backgroundColor:[],
					row:[]
				}]
			}
			this.data.indices_mes_a_mes.map((row,k)=>{
				data.labels.push(row.month)
				data.datasets[0].data.push(row.indice)
				data.datasets[0].backgroundColor.push(`#9c27b0`)
				data.datasets[0].row.push(row)
				//console.log(row)
			})
			const tooltips = {

			}

			new Chart(chart_test, {
				type: 'bar',
				data:data,
				options: {
					legend:{
						display:false
					},
					scales:{
						yAxes:[{
							ticks:{
								beginAtZero:true
							}
						}]
					},
					tooltips:{
						callbacks:{
							label:(tooltipItem,data)=>{
								const indx = tooltipItem.index
								return [
									`Indice: ${ data.datasets[0].data[indx].toFixed(2) }%`,
									`Nómina: ${ data.datasets[0].row[indx].nomina }`,
									`Total días: ${ data.datasets[0].row[indx].dias }`
								]
							}
						}
					}
				}
			})
		}


	}

}