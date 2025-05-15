if (window.location.pathname === '/admin/recibos') {
    $('#datatable_recibos').DataTable({
        pageLength: 10,
        lengthChange: true,
        order: [[0, 'desc']], // <-- Esto ordena por la primera columna (ID_reserva) en descendente
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-MX.json'
        }
    });
}
