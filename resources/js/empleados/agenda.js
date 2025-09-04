import { Calendar as fullCalendar } from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import listPlugin from '@fullcalendar/list'
import interactionPlugin, { Draggable } from '@fullcalendar/interaction'
import esLocale from '@fullcalendar/core/locales/es'


class Calendar{

	constructor(){

		Promise.all([
			get_template('/templates/agendar-evento')
		])
			.then(promise=>{

				this.form_event = promise[0]
				this.init()
			})

	}

	modal_turno(data=false){

		console.log(data)

		const form = $(this.form_event)
		form.find('[data-content="form-title"]').text(data==false ? 'Agendar Turno' : 'Editar Turno')

		if(data){
			form.find('[data-content="form-caption"]').text('Al cambiar de fecha/horario un turno no debe superponerse con otro turno ya agendado.')
		}

		form.find('[name="fecha_inicio"]')
			.datepicker({
				dateFormat:'dd/mm/yy'
			})
			.blur()

		form.find('[name="nomina_id"]').select2({
			placeholder:'Buscar Trabajador...',
			allowClear:true,
			ajax:{
				url:`/empleados/nominas/busqueda`,
				dateType:'json',
				method:'POST',
				data: params=> {
					return {
						search: params.term,
						type: 'public',
						_token:csfr,
						start:0,
						length:25,
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
			ajax:{
				url:`/empleados/usuarios/search`,
				dateType:'json',
				method:'POST',
				data: params=> {
					return {
						search: params.term,
						type: 'public',
						_token:csfr,
						start:0,
						length:25
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


	init(){

		console.log('calendar')

		let calendarEl = document.getElementById('calendar')
		let calendar = new fullCalendar(calendarEl, {
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


				const from_month = s.getMonth() + 1
				const to_month = e.getMonth() + 1

				const from_day = s.getDate()
				const to_day = e.getDate()

				const response = await axios.post('/empleados/agenda/buscar_turnos',{
					from:s,
					to:e
				})

				let events = []
				response.data.results.map(event=>{
					events.push({
						id:event.id,
						title:event.trabajador.nombre,
						start:event.fecha_inicio,
						end:event.fecha_final,
						classNames:['calendar-event',event.estado.referencia],						
					})
				})

				return events

			},
			eventClick: async (info)=>{

				const response = await axios.post(`/empleados/agenda/turno/${info.event.id}`)
				this.modal_turno(response.data)

			},
			eventDrop: async (info)=>{
				console.log(info.event.start)
			}
		})
		calendar.render()


		//////////////////
		$('[data-toggle="add-event"]').click(async btn=>{
			this.modal_turno()
		})
		$('body').on('submit','[data-form="agregar-turno"]',async form=>{

			form.preventDefault()
			const post = get_form(form.currentTarget)

			
			try{
				const response = await axios.post('/empleados/agenda/agregar_turno',post)
				toastr.success(response.data.message)
				
				//Swal.close()
				window.location.reload()

			}catch(error){
				toastr.error(error.response.data.message)
			}

		})


		$('[data-toggle="cancelar-turno"]').click(async btn=>{

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
					icon:'warning',
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
	}



}

new Calendar