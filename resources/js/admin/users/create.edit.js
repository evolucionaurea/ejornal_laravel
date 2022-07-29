$(()=>{

	const roles = [
		{id:1,name:'admin'},
		{id:2,name:'empleado'},
		{id:3,name:'cliente'}
	]

	const fields = [
		{roles:[2],class:'.mostrar_personal_interno'},
		{roles:[2],class:'.mostrar_clientes'},
		{roles:[2],class:'.mostrar_permiso_desplegables'},
		{roles:[2],class:'.mostrar_especialidades'},
		{roles:[2],class:'.mostrar_cuil'},
		{roles:[3],class:'.cliente_original'},
		{roles:[2],class:'.select_contratacion_users'},
		{roles:[2],class:'.liquidacion_onedrive_creacion_users'}
	]

	let mostrar_ocultar_campos = roleid=>{
		roleid = parseInt(roleid)
		fields.map(field=>{
			if(!field.roles.includes(roleid)){
				$(field.class).addClass('d-none')
			}else{

				$(field.class).removeClass('d-none')
			}
		})
	}
	mostrar_ocultar_campos($('[name="rol"]').val())


	$('[name="rol"]').on('change',e=>{
		const roleid = $(e.currentTarget).val()
		mostrar_ocultar_campos(roleid)
	})

	$('#cliente_select_multiple').select2({
		placeholder: 'Buscar...'
	}).trigger('change');

	$('#select_cliente_original').select2({
		placeholder: 'Buscar...'
	}).trigger('change');



})