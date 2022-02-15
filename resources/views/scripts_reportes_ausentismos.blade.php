<script type="text/javascript">
    window.onload = function() {

        /////////////////////// LOGICA AUSENTISMOS /////////////////////////////
        let ausentismos = [];

        function poblarTablasAusentismos() {
            for (let i = 0; i < ausentismos.length; i++) {
                $(".resultados_reporte_ausentismos").append(
                    $('<tr>', {
                        'class': ''
                    }).append(
                        $('<td>', {
                            'text': ausentismos[i].cliente
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': ausentismos[i].trabajador
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': ausentismos[i].user
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': ausentismos[i].tipo
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': ausentismos[i].fecha_inicio
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': ausentismos[i].fecha_final
                        })
                    )
                    .append(
                        $('<td>', {
                            'text': ausentismos[i].dias_ausente
                        })
                    )
                )
            }
        }

        function getAusentismos() {
            fetch('/admin/reportes/ausentismos')
                .then(response => response.json())
                .then(
                    (resultado) => {
                        ausentismos = resultado
                        poblarTablasAusentismos();
                        $('.tabla_reporte_ausentismos').DataTable().destroy();

                        $('.tabla_reporte_ausentismos').DataTable({
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

        // Al cargar la pagina llamamos a todas las ausentismos
        getAusentismos();

        $("#reporte_ausentismo_todo").click(function() {

            fetch('/admin/reportes/ausentismos')
                .then(response => response.json())
                .then(
                    (resultado) => {
                        ausentismos = resultado

                        $('.tabla_reporte_ausentismos').DataTable().destroy();
                        $('.resultados_reporte_ausentismos tr').remove();
                        poblarTablasAusentismos();

                        $('.tabla_reporte_ausentismos').DataTable({
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

        $("#reporte_ausentismo_filtro").click(function() {
            let registro_desde_ausen = $('#reporte_ausentismos_desde').val();
            let ausen_desde_partes = registro_desde_ausen.split("/");
            let ausentismos_desde = new Date(+ausen_desde_partes[2], ausen_desde_partes[1] - 1, +ausen_desde_partes[0]);

            let registro_hasta_ausen = $('#reporte_ausentismos_hasta').val();
            let ausen_hasta_partes = registro_hasta_ausen.split("/");
            let ausentismos_hasta = new Date(+ausen_hasta_partes[2], ausen_hasta_partes[1] - 1, +ausen_hasta_partes[0]);

            axios.post('/admin/reportes/filtrar_ausentismos', {
                ausentismos_desde: ausentismos_desde,
                ausentismos_hasta: ausentismos_hasta
            }).then(respuesta => {
                ausentismos = respuesta.data;

                $('.tabla_reporte_ausentismos').DataTable().destroy();
                $('.resultados_reporte_ausentismos tr').remove();
                poblarTablasAusentismos();

                $('.tabla_reporte_ausentismos').DataTable({
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

        /////////////////////// LOGICA AUSENTISMOS /////////////////////////////


    };
</script>
