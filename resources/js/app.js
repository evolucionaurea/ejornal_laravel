require('./bootstrap');
import Chart from 'chart.js';
require('../slick/slick.min.js');
require('./slick.js');
require('./graficos');
require('./sidebar');
require('./menu');
require('./data_tables.js');
require('./web_oficial.js');
require('./footer.js');
require('./ajax.js');


window.Vue = require('vue');
import Vuetify from 'vuetify';
Vue.use(Vuetify);


Vue.component('prueba-component', require('./components/PruebaComponent.vue').default);

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
});
