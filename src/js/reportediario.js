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

    // document.getElementById('usuario').addEventListener('change', () => {
    //     const usuarioId = document.getElementById('usuario').value;
    //     const fecha = document.getElementById('fecha').value;
    //     cargarDatosReporte(usuarioId, fecha);
    //     console.log('Cargando reporte para:', usuarioId, fecha);
    // });
    
    // document.getElementById('fecha').addEventListener('change', () => {
    //     const usuarioId = document.getElementById('usuario').value;
    //     const fecha = document.getElementById('fecha').value;
    //     cargarDatosReporte(usuarioId, fecha);
    //     console.log('Cargando reporte para:', usuarioId, fecha);
    // });
    
    
    // async function cargarDatosReporte(usuarioId, fecha) {
    //     try {
    //         const response = await fetch(`/api/reporte-diario/${usuarioId}/${fecha}`);
    //         if (!response.ok) throw new Error('Error al obtener los datos del reporte');
    
    //         const datos = await response.json();
    
    //         // Llenar tabla de reservas
    //         const tablaReservas = $('#tablaReservas').DataTable();
    //         tablaReservas.clear();
    //         datos.reservas.forEach(reserva => {
    //             tablaReservas.row.add([
    //                 reserva.No_Reserva,
    //                 reserva.Habitaciones,
    //                 reserva.Descuento,
    //                 reserva.Cobro_Extra,
    //                 reserva.Adelanto,
    //                 reserva.Penalidad,
    //                 reserva.Ventas_Servicios,
    //                 reserva.Total,
    //                 reserva.Tiempo_Rebasado
    //             ]);
    //         });
    //         tablaReservas.draw();
    
    //         // Llenar tabla de servicios/ventas
    //         const tablaServicios = $('#tablaServicios').DataTable();
    //         tablaServicios.clear();
    //         datos.ventas.forEach(venta => {
    //             tablaServicios.row.add([
    //                 venta.ID_Pago,
    //                 venta.Tipo,
    //                 venta.Habitacion,
    //                 venta.Articulo,
    //                 venta.Precio_Unitario,
    //                 venta.Cantidad,
    //                 venta.Total,
    //                 venta.Hora,
    //                 venta.Responsable
    //             ]);
    //         });
    //         tablaServicios.draw();
    
    //         // Actualizar totales (si los estás mostrando en tarjetas o elementos HTML)
    //         document.getElementById('ventasServicios').textContent = `$${datos.ventasServicios.toFixed(2)}`;
    //         document.getElementById('totalTablaAlquiler').textContent = `$${datos.totalTablaAlquiler.toFixed(2)}`;
    //         document.getElementById('totalReservaciones').textContent = `$${datos.totalReservaciones.toFixed(2)}`;
    //         document.getElementById('ventasPublico').textContent = `$${datos.ventasPublico.toFixed(2)}`;
    //         document.getElementById('totalFinal').textContent = `$${datos.totalFinal.toFixed(2)}`;
    
    //     } catch (error) {
    //         console.error(error);
    //         alert('Ocurrió un error al cargar los datos del reporte.');
    //     }
    // }
    
}
