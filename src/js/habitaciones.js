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

    // Delegación de eventos para eliminación de niveles
    document.getElementById('tableBody_habitaciones').addEventListener('click', async function (event) {
        if (event.target.closest('.btn-eliminarHabitacion')) {
            const habitacionId = event.target.closest('.btn-eliminarHabitacion').getAttribute('data-id');
            const result = await Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            });

            if (result.isConfirmed) {
                try {
                    const url = `/api/habitaciones/${habitacionId}`;
                    const respuesta = await fetch(url, {
                        method: 'DELETE',
                    });

                    const resultado = await respuesta.json();
                    mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);

                    if (resultado.tipo === 'success') {
                        await initDataTable();
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }
    });


    // Variable global para almacenar el habitacion original
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
                document.getElementById('id_nivelEditar').value = habitacion.id_nivel.id; // Aquí se asigna el ID del habitacion
                document.getElementById('id_categoriaEditar').value = habitacion.id_categoria.id; // Aquí el ID de la categoría
                document.getElementById('detalles_personalizadosEditar').value = habitacion.detalles_personalizados;
                document.getElementById('estatusEditar').value = habitacion.estatus;

                // Guardar el ID en el botón de actualización
                document.querySelector('.btnActualizarHabitacion').dataset.id = habitacionId;
    
            } catch (error) {
                console.error('Error al obtener los datos de la habitación:', error);
            }
        }
    });
    
    // --------------- ACTUALIZAR HABITACION -----------------
    document.getElementById('formEditarHabitacion').addEventListener('submit', async function (e) {
        e.preventDefault();

        const habitacionId = document.querySelector('.btnActualizarHabitacion').dataset.id;

        const habitacionActualizada = {
            numero: document.getElementById('numeroEditar').value.trim(),
            id_nivel: document.getElementById('id_nivelEditar').value.trim(),
            id_categoria: document.getElementById('id_categoriaEditar').value.trim(),
            detalles_personalizados: document.getElementById('detalles_personalizadosEditar').value.trim(),
            estatus: document.getElementById('estatusEditar').value.trim()
        };

        // console.log(habitacionActualizada);
        // return;

        if (!habitacionOriginal) {
            console.error('Error: No hay datos originales del habitacion');
            mostrarAlerta('Error', 'No se pudieron comparar los datos originales', 'error');
            return;
        }

        // Comparar con los datos originales sin hacer otra petición
        let cambios = {};
        if (habitacionActualizada.numero !== habitacionOriginal.numero) cambios.numero = habitacionActualizada.numero;
        if (habitacionActualizada.id_nivel !== habitacionOriginal.id_nivel) cambios.id_nivel = habitacionActualizada.id_nivel;
        if (habitacionActualizada.id_categoria !== habitacionOriginal.id_categoria) cambios.id_categoria = habitacionActualizada.id_categoria;
        if (habitacionActualizada.detalles_personalizados !== habitacionOriginal.detalles_personalizados) cambios.detalles_personalizados = habitacionActualizada.detalles_personalizados;
        if (habitacionActualizada.estatus !== habitacionOriginal.estatus) cambios.estatus = habitacionActualizada.estatus;

        // Si no hay cambios, no enviamos la petición
        if (Object.keys(cambios).length === 0) {
            mostrarAlerta2('No hay cambios por enviar', 'error');
            return;
        }
        const datos = habitacionActualizada;

        try {
            // Enviar la actualización con una sola petición
            const respuestaUpdate = await fetch(`/api/habitaciones/${habitacionId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datos)
            });

            if (!respuestaUpdate.ok) {
                const errorData = await respuestaUpdate.json();
                throw new Error(errorData.mensaje || 'Error desconocido al actualizar');
            }

            const resultado = await respuestaUpdate.json();
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
            initDataTable();

        } catch (error) {
            console.error('Error al actualizar habitacion:', error);
            mostrarAlerta('Error', error.message, 'error');
        }
    });

    //  --------------     CREAR NUEVa HABITACION     ----------------
    const botonSubirHabitacion = document.querySelector('.btnSubirHabitacion');
    botonSubirHabitacion.addEventListener('click', async function (e) {
        e.preventDefault();

        const habitacionNueva = {

            numero: document.getElementById('numero').value.trim(),
            id_nivel: document.getElementById('id_nivel').value.trim(),
            id_categoria: document.getElementById('id_categoria').value.trim(),
            detalles_personalizados: document.getElementById('detalles_personalizados').value.trim(),
            estatus: document.getElementById('estatus').value.trim()
        
        };

        if (habitacionNueva.id_nivel === "" || habitacionNueva.numero === "" || habitacionNueva.id_categoria === "") {
            mostrarAlerta2('Todos los campos son necesarios', 'error');
            return;
        }
        
        try {
            const datos = new FormData();
            Object.entries(habitacionNueva).forEach(([key, value]) => datos.append(key, value));
            const respuesta = await fetch('/api/habitaciones', {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
            initDataTable();

            // Limpiar los campos del formulario después de crear una habitación
            document.getElementById('numero').value = '';
            document.getElementById('detalles_personalizados').value = '';
            document.getElementById('estatus').value = '1';

        } catch (error) {
            console.error('Error en la solicitud:', error);
        }
    });

}
