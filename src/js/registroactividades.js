
if (window.location.pathname === '/admin/registro-actividades') {
    const tabla = document.getElementById('tabla-actividades') // Selección de la tabla
    const url = '/api/registro-actividades'; // Endpoint

    const cargarActividades = async () => {
        try {
            const response = await fetch(url);
            
            if (!response.ok) {
                if (response.status === 204) {
                    alert('No hay registros de actividades.');
                    return;
                }
                throw new Error('Error al obtener datos');
            }

            const data = await response.json();

            // Inicializa DataTables
            $(tabla).DataTable({
                data: data,
                columns: [
                    { data: null }, // Índice #
                    { data: 'nombre_usuario' },
                    { data: 'telefono' },
                    { 
                        data: 'accion',
                        render: data => `<span class="badge badge-info">${data}</span>`
                    },
                    { data: 'detalle' },
                    { data: 'fecha' },
                    { data: 'hora' }
                ],
                columnDefs: [{
                    targets: 0,
                    render: (data, type, row, meta) => meta.row + 1
                }],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        } catch (error) {
            console.error('Error:', error);
        }
    };

    cargarActividades();
}