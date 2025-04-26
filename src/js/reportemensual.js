if (window.location.pathname === '/admin/reporte-mensual') {

    let dataTableReservas;
    let dataTableServicios;
    let dataTableReservasInit = false;
    let dataTableServiciosInit = false;
    
    let nombreHotel;
    let telefonoHotel;
    let correoHotel;
    let ubicacionHotel;
    
    const urlHotel = '/api/hotel';  // URL para obtener datos del hotel
    
    async function obtenerDatosHotel() {
        try {
            const response = await fetch(urlHotel);
            const data = await response.json();
            nombreHotel = data.nombre || 'Tu Nombre Hotel';
            telefonoHotel = data.telefono || 'Tu Teléfono';
            correoHotel = data.correo || 'Tu Correo';
            ubicacionHotel = data.ubicacion || 'Tu Ubicación';
        } catch (error) {
            console.error('Error al obtener datos del hotel:', error);
        }
    }
    
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
    
    
    // Variables para totales mensuales
    let totalVentaServiciosMensual = 0;
    let totalReservacionesMensual = 0;
    let totalGeneralMensual = 0;

    let totalVentaDirectaMensual = 0;
    let totalVentaReservacionMensual = 0;
    let totalVentaGeneralMensual = 0;

    const configBase = {
        responsive: true,
        paging: true,
        ordering: true,
        info: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        dom: '<"d-flex justify-content-between align-items-center mb-2"fB>rtip'
    };

    async function initReporteMensual() {
        await obtenerDatosHotel(); // Primero obtenemos los datos del hotel
    
        const fechaFormateada = obtenerFechaFormateada();
        const mensajeTopReporte = `Generado el: ${fechaFormateada}
        Teléfono: ${telefonoHotel} | Correo: ${correoHotel} | Ubicación: ${ubicacionHotel}`;
        const tituloReporteReservas = `Reporte de Reservaciones - ${nombreHotel}`;
        const tituloReporteServicios = `Reporte de Ventas y Servicios - ${nombreHotel}`;
        const nombreArchivoReservas = `reservaciones_${fechaFormateada.replace(/[\s:]/g, '_')}`;
        const nombreArchivoServicios = `servicios_${fechaFormateada.replace(/[\s:]/g, '_')}`;
    
        const opcionesReservas = {
            ...configBase,
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
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: ':visible',
                        modifier: {
                            page: 'all'
                        }
                    },
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 10;
                        doc.styles.tableHeader.fontSize = 12;
                    }
                }
            ]
        };
    
        const opcionesServicios = {
            ...configBase,
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
                        columns: ':visible',
                        modifier: {
                            page: 'all'
                        }
                    },
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 10;
                        doc.styles.tableHeader.fontSize = 12;
                    }
                }
            ]
        };
    
        const usuarioId = document.getElementById('usuario').value || 'null';
        const mes = document.getElementById('mes').value;
        const anio = document.getElementById('anio').value;
    
        const url = `/api/reporte-mensual/${usuarioId}/${mes}/${anio}`;
    
        try {
            const response = await fetch(url);
            const { reservas, ventas } = await response.json();
    
            if (dataTableReservasInit) dataTableReservas.destroy();
            if (dataTableServiciosInit) dataTableServicios.destroy();
    
            llenarTablaReservas(reservas);
            llenarTablaServicios(ventas);
    
            dataTableReservas = $('#tablaReservas').DataTable(opcionesReservas);
            dataTableServicios = $('#tablaServicios').DataTable(opcionesServicios);
    
            dataTableReservasInit = true;
            dataTableServiciosInit = true;
        } catch (error) {
            console.error('Error al obtener reporte mensual:', error);
        }
    }
    
    function llenarTablaReservas(reservas) {
        const tbody = document.querySelector('#tablaReservas tbody');
        tbody.innerHTML = '';
    
        reservas.forEach((reserva, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${reserva.No_Reserva}</td>
                    <td>${reserva.Huesped}</td>
                    <td>${reserva.Habitaciones}</td>
                    <td>MXN$${reserva.Precio_Total}</td>
                    <td>MXN$${reserva.Descuento}</td>
                    <td>MXN$${reserva.Cobro_Extra}</td>
                    <td>MXN$${reserva.Adelanto}</td>
                    <td>MXN$${reserva.Penalidad}</td>
                    <td>MXN$${reserva.Ventas_Servicios}</td>
                    <td>MXN$${reserva.Total}</td>
                    <td>${reserva.Tiempo_Rebasado}</td>
                </tr>
            `;
            tbody.innerHTML += row;

            const ventasServicios = parseFloat(reserva.Ventas_Servicios || 0);
            const total = parseFloat(reserva.Total || 0);

            totalVentaServiciosMensual += ventasServicios;
            totalReservacionesMensual += total - ventasServicios;
        });

        totalGeneralMensual = totalVentaServiciosMensual + totalReservacionesMensual;
        // Si quieres actualizar algún resumen mensual en pantalla, aquí lo puedes hacer:
    document.getElementById('totalVentasMensual').textContent = `MXN$${totalVentaServiciosMensual.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;
    document.getElementById('totalReservasMensual').textContent = `MXN$${totalReservacionesMensual.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;
    document.getElementById('totalGeneralMensual').textContent = `MXN$${totalGeneralMensual.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;

    }

    function llenarTablaServicios(ventas) {
        const tbody = document.querySelector('#tablaServicios tbody');
        tbody.innerHTML = '';
    
        ventas.forEach((item, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${item.Identificador}</td>
                    <td>${item.Tipo}</td>
                    <td>${item.Reserva ?? '-'}</td>
                    <td>${item.Habitacion ?? '-'}</td>
                    <td>${item.Articulo}</td>
                    <td>${item.Cantidad}</td>
                    <td>${item.Precio_Unitario}</td>
                    <td>${item.Total}</td>
                    <td>${item.Hora}</td>
                </tr>
            `;
            tbody.innerHTML += row;

            const total = parseFloat(item.Total || 0);

            if (item.Tipo.toLowerCase() === 'huésped' || item.Tipo.toLowerCase() === 'huesped') {
                totalVentaReservacionMensual += total;
            } else if (item.Tipo.toLowerCase() === 'público' || item.Tipo.toLowerCase() === 'publico') {
                totalVentaDirectaMensual += total;
            }
        });

        totalVentaGeneralMensual = totalVentaReservacionMensual + totalVentaDirectaMensual;

    //Igualmente podrías actualizar un resumen mensual aquí si quieres
    document.getElementById('totalVentasDirectaMensual').textContent = `MXN$${totalVentaDirectaMensual.toFixed(2)}`;
    document.getElementById('totalVentasReservacionMensual').textContent = `MXN$${totalVentaReservacionMensual.toFixed(2)}`;
    document.getElementById('totalVentasGeneralMensual').textContent = `MXN$${totalVentaGeneralMensual.toFixed(2)}`;
    }

    // Escuchar cambios
    document.addEventListener('DOMContentLoaded', initReporteMensual);
    document.getElementById('usuario').addEventListener('change', initReporteMensual);
    document.getElementById('mes').addEventListener('change', initReporteMensual);
    document.getElementById('anio').addEventListener('change', initReporteMensual);
}
