if(window.location.pathname === '/admin/configuracion/niveles'){
    //alert('cargado con exito el scrip de niveles');

    let dataTable;
    let dataTableInit = false;

    const estatusDictionary = {
        0: 'Inactivo',
        1: 'Activo'
    };

    // Configuración DataTable
    const dataTableOption = {
        destroy: true,
        pageLength: 5,
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-MX.json',
        },
        dom: '<"row mb-2"<"d-flex justify-content-start col-sm-6"f>>' +  
             '<"row"<"col-sm-12"tr>>' + 
             '<"row d-flex justify-content-between"<"col d-flex justify-content-start"l><"col d-flex justify-content-center"i><"col d-flex justify-content-end"p>>',
        columnDefs: [
            { orderable: false, targets: [4] }  // Desactiva ordenación en Dirección y Estatus
            //{ visible: false, targets: [7] }       // Oculta la columna Password
        ]
    };

    // Ejecutar funciones
    initDataTable();

    // Inicializamos DataTable
    async function initDataTable() {
        if (dataTableInit) {
            dataTable.destroy(); // Destruye la tabla si ya existe previamente
        }

        const niveles = await listarNiveles(); // Esperamos los datos antes de inicializar DataTable

        if (niveles.length > 0) {
            llenarTabla(niveles);
        }

        // Inicializa DataTable
        dataTable = $('#datatable_niveles').DataTable(dataTableOption);
        dataTableInit = true;
    }

    // Función para llenar la tabla con los datos obtenidos
    function llenarTabla(niveles) {
        const tbody = document.getElementById('tableBody_niveles');
        tbody.innerHTML = ''; // Limpiamos el contenido previo

        niveles.forEach((nivel, index) => {
            const estatus = estatusDictionary[nivel.estatus] || 'Desconocido';

            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${nivel.nombre}</td>
                    <td>${nivel.numero}</td>
                    <td class="text-center">${estatus}</td>
                    <td>
                        <!-- Botón de editar que abre el modal -->
                        <button 
                            class="btn btn-sm btn-primary btnEditarNivel" 
                            data-id="${nivel.id}" 
                            data-toggle="modal" 
                            data-target="#modalEditarNivel">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <!-- Botón de eliminar -->
                        <button class="btn btn-sm btn-danger btn-eliminarNivel" data-id="${nivel.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // Función para obtener los datos de la API
    async function listarNiveles() {
        try {
            const response = await fetch('/api/niveles');
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error al obtener niveles:', error);
            return [];
        }
    }
}