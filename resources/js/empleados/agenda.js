import { Calendar as fullCalendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin, { Draggable } from '@fullcalendar/interaction'
import esLocale from '@fullcalendar/core/locales/es'


class Calendar{

	constructor(){

		this.delay_interval = 60*3	
		this.calendar = null

		this.init()		
	}

	async modal_turno(data=false){

		//console.log(data)
		const promise = await Promise.all([
			get_template('/templates/agendar-evento'),
			axios.get('/empleados/agenda_motivos')
		])
		const form = $(promise[0])
		const motivos = promise[1].data.data

		form.find('[data-content="form-title"]').text(data==false ? 'Agendar Turno' : 'Editar Turno')

		if(motivos){
			motivos.map(motivo=>{
				const option = window.dom('option')
				option.val(motivo.id).text(motivo.nombre)
				form.find('[name="motivo_id"]').append(option)
			})
		}

		if(data){
			form.find('[data-content="form-caption"]').text('Al cambiar de fecha/horario un turno no debe superponerse con otro turno ya agendado.')

			form.find(`[name="user_id"] option[value="${data.user.id}"]`).remove()
			form.find('[name="user_id"]').append(`<option value="${data.turno.user_id}" selected>${data.turno.user.nombre} (${data.turno.user.especialidad.nombre})</option>`)
			form.find('[name="nomina_id"]').append(`<option value="${data.turno.nomina_id}" selected>${data.turno.trabajador.nombre}</option>`)
			form.find('[name="comentarios"]').val(data.turno.comentarios)
			form.find('[name="horario"]').val(data.turno.horario)
			form.find('[name="duracion"]').val(data.turno.duracion)
			form.find('[name="fecha_inicio"]').val(data.turno.fecha_inicio_date)

			form.find('[name="id"]').val(data.turno.id)
			form.find('[name="motivo_id"]').val(data.turno.motivo_id)
			
			form.find('[data-toggle="estados"]').removeClass('d-none')
			form.find('[name="estado_id"]').prop({
				required:true
			}).val(data.turno.estado_id)
		}

		form.find('[name="fecha_inicio"]')
			.datepicker({
				dateFormat:'dd/mm/yy'
			})
			.blur()

		form.find('[name="nomina_id"]').select2({
			placeholder:'Buscar Trabajador...',
			allowClear:true,
			width:'100%',
			dropdownParent: $('#modals'),
			ajax:{
				url:`/empleados/nominas/busqueda`,
        dataType:'json',
				method:'POST',
				data: params=> {
					return {
						search: params.term,
						estado:1,
						type: 'public',
						_token:csfr,
						start:0,
						length:250,
						draw:1
					};
				},
				processResults:response=>{
					return {
						results: $.map(response.data, obj=>{
							return {
								id: obj.id,
								text: obj.nombre
							}
						})
					}
				}
			}
		})
		form.find('[name="user_id"]').select2({
			placeholder:'Buscar Usuario...',
			allowClear:true,
			width:'100%',
			dropdownParent: $('#modals'),
			ajax:{
				url:`/empleados/usuarios/search`,
 				dataType:'json',
				method:'POST',
				data: params=> {
					return {
						search: params.term,
						type: 'public',
						_token:csfr,
						start:0,
						length:250
					};
				},
				processResults:response=>{
					return {
						results: response.result.map(obj=>{
							return {
								id: obj.id,
								text: `${obj.nombre} (${obj.especialidad.nombre})`,
								selected: obj.id==response.user.id
							}
						})
					}
				}
			}
		})


		$('#modals .modal-body').html(form)

		$('#modals .modal-dialog').addClass('modal-lg')
		$('#modals').modal('show')

	}
	async ver_turno(id){

		const template = await axios.get(`/empleados/agenda/ver-turno/${id}`)
		$('#modals .modal-body').html(template.data)

		$('#modals .modal-dialog').addClass('modal-lg')
		$('#modals').modal('show')

	}

	async proximos_turnos(){
		const turnos = await axios.post('/templates/proximos-turnos',{
			user:$('[name="usuarios"]').val()
		})
		$('[data-content="next-events"]').html(turnos.data)
	}

	async modal_comunicacion(data){

		///console.log(data)

		const ausentismo = data.nomina.ausentismos[0]

		const template = await get_template('/templates/form-comunicacion')
		const form = $(template)
		form.find('[name="id_ausentismo"]').val(ausentismo.id)
		
		form.find('[data-content="ausentismo_tipo"]').text(ausentismo.tipo.nombre)
		form.find('[data-content="fecha_inicio"]').text(ausentismo.fecha_inicio)
		form.find('[data-content="fecha_final"]').text(ausentismo.fecha_final)
		form.find('[data-content="total_dias"]').text(ausentismo.total_dias)
		form.find('[data-content="user"]').text(ausentismo.user)

		data.tipo_comunicaciones.map(tipo=>{
			const option = dom('option')
			option.val(tipo.id).text(tipo.nombre)
			form.find('[name="id_tipo"]').append(option)
		})

		$('#popups .modal-body').html(form)
		$('#popups').modal('show')

	}

	async modal_turno_atendido(turno){

		const user = await axios.get('/user/me')
		///return console.log(turno)

		const consulta_title = user.data.especialidad.nombre=='médico' ? 'Médica/Nutricional' : 'de Enfermería'
		
		const swal = await Swal.fire({
			icon:'question',
			allowOutsideClick:false,
			title:`¿Deseas cargar una Comunicación o Consulta ${consulta_title}?`,
			html:`<div class="small-comment">Si seleccionas cargar una comunicación, el trabajador deberá tener un ausentismo vigente.</div>`,

			input:'select',
			inputOptions:{
				'consulta':`Consulta ${consulta_title}`,
				'comunicacion':'Comunicación'
			},
			inputPlaceholder:'--Seleccionar--',
			inputValidator:value=>{
				return new Promise((resolve,reject)=>{
					if(value===''){
						resolve('Debes seleccionar una opción')
					}else{
						resolve()
					}
				})
			},

			showCancelButton:true,
			reverseButtons:true,
			cancelButtonText:'Cancelar',
			confirmButtonText:'Continuar'
		})
		if(swal.isDismissed) return false

		//Comunicación
		if(swal.value=='comunicacion'){

			// Verfico que tenga un ausentismo vigente
			const response_ausentismo = await axios.get(`/empleados/check-ausentismo/${turno.nomina_id}`)

			if(response_ausentismo.data.nomina.ausentismos.length==0){
				const swal_sin_ausentismos = await Swal.fire({
					icon:'error',
					title:'El trabajador seleccionado no posee un ausentismo vigente'
				})
				return false
			}

			// abrir pop carga comunicación
			return this.modal_comunicacion(response_ausentismo.data)
			//console.log('comunicación')
		}

		//Consulta
		if(swal.value=='consulta'){

			if(user.data.especialidad.nombre=='médico') {

				/// preguntar consulta medica o nutricional
				const swal_consulta = await Swal.fire({
					icon:'question',
					title:'¿Deseas cargar una Consulta Médica o Nutricional?',
					input:'select',
					inputOptions:{
						'medicas':'Consulta Médica',
						'nutricionales':'Consulta Nutricional'
					},
					inputPlaceholder:'--Seleccionar--',
					inputValidator:value=>{
						return new Promise((resolve,reject)=>{
							if(value===''){
								resolve('Debes seleccionar una opción')
							}else{
								resolve()
							}
						})
					},

					showCancelButton:true,
					reverseButtons:true,
					cancelButtonText:'Cancelar',
					confirmButtonText:'Continuar'
				})

				if(swal_consulta.isDenied) return false
				return window.location.href = `/empleados/consultas/${swal_consulta.value}/create?id_nomina=${turno.nomina_id}`
			}
			if(user.data.especialidad.nombre=='enfermero'){ 
				/// consulta enfermería
				return window.location.href = `/empleados/consultas/enfermeria/create?id_nomina=${turno.nomina_id}`
			}
		}
	}
	async modal_turno_sin_atender(turno){

		const swal = await Swal.fire({
			icon:'question',
			allowOutsideClick:false,
			title:`¿Deseas cargar un comentario u observación?`,
			html:`<div class="small-comment">Puedes describir los detalles del por qué no fue atendido este trabajador.</div>`,

			input:'textarea',			
			inputPlaceholder:'Comentarios...',		
			inputValidator:value=>{
				return new Promise((resolve,reject)=>{
					if(value===''){
						resolve('Debes agregar algún comentario')
					}else{
						resolve()
					}
				})
			},	

			showCancelButton:true,
			reverseButtons:true,
			cancelButtonText:'No deseo dejar comentarios',
			confirmButtonText:'Guardar Comentarios'
		})		
		if(swal.isDismissed) return false

		console.log(swal)

		const response = await axios.post(`/empleados/agenda/editar-turno/${turno.id}`,{
			mode:'comentarios',
			comentarios:swal.value
		})

		if(response.status!=200){
			toastr.error(response.data.message)
			return false
		}
		toastr.success('Comentarios agregados correctamente!')
		Swal.close()
		this.reload_calendar()

	}

	reload_calendar(){
		clearInterval(this.sync_event)
		this.proximos_turnos()
		if(this.calendar) this.calendar.refetchEvents()
		this.sync_event = setInterval(this.proximos_turnos,this.delay_interval*1000)
	}


	init(){

		console.log('calendar')

		let calendarEl = document.getElementById('calendar')
		this.calendar = new fullCalendar(calendarEl, {
			plugins: [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ],
			initialView: 'dayGridMonth',
			headerToolbar: {
				left: 'prev,next today',
				center: 'title',
				right: 'dayGridMonth,timeGridWeek,listWeek'
			},
			editable:true,
			droppable:true,
			eventDurationEditable:false,
			locale:esLocale,
			eventSources: async (view,element)=>{

				let s = new Date(view.start.valueOf()+(3*60*60000))
				let e = new Date(view.end.valueOf()+(3*59*60000))

				const response = await axios.post('/empleados/agenda/buscar_turnos',{
					from:s,
					to:e,
					user:$('[name="usuarios"]').val()
				})

				let events = []
				response.data.results.map(event=>{
					events.push({
						id:event.id,
						title:`${event.trabajador.nombre}`,
						start:event.fecha_inicio,
						end:event.fecha_final,						
						classNames:['calendar-event',event.estado.referencia],						
					})
				})

				return events

			},
			eventClick: async (info)=>{

				/* const response = await axios.post(`/empleados/agenda/turno/${info.event.id}`)
				if(response.data.turno.estado.referencia == 'cancelled'){
					toastr.error('El turno ha sido cancelado')
					return false
				}		 */		
				this.ver_turno(info.event.id)

			},
			eventDrop: async (info)=>{
				//console.log(info.event.start.toISOString())
				const id = info.event.id

				try {
					const response = await axios.post(`/empleados/agenda/editar-turno/${id}`,{
						id:id,
						nueva_fecha:info.event.start,
						mode:'mover'
					})
	
					toastr.success('El turno fue actualizado correctamente!')
					return true
				}catch(error){
					toastr.error(error.response.data.message)
					this.calendar.refetchEvents()
				}
				

			},
			/* eventContent:(arg)=>{		
				console.log(arg.event)						
				return {
					html:arg.event.title
				}
			} */
		})
		this.calendar.render()


		//////////////////
		$('[data-toggle="add-event"]').click(async btn=>{
			this.modal_turno()
		})
		$('body').on('submit','[data-form="agregar-turno"]',async form=>{

			form.preventDefault()
			const post = get_form(form.currentTarget)
			const status = $(form.currentTarget).find('[name="estado_id"] option:selected').attr('data-reference')
			
			try{
				const response = await axios.post('/empleados/agenda/guardar_turno',post)
				post.id = response.data.id
				
				if(!('success' in response.data) && !response.data.success){
					toastr.error('Hubo un error al guardar el turno')
					return false
				}
				
				//Swal.close()
				toastr.success(response.data.message)
				$('#modals').modal('hide')
				this.reload_calendar()

				/// Si fue atendido pregunto si desea cargar consulta o comunicación
				if(status=='attended'){
					this.modal_turno_atendido(response.data.turno)
				}

			}catch(error){
				//console.log(error)
				toastr.error(error.response.data.message)
			}

		})


		$('body').on('click','[data-toggle="cancelar-turno"]',async btn=>{

			const card = $(btn.currentTarget).closest('.timeline-card')
			const id = card.attr('data-id')
			
			const response = await axios.post(`/empleados/agenda/turno/${id}`)
			if(response.status!=200){
				toastr.error('El turno no fue encontrado')
				return false
			}
			
			const data = response.data
			const turno = data.turno
			const user = data.user
			
			// Chequeo si el turno fue creado por el mismo usuario o está asignado a su usuario
			if((turno.registra_user_id != null && turno.registra_user_id != user.id) || turno.user_id != user.id){
				Swal.fire({
					icon:'error',
					title:'No tienes permiso para cancelar este turno',
					html:'El turno fue creado por otra persona o está asignado a otro usuario.'
				})
				return false 
			}

			const swal = await SwalWarning.fire({
				title:'¿Seguro deseas cancelar este turno?'
			})
			if(!swal.value) return false 

			const response_edit = await axios.post(`/empleados/agenda/editar-turno/${id}`,{
				mode:'status',
				status:'cancelled'
			})

			if(response_edit.status!=200){
				toastr.error(response_edit.data.message)
				return false
			}

			toastr.success('Actualizado correctamente!')
			$('#modals').modal('hide')
			this.reload_calendar()

		})
		$('body').on('click','[data-toggle="editar-turno"]',async btn=>{
			const card = $(btn.currentTarget).closest('.timeline-card')
			const id = card.attr('data-id')
			const response = await axios.post(`/empleados/agenda/turno/${id}`)
			this.modal_turno(response.data)
		})
		$('body').on('click','[data-toggle="change-status"]',async btn=>{
			const status = $(btn.currentTarget).attr('data-value')
			const card = $(btn.currentTarget).closest('.timeline-card')
			const id = card.attr('data-id')

			//return console.log(status)
			
			const response = await axios.post(`/empleados/agenda/editar-turno/${id}`,{
				mode:'status',
				status:status
			})

			if(response.status!=200){
				toastr.error(response.data.message)
				return false
			}
			toastr.success('Actualizado correctamente!')
			$('#modals').modal('hide')			
			this.reload_calendar()

			/// Si fue atendido pregunto si desea cargar consulta o comunicación
			if(status=='attended'){
				this.modal_turno_atendido(response.data.turno)
			}

			/// Si no fue atendido pregunto si desea cargar un comentario
			if(status=='absent'){
				this.modal_turno_sin_atender(response.data.turno)
			}

		})

		$('[name="usuarios"]').change(select=>{
			this.reload_calendar()
		})
		
		this.sync_event = setInterval(this.proximos_turnos,this.delay_interval*1000)
		this.proximos_turnos()
	}



}

new Calendar