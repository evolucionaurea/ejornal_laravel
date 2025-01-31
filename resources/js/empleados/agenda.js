import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

let calendarEl = document.getElementById('calendar');
let calendar = new Calendar(calendarEl, {
  plugins: [ dayGridPlugin, timeGridPlugin, listPlugin ],
  initialView: 'dayGridMonth',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,listWeek'
  }
});
calendar.render();


$(()=>{

	$('[data-toggle="add-event"]').click(async btn=>{
		const response = await get_template('/templates/agendar-evento')

		const form = $(response)

		const swal = await Swal.fire({
			title: 'Agregar Turno',
			html:form,
			showCancelButton: true,
			showConfirmButton: false,
			width:720,
			cancelButtonText: 'Cancelar',
			didOpen:()=>{

				$('.swal2-popup [name="fecha_inicio"]')
					.datepicker({
						dateFormat:'dd/mm/yy'
					})
					.blur()

				$('.swal2-popup [name="nomina_id"]').select2({
					placeholder:'Select',
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

			}
		})

	})


	$('body').on('submit','[data-form="agregar-turno"]',async form=>{

		form.preventDefault()
		const post = get_form(form.currentTarget)

		try{
			const response = await axios.post('/empleados/agenda/agregar_turno',post)
			toastr.success(response.data.message)

			Swal.close()

		}catch(error){
			toastr.error(error.response.data.message)
		}

	})

})