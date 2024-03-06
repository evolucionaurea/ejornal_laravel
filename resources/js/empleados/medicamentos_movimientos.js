import Tablas from '../classes/Tablas.js';

$(()=>{

	new Tablas({
		controller:'/empleados/medicamentos_movimientos',
		get_path:'/busqueda',
		table:$('[data-table="medicamentos-movimientos"]'),
		modulo_busqueda:$('[data-toggle="busqueda-filtros"]'),
		///datatable_options:{order:[[ 0, "desc" ]]},
		server_side:true,

		datatable_options:{
			order:[[0,'asc']],

			dom:'<"table-spacer-top"l>t<"table-spacer-bottom"ip>',

			columns:[

				{
					data:'medicamento',
					name:'medicamento',
					className:'align-middle border-left'
				},
				{
					data:null,
					name:'tipo_consulta',
					className:'align-middle border-left',
					render:v=>{
						if(v.id_consulta_enfermeria==null && v.id_consulta_medica==null) return '<span class="text-muted font-italic">[ingreso / egreso]</span>'
						if(v.id_consulta_enfermeria!=null) return 'Enfermería'
						if(v.id_consulta_medica!=null) return 'Médica'
						return ''
					}
				},
				{
					data:'user',
					name:'user',
					className:'align-middle border-left'
				},
				{
					data:'cliente',
					name:'cliente',
					className:'align-middle border-left'
				},
				{
					data:'trabajador',
					name:'trabajador',
					className:'align-middle border-left',
					sortable:false,
					render:v=>{
						if(v==null) return '<span class="text-muted font-italic">[no aplica]</span>'
						return v
					}
				},
				{
					data:'ingreso',
					name:'ingreso',
					className:'align-middle border-left',
					render:v=>{
						return v==null || v==0 ? '-' : v
					}
				},
				{
					data:'suministrados',
					name:'suministrados',
					className:'align-middle border-left',
					render:v=>{
						return v==null || v==0 ? '-' : v
					}
				},
				{
					data:'egreso',
					name:'egreso',
					className:'align-middle border-left',
					render:v=>{
						return v==null || v==0 ? '-' : v
					}
				},
				{
					data:'motivo',
					name:'motivo',
					className:'align-middle border-left',

					render:v=>{
						return v=='' ? '<span class="text-muted font-italic">[no indicado]</span>' : v
					}
				},
				{
					data:'fecha_ingreso',
					name:'fecha_ingreso',
					className:'align-middle border-left'
				}
			]
		}


		/*render_row: medicamento => {
			console.log(medicamento);
			const formatDate = (date) => {
				if (!date) return '';
				const d = new Date(date);
				const formattedDate = `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
				const isoFormattedDate = `${d.getFullYear()}-${(d.getMonth() + 1).toString().padStart(2, '0')}-${d.getDate().toString().padStart(2, '0')}`;
				return { formattedDate, isoFormattedDate };
			};

			const { formattedDate: formattedFechaIngreso, isoFormattedDate: isoFechaIngreso } = formatDate(medicamento.fecha_ingreso);
			const { formattedDate: formattedCreatedAt, isoFormattedDate: isoCreatedAt } = formatDate(medicamento.created_at);

			return $(`
				<tr>
					<td>${medicamento.medicamento}</td>
					<td>${medicamento.tipo_consulta}</td>
					<td>${medicamento.user}</td>
					<td>${medicamento.trabajador != null ? medicamento.trabajador : 'No disponible'}</td>
					<td>${medicamento.cliente}</td>

					<td>${medicamento.suministrados != null && medicamento.suministrados != 0 ? medicamento.suministrados : ''}</td>
					<td>${medicamento.egreso != null && medicamento.egreso != 0 ? medicamento.egreso : ''}</td>
					<td>${medicamento.motivo != null && medicamento.motivo != 0 ? medicamento.motivo : ''}</td>
					<td data-order="${isoFechaIngreso}">${formattedFechaIngreso}</td>
					<td data-order="${isoCreatedAt}">${formattedCreatedAt}</td>
				</tr>`
			);
		}*/


	})

	$('[data-toggle="busqueda-filtros"]').find('[name="from"], [name="to"]').datepicker()

})
