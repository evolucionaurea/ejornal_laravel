const mix = require('laravel-mix');
require('dotenv').config(); // leer .env

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
		'reportes.tareas_adecuadas',
		'recetas'
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

		'recetas',
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

// compilar los archivos de cada grupo
Object.keys(jsEntries).forEach(key => {
    const items = jsEntries[key];
    const folder = `resources/js/${key}/`;

    items.forEach(item => {
        let subfolder = item.includes('.') ? `${item.split('.')[0]}/` : '';
        let file;

        if (item.includes('.')) {
            const parts = item.split('.');
            if (parts.length === 3) {
                subfolder = `${subfolder}/${parts[1]}/`;
                file = parts[2];
            } else {
                file = parts[1];
            }
        } else {
            file = item;
        }

        mix.js(
            `${folder}${subfolder}${file}.js`,
            `public/js/${key}/${subfolder}${file}.js`
        );
    });
});


const appUrl = process.env.APP_URL || 'http://ejornal_laravel.test';

if (!mix.inProduction()) {
    mix.browserSync({
        proxy: appUrl,
        port: 3000,
        open: true,
        notify: false,
        files: [
            'resources/views/**/*.blade.php',
            'public/js/**/*.js',
            'public/css/**/*.css',
        ],
    });
}

mix.version();


