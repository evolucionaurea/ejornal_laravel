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
			order:[[7,'desc']],

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
					data:null,
					name:'user',
					orderable:false,
					className:'align-middle border-left',
					render:v=>{
						if(v.id_consulta_enfermeria != null) return v.user_consulta_enfermeria
						if(v.id_consulta_medica != null) return v.user_consulta_medica
						return v.user
					}
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
					data:'created_at',
					name:'created_at',
					className:'align-middle border-left',
					render:v=>{
						return `${v} hs.`
					}
				}
			]
		}

	})

	$('[data-toggle="busqueda-filtros"]').find('[name="from"], [name="to"]').datepicker()

})
