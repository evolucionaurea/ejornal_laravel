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
		{roles:[2],class:'.mostrar_calle'},
		{roles:[2],class:'.mostrar_nro'},
		{roles:[2],class:'.mostrar_entre_calles'},
		{roles:[2],class:'.mostrar_localidad'},
		{roles:[2],class:'.mostrar_partido'},
		{roles:[2],class:'.mostrar_cod_postal'},
		{roles:[2],class:'.mostrar_permitir_fichada'},
		{roles:[2],class:'.mostrar_observaciones'},
		{roles:[2],class:'.select_contratacion_users'},
		{roles:[2],class:'.liquidacion_onedrive_creacion_users'},
		{roles:[3],class:'.cliente_original'},
		{roles:[4],class:'.grupos'}
	]

	let mostrar_ocultar_campos = roleid=>{
		roleid = parseInt(roleid)
		fields.map(field=>{
			///console.log(field.roles, field.class);
			if(!field.roles.includes(roleid)){
				$(field.class).addClass('d-none')
			}else{
				$(field.class).removeClass('d-none')
			}
		})
		switch (roleid) {
			case 2:
				$('.mostrar_clientes label').text('¿Para quien trabajará?');
				break;
			case 3:
				$('.mostrar_clientes label').text('¿Este usuario a que Cliente pertenece?');
				break;
			default:
				break

		}
	}
	mostrar_ocultar_campos($('[name="rol"]').val())


	$('[name="rol"]').on('change',e=>{
		const roleid = $(e.currentTarget).val()
		mostrar_ocultar_campos(roleid)
	})

	$('#cliente_select_multiple').select2({
		placeholder: 'Buscar...'
	}).trigger('change');

	/*$('#select_cliente_original').select2({
		placeholder: 'Buscar...'
	}).trigger('change');*/



})
