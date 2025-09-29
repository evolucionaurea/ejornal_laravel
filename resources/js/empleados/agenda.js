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

	async cargar_turnos(){

		//$('[data-content="next-events"]').html('Actualizando...')
		//const template = await get_template('/templates/proximos-turnos')
		const turnos = await axios.post('/templates/proximos-turnos',{
			user:$('[name="usuarios"]').val()
		})
		$('[data-content="next-events"]').html(turnos.data)
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
				console.log(info.event.start.toISOString())
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
			
			try{
				const response = await axios.post('/empleados/agenda/guardar_turno',post)
				
				if(!('success' in response.data) && !response.data.success){
					toastr.error('Hubo un error al guardar el turno')
					return false
				}
				
				//Swal.close()
				toastr.success(response.data.message)
				window.location.reload()

			}catch(error){
				console.log(error)
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
				mode:'cancel'
			})

			if(response.status!=200){
				toastr.error(response.data.message)
				return false
			}
			window.location.reload()

		})
		$('body').on('click','[data-toggle="editar-turno"]',async btn=>{
			const card = $(btn.currentTarget).closest('.timeline-card')
			const id = card.attr('data-id')
			const response = await axios.post(`/empleados/agenda/turno/${id}`)
			this.modal_turno(response.data)
		})

		$('[name="usuarios"]').change(select=>{
			clearInterval(this.sync_event)
			this.cargar_turnos()
			
			// Refresh calendar to show events for selected user
			if(this.calendar){
				this.calendar.refetchEvents()
			}

			this.sync_event = setInterval(this.cargar_turnos,this.delay_interval*1000)
		})
		
		this.sync_event = setInterval(this.cargar_turnos,this.delay_interval*1000)
		this.cargar_turnos()
	}



}

new Calendar