<script type="text/javascript">
    window.onload = function() {

        /////////////////////// LOGICA COMUNICACIONES /////////////////////////////
        let comunicaciones = [];

        function poblarTablasComunicaciones() {
            for (let i = 0; i < comunicaciones.length; i++) {
                $(".resultados_reporte_comunicaciones").append(
                    $('<tr>', {
                        'class': ''
                    }).append(
                        $('<td>', {
                            'text': comunicaciones[i].nombre
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': comunicaciones[i].cliente
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': comunicaciones[i].tipo_ausentismo
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': comunicaciones[i].tipo_comunicacion
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': comunicaciones[i].user
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': comunicaciones[i].descripcion
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': comunicaciones[i].created_at
                        })
                    )
                )
            }
        }

        function getComunicaciones() {
            fetch('/admin/reportes/comunicaciones')
                .then(response => response.json())
                .then(
                    (resultado) => {
                        comunicaciones = resultado
                        poblarTablasComunicaciones();
                        $('.tabla_reporte_comunicaciones').DataTable().destroy();

                        $('.tabla_reporte_comunicaciones').DataTable({
                            scrollY: 400,
                            pageLength:10,
                        		lengthMenu:[10,25,50,100,250,500],
                            dom: 'Bfrtip',
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

        // Al cargar la pagina llamamos a todas las ausentismos
        getComunicaciones();

        $("#reporte_comunicaciones_todo").click(function() {

            fetch('/admin/reportes/comunicaciones')
                .then(response => response.json())
                .then(
                    (resultado) => {
                        comunicaciones = resultado

                        $('.tabla_reporte_comunicaciones').DataTable().destroy();
                        $('.resultados_reporte_comunicaciones tr').remove();
                        poblarTablasComunicaciones();

                        $('.tabla_reporte_comunicaciones').DataTable({
                            scrollY: 400,
                            pageLength:10,
                        		lengthMenu:[10,25,50,100,250,500],
                            dom: 'Bfrtip',
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

        $("#reporte_comunicaciones_filtro").click(function() {
            let registro_desde_comunica = $('#reporte_comunicaciones_desde').val();
            let comunica_desde_partes = registro_desde_comunica.split("/");
            let comunicaciones_desde = new Date(+comunica_desde_partes[2], comunica_desde_partes[1] - 1, +comunica_desde_partes[0]);

            let registro_hasta_comunica = $('#reporte_comunicaciones_hasta').val();
            let comunica_hasta_partes = registro_hasta_comunica.split("/");
            let comunicaciones_hasta = new Date(+comunica_hasta_partes[2], comunica_hasta_partes[1] - 1, +comunica_hasta_partes[0]);

            axios.post('/admin/reportes/filtrar_comunicaciones', {
                comunicaciones_desde: comunicaciones_desde,
                comunicaciones_hasta: comunicaciones_hasta
            }).then(respuesta => {
                comunicaciones = respuesta.data;

                $('.tabla_reporte_comunicaciones').DataTable().destroy();
                $('.resultados_reporte_comunicaciones tr').remove();
                poblarTablasComunicaciones();

                $('.tabla_reporte_comunicaciones').DataTable({
                    scrollY: 400,
                    pageLength:10,
                    lengthMenu:[10,25,50,100,250,500],
                    dom: 'Bfrtip',
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

        /////////////////////// LOGICA AUSENTISMOS /////////////////////////////


    };
</script>
