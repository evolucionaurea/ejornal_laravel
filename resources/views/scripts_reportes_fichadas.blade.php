<script type="text/javascript">
    window.onload = function() {

        /////////////////////// LOGICA FICHADAS /////////////////////////////
        let fichadas = [];

        function poblarTablas() {
            for (let i = 0; i < fichadas.length; i++) {
              let egreso_formateado = (fichadas[i].egreso == null) ? 'Aún trabajando' : fichadas[i].egreso;
                $(".resultados_reporte_fichadas").append(
                    $('<tr>', {
                        'class': ''
                    }).append(
                        $('<td>', {
                            'text': fichadas[i].user
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': fichadas[i].cliente
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': fichadas[i].ingreso + ' hasta el ' + egreso_formateado
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': (fichadas[i].egreso == null) ? 'Aún trabajando' : fichadas[i].tiempo_dedicado
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': fichadas[i].ip
                        })
                    )
                )
            }
        }

        function getFichadas() {
            fetch('/admin/reportes/fichadas_nuevas')
                .then(res => res.json())
                .then(
                    (results) => {
                        fichadas = results
                        poblarTablas();
                        $('.tabla_reporte_fichadas').DataTable().destroy();

                        $('.tabla_reporte_fichadas').DataTable({
                            scrollY: 400,
                            dom: 'Bfrtip',
                            pageLength:10,
                        		lengthMenu:[10,25,50,100,250,500],
                            order: [[ 2, "desc" ]],
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

        // Al cargar la pagina llamamos a todas las fichadas
        getFichadas();

        $("#reporte_fichada_todo").click(function() {

            fetch('/admin/reportes/fichadas_nuevas')
                .then(res => res.json())
                .then(
                    (results) => {
                        fichadas = results

                        $('.tabla_reporte_fichadas').DataTable().destroy();
                        $('.resultados_reporte_fichadas tr').remove();
                        poblarTablas();

                        $('.tabla_reporte_fichadas').DataTable({
                            scrollY: 400,
                            dom: 'Bfrtip',
                            pageLength:10,
                        		lengthMenu:[10,25,50,100,250,500],
                            order: [[ 2, "desc" ]],
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

        $("#reporte_fichada_filtro").click(function() {
            let registro_desde = $('#reporte_fichadas_desde').val();
            let desde_partes = registro_desde.split("/");
            let fichadas_desde = new Date(+desde_partes[2], desde_partes[1] - 1, +desde_partes[0]);

            let registro_hasta = $('#reporte_fichadas_hasta').val();
            let hasta_partes = registro_hasta.split("/");
            let fichadas_hasta = new Date(+hasta_partes[2], hasta_partes[1] - 1, +hasta_partes[0]);

            axios.post('/admin/reportes/filtrar_fichadas_nuevas', {
                fichadas_desde: fichadas_desde,
                fichadas_hasta: fichadas_hasta
            }).then(response => {
                fichadas = response.data;
                console.log(fichadas);

                $('.tabla_reporte_fichadas').DataTable().destroy();
                $('.resultados_reporte_fichadas tr').remove();
                poblarTablas();

                $('.tabla_reporte_fichadas').DataTable({
                    scrollY: 400,
                    dom: 'Bfrtip',
                    pageLength:10,
                    lengthMenu:[10,25,50,100,250,500],
                    order: [[ 2, "desc" ]],
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

        /////////////////////// LOGICA FICHADAS /////////////////////////////


    };
</script>