if (window.location.pathname === '/admin/reporte-diario') {
    const opcionesDataTable = {
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
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                orientation: 'portrait', // vertical
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

    $('#tablaReservas').DataTable(opcionesDataTable);
    $('#tablaServicios').DataTable(opcionesDataTable);
}
