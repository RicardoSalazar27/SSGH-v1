if(window.location.pathname === '/admin/registro-actividades'){

    let dataTable;
    let dataTableInit = false;

    // Configuración de DataTable
    const dataTableOption = {
        destroy: true, // Destruir la tabla si ya existe
        pageLength: 10, // Número de filas por página
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-MX.json', // Idioma en español
        },
        dom: '<"row mb-2"<"d-flex justify-content-start col-sm-6"f>>' +  
            '<"row"<"col-sm-12"tr>>' + 
            '<"row d-flex justify-content-between"<"col d-flex justify-content-start"l><"col d-flex justify-content-center"i><"col d-flex justify-content-end"p>>', // Estructura del DOM
        columnDefs: [
            { orderable: false, targets: [4] }  // Desactiva ordenación en la columna de Acción
        ]
    };

    // Ejecutamos la función para inicializar la tabla
    initDataTable();

    // Inicializamos DataTable
    async function initDataTable() {
        if (dataTableInit) {
            dataTable.destroy(); // Destruir la tabla si ya existe previamente
        }

        const actividades = await obtenerActividades(); // Obtener los datos de la API
        if (actividades.length > 0) {
            llenarTabla(actividades); // Llenar la tabla con los datos obtenidos
        }

        // Inicializa DataTable con las opciones configuradas
        dataTable = $('#tabla-actividades').DataTable(dataTableOption);
        dataTableInit = true;
    }

    // Función para obtener los datos de la API
    async function obtenerActividades() {
        try {
            const response = await fetch('/api/registro-actividades');
            if (!response.ok) {
                throw new Error('Error al obtener actividades');
            }
            const actividades = await response.json();
            return actividades;
        } catch (error) {
            console.error('Error al obtener actividades:', error);
            return [];
        }
    }

    // Función para llenar la tabla con los datos obtenidos
    function llenarTabla(actividades) {
        const tbody = document.querySelector('#tabla-actividades tbody');
        tbody.innerHTML = ''; // Limpiar cualquier contenido previo

        actividades.forEach((actividad, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${actividad.nombre_usuario}</td>
                    <td>${actividad.telefono}</td>
                    <td><span class="badge badge-info">${actividad.accion}</span></td>
                    <td>${actividad.detalle}</td>
                    <td>${actividad.fecha}</td>
                    <td>${actividad.hora}</td>
                </tr>
            `;
            tbody.innerHTML += row; // Añadir la fila a la tabla
        });
    }
}