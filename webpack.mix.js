const mix = require('laravel-mix');

mix.sass('resources/sass/app.scss', 'public/css')
//mix.js('resources/js/app.js', 'public/js');

mix.js('resources/js/app.optimized.js', 'public/js');

const jsEntries = {
	admin: [
		'resumen',

		'users',
		'users.create',
		'users.edit',

		'medicamentos',
		'medicamentos.movimientos',

		'agendas',

		'clientes',
		'clientes.show',

		'grupos',
		'grupos.create',
		'grupos.edit',

		'reportes.actividad_usuarios',
		'reportes.ausentismos',
		'reportes.certificaciones',
		'reportes.comunicaciones',
		'reportes.consultas',
		'reportes.fichadas_nuevas',
		'reportes.preocupacionales',
		'reportes.tareas_adecuadas'
	],
	empleados: [
		'resumen',
		'agenda',
		'cuenta',
		'liquidacion',

		'nominas',
		'nominas.movimientos',
		'nominas.show',
		'nominas.create',
		'nominas.edit',
		'nominas.historial',

		'caratulas',
		'caratulas.create',
		'caratulas.edit',
		'caratulas.show',

		'ausentismos',
		'ausentismos.create',
		'ausentismos.edit',
		'ausentismos.show',

		'certificados',
		'certificados_livianos',

		'documentaciones.show',

		'tareas_livianas',
		'tareas_livianas.create',
		'tareas_livianas.edit',

		'comunicaciones',
		'comunicaciones_livianas',

		'consultas.todas',
		'consultas.medicas',
		'consultas.medicas.create',
		'consultas.nutricionales',
		'consultas.nutricionales.create',
		'consultas.enfermeria',
		'consultas.enfermeria.create',

		'covid.testeos',
		'covid.vacunas',

		'medicamentos',
		'medicamentos.create',
		'medicamentos.movimientos',

		'preocupacionales',
		'preocupacionales.create',
		'preocupacionales.edit',

		'recetas.create'

	],
	clientes: [
		'resumen',
		'nominas',
		'nominas_historial',
		'nominas_movimientos',
		'ausentismos',
		'preocupacionales'
	],
	grupos: [
		'resumen',
		'resumen_cliente',
		'nominas',
		'nominas_movimientos',
		'nominas_historial',
		'ausentismos'
	]
}

// compilar los archivos de empleados
Object.keys(jsEntries).forEach(key => {
	const items = jsEntries[key];
	const folder = `resources/js/${key}/`;
	items.forEach(item => {
		let subfolder = item.includes('.') ? `${item.split('.')[0]}/` : '';
		//const file = item.includes('.') ? (item.split('.').length == 3 ? `${item.split('.')[1]}.${item.split('.')[2]}` : item.split('.')[1]) : item;
		let file;
		if (item.includes('.')) {
			if (item.split('.').length == 3) {
				subfolder = `${subfolder}/${item.split('.')[1]}/`
				file = item.split('.')[2];
			} else {
				file = item.split('.')[1];
			}
		} else {
			file = item;
		}
		mix.js(`${folder}${subfolder}${file}.js`, `public/js/${key}/${subfolder}${file}.js`);
	});
});
//mix.sourceMaps();


mix.browserSync('http://ejornal.test/');
mix.version();
