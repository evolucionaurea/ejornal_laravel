<script type="text/javascript">
    window.onload = function() {

        /////////////////////// LOGICA CONSULTAS MEDICAS /////////////////////////////
        let consultas_medicas = [];

        function poblarTablasConsultasMedicas() {
            for (let i = 0; i < consultas_medicas.length; i++) {
                $(".resultados_reporte_consultas_medicas").append(
                    $('<tr>', {
                        'class': ''
                    }).append(
                        $('<td>', {
                            'text': consultas_medicas[i].nombre
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_medicas[i].cliente
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_medicas[i].temperatura_auxiliar
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_medicas[i].peso
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_medicas[i].altura
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_medicas[i].derivacion_consulta
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_medicas[i].diagnostico
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_medicas[i].fecha
                        })
                    )
                )
            }
        }

        function getConsultasMedicas() {
            fetch('/admin/reportes/consultas_medicas')
                .then(response => response.json())
                .then(
                    (resultado) => {
                        consultas_medicas = resultado
                        poblarTablasConsultasMedicas();
                        $('.tabla_reporte_consultas_medicas').DataTable().destroy();

                        $('.tabla_reporte_consultas_medicas').DataTable({
                            scrollY: 400,
                            dom: 'Bfrtip',
                            pageLength:10,
                        		lengthMenu:[10,25,50,100,250,500],
                            order: [[ 3, "desc" ]],
                            buttons: [{
                                    extend: 'copy',
                                    text: 'Copiar',
                                },
                                {
                                    extend: 'excel',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'pdf',
                                    text: 'PDF',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                    title: 'eJornal',
                                }
                            ],
                            responsive: true,
                            "language": {
                                "sProcessing": "Procesando...",
                                "sLengthMenu": "Mostrar _MENU_ registros",
                                "sZeroRecords": "No se encontraron resultados",
                                "sEmptyTable": "Ningún dato disponible en esta tabla",
                                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                "sInfoPostFix": "",
                                "sSearch": "Buscar:",
                                "sUrl": "",
                                "sInfoThousands": ",",
                                "sLoadingRecords": "Cargando...",
                                "oPaginate": {
                                    "sFirst": "Primero",
                                    "sLast": "Último",
                                    "sNext": "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "oAria": {
                                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                }
                            }

                        });

                    });
        }

        // Al cargar la pagina llamamos a todas las consultas
        getConsultasMedicas();

        $("#reporte_consultas_medicas_todo").click(function() {

            fetch('/admin/reportes/consultas_medicas')
                .then(response => response.json())
                .then(
                    (resultado) => {
                        consultas_medicas = resultado

                        $('.tabla_reporte_consultas_medicas').DataTable().destroy();
                        $('.resultados_reporte_consultas_medicas tr').remove();
                        poblarTablasConsultasMedicas();

                        $('.tabla_reporte_consultas_medicas').DataTable({
                            scrollY: 400,
                            dom: 'Bfrtip',
                            pageLength:10,
                        		lengthMenu:[10,25,50,100,250,500],
                            order: [[ 5, "desc" ]],
                            buttons: [{
                                    extend: 'copy',
                                    text: 'Copiar',
                                },
                                {
                                    extend: 'excel',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'pdf',
                                    text: 'PDF',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                    title: 'eJornal',
                                }
                            ],
                            responsive: true,
                            "language": {
                                "sProcessing": "Procesando...",
                                "sLengthMenu": "Mostrar _MENU_ registros",
                                "sZeroRecords": "No se encontraron resultados",
                                "sEmptyTable": "Ningún dato disponible en esta tabla",
                                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                "sInfoPostFix": "",
                                "sSearch": "Buscar:",
                                "sUrl": "",
                                "sInfoThousands": ",",
                                "sLoadingRecords": "Cargando...",
                                "oPaginate": {
                                    "sFirst": "Primero",
                                    "sLast": "Último",
                                    "sNext": "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "oAria": {
                                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                }
                            }

                        });

                    });

        });

        $("#reporte_consultas_medicas_filtro").click(function() {
            let registro_desde_medic = $('#reporte_consultas_medicas_desde').val();
            let medic_desde_partes = registro_desde_medic.split("/");
            let consultas_medicas_desde = new Date(+medic_desde_partes[2], medic_desde_partes[1] - 1, +medic_desde_partes[0]);

            let registro_hasta_medic = $('#reporte_consultas_medicas_hasta').val();
            let medic_hasta_partes = registro_hasta_medic.split("/");
            let consultas_medicas_hasta = new Date(+medic_hasta_partes[2], medic_hasta_partes[1] - 1, +medic_hasta_partes[0]);

            axios.post('/admin/reportes/filtrar_consultas_medicas', {
                consultas_medicas_desde: consultas_medicas_desde,
                consultas_medicas_hasta: consultas_medicas_hasta
            }).then(respuesta => {
                consultas_medicas = respuesta.data;

                $('.tabla_reporte_consultas_medicas').DataTable().destroy();
                $('.resultados_reporte_consultas_medicas tr').remove();
                poblarTablasConsultasMedicas();

                $('.tabla_reporte_consultas_medicas').DataTable({
                    scrollY: 400,
                    dom: 'Bfrtip',
                    pageLength:10,
                    lengthMenu:[10,25,50,100,250,500],
                    order: [[ 5, "desc" ]],
                    buttons: [{
                            extend: 'copy',
                            text: 'Copiar',
                        },
                        {
                            extend: 'excel',
                            title: 'eJornal',
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            title: 'eJornal',
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'eJornal',
                        }
                    ],
                    responsive: true,
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }

                });


            }).catch(e => {
                console.log(e);
            });

        });

        /////////////////////// LOGICA CONSULTAS MEDICAS /////////////////////////////






        /////////////////////// LOGICA CONSULTAS ENFERMERIA /////////////////////////////
        let consultas_enfermerias = [];

        function poblarTablasConsultasEnfermerias() {
          console.log(consultas_enfermerias);
            for (let i = 0; i < consultas_enfermerias.length; i++) {
                $(".resultados_reporte_consultas_enfermerias").append(
                    $('<tr>', {
                        'class': ''
                    }).append(
                        $('<td>', {
                            'text': consultas_enfermerias[i].nombre
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_enfermerias[i].cliente
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_medicas[i].temperatura_auxiliar
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_enfermerias[i].peso
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_enfermerias[i].altura
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_enfermerias[i].derivacion_consulta
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_enfermerias[i].diagnostico
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': consultas_enfermerias[i].fecha
                        })
                    )
                )
            }
        }

        function getConsultasEnfermerias() {
            fetch('/admin/reportes/consultas_enfermeria')
                .then(response => response.json())
                .then(
                    (resultado) => {
                        consultas_enfermerias = resultado
                        poblarTablasConsultasEnfermerias();
                        $('.tabla_reporte_consultas_enfermerias').DataTable().destroy();

                        $('.tabla_reporte_consultas_enfermerias').DataTable({
                            scrollY: 400,
                            dom: 'Bfrtip',
                            pageLength:10,
                        		lengthMenu:[10,25,50,100,250,500],
                            order: [[ 5, "desc" ]],
                            buttons: [{
                                    extend: 'copy',
                                    text: 'Copiar',
                                },
                                {
                                    extend: 'excel',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'pdf',
                                    text: 'PDF',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                    title: 'eJornal',
                                }
                            ],
                            responsive: true,
                            "language": {
                                "sProcessing": "Procesando...",
                                "sLengthMenu": "Mostrar _MENU_ registros",
                                "sZeroRecords": "No se encontraron resultados",
                                "sEmptyTable": "Ningún dato disponible en esta tabla",
                                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                "sInfoPostFix": "",
                                "sSearch": "Buscar:",
                                "sUrl": "",
                                "sInfoThousands": ",",
                                "sLoadingRecords": "Cargando...",
                                "oPaginate": {
                                    "sFirst": "Primero",
                                    "sLast": "Último",
                                    "sNext": "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "oAria": {
                                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                }
                            }

                        });

                    });
        }

        // Al cargar la pagina llamamos a todas las consultas
        getConsultasEnfermerias();

        $("#reporte_consultas_enfermerias_todo").click(function() {

            fetch('/admin/reportes/consultas_enfermeria')
                .then(response => response.json())
                .then(
                    (resultado) => {
                        consultas_enfermerias = resultado

                        $('.tabla_reporte_consultas_enfermerias').DataTable().destroy();
                        $('.resultados_reporte_consultas_enfermerias tr').remove();
                        poblarTablasConsultasEnfermerias();

                        $('.tabla_reporte_consultas_enfermerias').DataTable({
                            scrollY: 400,
                            dom: 'Bfrtip',
                            pageLength:10,
                        		lengthMenu:[10,25,50,100,250,500],
                            order: [[ 5, "desc" ]],
                            buttons: [{
                                    extend: 'copy',
                                    text: 'Copiar',
                                },
                                {
                                    extend: 'excel',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'pdf',
                                    text: 'PDF',
                                    title: 'eJornal',
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                    title: 'eJornal',
                                }
                            ],
                            responsive: true,
                            "language": {
                                "sProcessing": "Procesando...",
                                "sLengthMenu": "Mostrar _MENU_ registros",
                                "sZeroRecords": "No se encontraron resultados",
                                "sEmptyTable": "Ningún dato disponible en esta tabla",
                                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                                "sInfoPostFix": "",
                                "sSearch": "Buscar:",
                                "sUrl": "",
                                "sInfoThousands": ",",
                                "sLoadingRecords": "Cargando...",
                                "oPaginate": {
                                    "sFirst": "Primero",
                                    "sLast": "Último",
                                    "sNext": "Siguiente",
                                    "sPrevious": "Anterior"
                                },
                                "oAria": {
                                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                                }
                            }

                        });

                    });

        });

        $("#reporte_consultas_enfermerias_filtro").click(function() {
            let registro_desde_enferme = $('#reporte_consultas_enfermerias_desde').val();
            let enferme_desde_partes = registro_desde_enferme.split("/");
            let consultas_enfermerias_desde = new Date(+enferme_desde_partes[2], enferme_desde_partes[1] - 1, +enferme_desde_partes[0]);

            let registro_hasta_enferme = $('#reporte_consultas_enfermerias_hasta').val();
            let enferme_hasta_partes = registro_hasta_enferme.split("/");
            let consultas_enfermerias_hasta = new Date(+enferme_hasta_partes[2], enferme_hasta_partes[1] - 1, +enferme_hasta_partes[0]);

            axios.post('/admin/reportes/filtrar_consultas_enfermeria', {
                consultas_enfermerias_desde: consultas_enfermerias_desde,
                consultas_enfermerias_hasta: consultas_enfermerias_hasta
            }).then(respuesta => {
                consultas_enfermerias = respuesta.data;

                $('.tabla_reporte_consultas_enfermerias').DataTable().destroy();
                $('.resultados_reporte_consultas_enfermerias tr').remove();
                poblarTablasConsultasEnfermerias();

                $('.tabla_reporte_consultas_enfermerias').DataTable({
                    scrollY: 400,
                    dom: 'Bfrtip',
                    pageLength:10,
                    lengthMenu:[10,25,50,100,250,500],
                    order: [[ 5, "desc" ]],
                    buttons: [{
                            extend: 'copy',
                            text: 'Copiar',
                        },
                        {
                            extend: 'excel',
                            title: 'eJornal',
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            title: 'eJornal',
                        },
                        {
                            extend: 'print',
                            text: 'Imprimir',
                            title: 'eJornal',
                        }
                    ],
                    responsive: true,
                    "language": {
                        "sProcessing": "Procesando...",
                        "sLengthMenu": "Mostrar _MENU_ registros",
                        "sZeroRecords": "No se encontraron resultados",
                        "sEmptyTable": "Ningún dato disponible en esta tabla",
                        "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                        "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sSearch": "Buscar:",
                        "sUrl": "",
                        "sInfoThousands": ",",
                        "sLoadingRecords": "Cargando...",
                        "oPaginate": {
                            "sFirst": "Primero",
                            "sLast": "Último",
                            "sNext": "Siguiente",
                            "sPrevious": "Anterior"
                        },
                        "oAria": {
                            "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                        }
                    }

                });


            }).catch(e => {
                console.log(e);
            });

        });

        /////////////////////// LOGICA CONSULTAS ENFERMERIA /////////////////////////////


    };
</script>
