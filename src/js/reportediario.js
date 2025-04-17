if (window.location.pathname === '/admin/reporte-diario') {
    // 🔧 Variables necesarias definidas manualmente
    const nombreHotel = 'Hotel Paraíso Tropical';

    function obtenerFechaFormateada() {
        const fecha = new Date();
        const year = fecha.getFullYear();
        const month = String(fecha.getMonth() + 1).padStart(2, '0');
        const day = String(fecha.getDate()).padStart(2, '0');
        const hours = String(fecha.getHours()).padStart(2, '0');
        const minutes = String(fecha.getMinutes()).padStart(2, '0');
        const seconds = String(fecha.getSeconds()).padStart(2, '0');
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    const fechaFormateada = obtenerFechaFormateada();

    const tituloReporteReservas = `Reporte de Reservaciones - ${nombreHotel}`;
    const tituloReporteServicios = `Reporte de Ventas y Servicios - ${nombreHotel}`;
    const mensajeTopReporte = `Generado el: ${fechaFormateada}`;

    const nombreArchivoReservas = `reservaciones_${fechaFormateada.replace(/[\s:]/g, '_')}`;
    const nombreArchivoServicios = `servicios_${fechaFormateada.replace(/[\s:]/g, '_')}`;

    // ✅ Configuración para tabla de Reservas
    const opcionesReservas = {
        responsive: true,
        paging: true,
        ordering: true,
        info: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        dom: '<"d-flex justify-content-between align-items-center mb-2"fB>rtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: tituloReporteReservas,
                messageTop: mensajeTopReporte,
                filename: nombreArchivoReservas,
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdfHtml5',
                title: tituloReporteReservas,
                messageTop: mensajeTopReporte,
                filename: nombreArchivoReservas,
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'portrait',
                pageSize: 'A4',
                exportOptions: {
                    columns: ':visible'
                },
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 10;
                    doc.styles.tableHeader.fontSize = 12;
                }
            }
        ]
    };

    // ✅ Configuración para tabla de Servicios
    const opcionesServicios = {
        ...opcionesReservas,
        buttons: [
            {
                extend: 'excelHtml5',
                title: tituloReporteServicios,
                messageTop: mensajeTopReporte,
                filename: nombreArchivoServicios,
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdfHtml5',
                title: tituloReporteServicios,
                messageTop: mensajeTopReporte,
                filename: nombreArchivoServicios,
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'portrait',
                pageSize: 'A4',
                exportOptions: {
                    columns: ':visible'
                },
                customize: function (doc) {
                    doc.defaultStyle.fontSize = 10;
                    doc.styles.tableHeader.fontSize = 12;
                }
            }
        ]
    };

    // Inicializar tablas
    $('#tablaReservas').DataTable(opcionesReservas);
    $('#tablaServicios').DataTable(opcionesServicios);
}
