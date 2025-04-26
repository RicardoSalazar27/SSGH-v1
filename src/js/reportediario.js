if (window.location.pathname === '/admin/reporte-diario') {

    let dataTableReservas;
    let dataTableServicios;
    let dataTableReservasInit = false;
    let dataTableServiciosInit = false;

    let nombreHotel;
    let telefonoHotel;
    let correoHotel;
    let ubicacionHotel;

    const urlHotel = '/api/hotel';  // URL de la API para obtener los datos del hotel

    async function obtenerNombreHotel() {
        try {
            const response = await fetch(urlHotel);
            const data = await response.json();
            nombreHotel = data.nombre || 'Tu Nombre Hotel'; // Actualizamos el nombre del hotel
            telefonoHotel = data.telefono || 'Tu Telefono';
            correoHotel = data.correo || 'Tu Correo';
            ubicacionHotel = data.ubicacion || 'Tu Ubicacion';
        } catch (error) {
            console.error('Error al obtener el nombre del hotel:', error);
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

    let totalVentaServicios = 0;
    let totalReservaciones = 0;
    let totalGeneral = 0;

    let totalVentaDirecta = 0;
    let totalVentaReservacion = 0;
    let totalVentaGeneral = 0;

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

    async function initReportesDiarios() {
        await obtenerNombreHotel(); // <--- ESPERAR a que obtenga el nombre antes de continuar
    
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
        const fecha = document.getElementById('fecha').value;
        const url = `/api/reporte-diario/${usuarioId}/${fecha}`;
    
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
            console.error('Error al obtener reporte diario:', error);
        }
    }
    

    function llenarTablaReservas(reservas) {
        const tbody = document.querySelector('#tablaReservas tbody');
        tbody.innerHTML = '';
    
        // Reiniciar totales antes de acumular
        totalVentaServicios = 0;
        totalReservaciones = 0;
        totalGeneral = 0;
    
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
        
            totalVentaServicios += ventasServicios;
            totalReservaciones += total - ventasServicios;
        });
        
        totalGeneral = totalVentaServicios + totalReservaciones;
            
        // Llenar <p> del resumen
        document.getElementById('totalVentas').textContent = `MXN$${totalVentaServicios.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;
        document.getElementById('totalReservas').textContent = `MXN$${totalReservaciones.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;
        document.getElementById('totalGeneral').textContent = `MXN$${totalGeneral.toLocaleString('es-MX', { minimumFractionDigits: 2 })}`;
    }    
    
    function llenarTablaServicios(ventas) {
        const tbody = document.querySelector('#tablaServicios tbody');
        tbody.innerHTML = '';
    
        // Reiniciamos totales por si se vuelve a llenar la tabla
        totalVentaDirecta = 0;
        totalVentaReservacion = 0;
        totalVentaGeneral = 0;
    
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
                    <td>MXN$${item.Precio_Unitario}</td>
                    <td>MXN$${item.Total}</td>
                    <td>${item.Hora}</td>
                </tr>
            `;
            tbody.innerHTML += row;
    
            const total = parseFloat(item.Total || 0);
    
            if (item.Tipo.toLowerCase() === 'huésped' || item.Tipo.toLowerCase() === 'huesped') {
                totalVentaReservacion += total;
            } else if (item.Tipo.toLowerCase() === 'público' || item.Tipo.toLowerCase() === 'publico') {
                totalVentaDirecta += total;
            }
        });
    
        totalVentaGeneral = totalVentaReservacion + totalVentaDirecta;

        document.getElementById('totalVentasDirecta').textContent = `MXN$${totalVentaDirecta.toFixed(2)}`;
        document.getElementById('totalVentasReservacion').textContent = `MXN$${totalVentaReservacion.toFixed(2)}`;
        document.getElementById('totalVentasGeneral').textContent = `MXN$${totalVentaGeneral.toFixed(2)}`;

    }        

    // 🔄 Ejecutar al cargar la página o cuando cambien filtros
    document.addEventListener('DOMContentLoaded', initReportesDiarios);
    document.getElementById('usuario').addEventListener('change', initReportesDiarios);
    document.getElementById('fecha').addEventListener('change', initReportesDiarios);
}
