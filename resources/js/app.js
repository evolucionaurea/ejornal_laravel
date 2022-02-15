require('./bootstrap');

require('../slick/slick.min.js');
require('./slick.js');
require('./data_picker_code.js');
require('./data_tables.js');
require('./data_picker.js');

require('./sidebar');
require('./menu');
require('./footer.js');
require('./ajax.js');
require('./users.js');
require('./nav_sup.js');
require('./medicamentos.js');
require('./stock_medicamentos.js');
require('./documentacion_ausentismo.js');


window.Vue = require('vue');
import Vuetify from 'vuetify';
Vue.use(Vuetify);


Vue.component('prueba-component', require('./components/PruebaComponent.vue').default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
