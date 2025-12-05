import DataTable from 'datatables.net-dt';
require('./agendas.js')

window.datatable_options.ordering = false;
new DataTable('.tabla', window.datatable_options);