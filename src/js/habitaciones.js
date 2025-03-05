if(window.location.pathname === '/admin/configuracion/habitaciones'){

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

        const habitaciones = await listarhabitaciones(); // Esperamos los datos antes de inicializar DataTable

        if (habitaciones.length > 0) {
            llenarTabla(habitaciones);
        }

        // Inicializa DataTable
        dataTable = $('#datatable_habitaciones').DataTable(dataTableOption);
        dataTableInit = true;
    }

    // Función para obtener los datos de la API
    async function listarhabitaciones() {
        try {
            const response = await fetch('/api/habitaciones');
            const habitaciones = await response.json();
            return habitaciones;
        } catch (error) {
            console.error('Error al obtener habitaciones:', error);
            return [];
        }
    }

    // Función para llenar la tabla con los datos obtenidos
    function llenarTabla(habitaciones) {
        const tbody = document.getElementById('tableBody_habitaciones');
        tbody.innerHTML = ''; // Limpiamos el contenido previo

        habitaciones.forEach((habitacion) => {
            const estatus = estatusDictionary[habitacion.estatus] || 'Desconocido';

            const row = `
                <tr>
                    <td>${habitacion.numero}</td>
                    <td>${habitacion.id_nivel.nombre}</td>
                    <td>${habitacion.id_categoria.nombre}</td>
                    <td>${habitacion.id_categoria.precio_base}</td>
                    <td>${habitacion.id_categoria.servicios_incluidos}</td>
                    <td>${habitacion.detalles_personalizados}</td>
                    <td class="text-center">${estatus}</td>
                    <td>
                        <!-- Botón de editar que abre el modal -->
                        <button 
                            class="btn btn-sm btn-primary btnEditarHabitacion" 
                            data-id="${habitacion.id}" 
                            data-toggle="modal" 
                            data-target="#modalEditarHabitacion">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <!-- Botón de eliminar -->
                        <button class="btn btn-sm btn-danger btn-eliminarHabitacion" data-id="${habitacion.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    //eliminar


    // Variable global para almacenar el nivel original
    let habitacionOriginal = null;

    // --------------- LLENAR MODAL PARA ACTUALIZAR -----------------
    document.addEventListener('click', async function (event) {
        if (event.target.closest('.btnEditarHabitacion')) {
            const boton = event.target.closest('.btnEditarHabitacion');
            const habitacionId = boton.dataset.id;
            habitacionOriginal = '';
    
            try {
                const respuesta = await fetch(`/api/habitaciones/${habitacionId}`);
                if (!respuesta.ok) {
                    throw new Error(`Error al obtener habitación: ${respuesta.statusText}`);
                }
                const habitacion = await respuesta.json();
                console.log(habitacion);
    
                habitacionOriginal = { ...habitacion }; // Guardar en variable global
    
                // Llenar los campos del modal con los datos de la habitación
                document.getElementById('numeroEditar').value = habitacion.numero;
                document.getElementById('id_nivelEditar').value = habitacion.id_nivel.id; // Aquí se asigna el ID del nivel
                document.getElementById('id_categoriaEditar').value = habitacion.id_categoria.id; // Aquí el ID de la categoría
                document.getElementById('detalles_personalizadosEditar').value = habitacion.detalles_personalizados;
                document.getElementById('estatusEditar').value = habitacion.estatus;

                // Guardar el ID en el botón de actualización
                document.querySelector('.btnActualizarHabitacion').dataset.id = nivelId;
    
            } catch (error) {
                console.error('Error al obtener los datos de la habitación:', error);
            }
        }
    });
    
}
