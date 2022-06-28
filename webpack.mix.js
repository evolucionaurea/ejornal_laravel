const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
.sass('resources/sass/app.scss', 'public/css').sourceMaps();

// mix.sass('resources/sass/app.scss', 'public/css');

// mix.scripts(
//   [
//   'resources/js/app.js',
//   'resources/js/graficos.js',
//   'resources/js/sidebar.js',
//   'resources/slick/slick.min.js',
//   'resources/js/slick.js',
//   'resources/js/web_oficial.js',
//   'resources/js/testimonios_home.js',
//   'resources/js/footer.js',
//   'resources/js/data_tables.js',
//   'resources/js/data_table_pdfmake.min.js',
//   'resources/js/data_table_fonts.js',
//   'resources/js/data_table_export_format.min.js',
//   'resources/js/ajax.js'
// ],
//    'public/js/app.js').sourceMaps();


mix.browserSync('http://ejornal.test/');

if(mix.inProduction()) {
  mix.version();
}
