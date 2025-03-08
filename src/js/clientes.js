if(window.location.pathname === '/admin/clientes'){
    
    let dataTable;
    let dataTableInit = false;

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
            { orderable: false, targets: [6,7] }  // Desactiva ordenación en Dirección y Estatus
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

        const clientes = await listarClientes(); // Esperamos los datos antes de inicializar DataTable

        if (clientes.length > 0) {
            llenarTabla(clientes);
        }

        // Inicializa DataTable
        dataTable = $('#datatable_clientes').DataTable(dataTableOption);
        dataTableInit = true;
    }

    // Función para obtener los datos de la API
    async function listarClientes() {
        try {
            const response = await fetch('/api/clientes');
    
            if (response.status === 204) {
                return []; // No hay contenido, devolvemos un array vacío
            }
    
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
    
            return await response.json();
        } catch (error) {
            console.error('Error al obtener clientes:', error);
            return null;
        }
    }

    // Función para llenar la tabla con los datos obtenidos
    function llenarTabla(clientes) {
        const tbody = document.getElementById('tableBody_clientes');
        tbody.innerHTML = ''; // Limpiamos el contenido previo

        clientes.forEach((cliente, index) => {
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${cliente.nombre}</td>
                    <td>${cliente.apellidos}</td>
                    <td>${cliente.telefono}</td>
                    <td>${cliente.correo}</td>
                    <td>${cliente.direccion}</td>
                    <td>${cliente.documento_identidad}</td>
                    <td>
                        <!-- Botón de editar que abre el modal -->
                        <button 
                            class="btn btn-sm btn-primary btnEditarCliente" 
                            data-id="${cliente.id}" 
                            data-toggle="modal" 
                            data-target="#modalEditarCliente">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <!-- Botón de eliminar -->
                        <button class="btn btn-sm btn-danger btn-eliminarCliente" data-id="${cliente.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // Variable global para almacenar el nivel original
    let clienteOriginal = null;

    // --------------- LLENAR MODAL PARA ACTUALIZAR -----------------
    document.addEventListener('click', async function (event) {
        if (event.target.closest('.btnEditarCliente')) {
            const boton = event.target.closest('.btnEditarCliente');
            const clientesId = boton.dataset.id;
            clienteOriginal= '';
            try {
                // Obtener los datos del nivel desde la API
                const respuesta = await fetch(`/api/clientes/${clientesId}`);
                if (!respuesta.ok) {
                    throw new Error(`Error al obtener cliente: ${respuesta.statusText}`);
                }
                const cliente = await respuesta.json();
                clienteOriginal = { ...cliente }; // Guardar en variable global

                // Llenar los campos del modal con los datos del cliente
                document.getElementById('nombre').value = cliente.nombre;
                document.getElementById('apellidos').value = cliente.apellidos;
                document.getElementById('direccion').value = cliente.direccion;
                document.getElementById('correo').value = cliente.correo;
                document.getElementById('telefono').value = cliente.telefono;
                document.getElementById('documento_identidad').value = cliente.documento_identidad;

                // Guardar el ID en el botón de actualización
                document.querySelector('.btnActualizarCliente').dataset.id = clientesId;

            } catch (error) {
                console.error('Error al obtener los datos del cliente:', error);
            }
        }
    });

    // --------------- ACTUALIZAR CLIENTE -----------------
    document.getElementById('formEditarCliente').addEventListener('submit', async function (e) {
        e.preventDefault();
    
        const clienteId = document.querySelector('.btnActualizarCliente').dataset.id;

        const clienteActualizado = {
            nombre: document.getElementById('nombre').value.trim(),
            apellidos: document.getElementById('apellidos').value.trim(),
            telefono: document.getElementById('telefono').value.trim(),
            correo: document.getElementById('correo').value.trim(),
            direccion: document.getElementById('direccion').value.trim(),
            documento_identidad: document.getElementById('documento_identidad').value.trim()
        };

        if (!clienteOriginal) {
            console.error('Error: No hay datos originales del cliente');
            mostrarAlerta('Error', 'No se pudieron comparar los datos originales', 'error');
            return;
        }

        // Comparar con los datos originales sin hacer otra petición
        let cambios = {};
        if (clienteActualizado.nombre !== clienteOriginal.nombre) cambios.nombre = clienteActualizado.nombre;
        if (clienteActualizado.apellidos !== clienteOriginal.apellidos) cambios.apellidos = clienteActualizado.apellidos;
        if (clienteActualizado.telefono !== clienteOriginal.telefono) cambios.telefono = clienteActualizado.telefono;
        if (clienteActualizado.correo !== clienteOriginal.correo) cambios.correo = clienteActualizado.correo;
        if (clienteActualizado.direccion !== clienteOriginal.direccion) cambios.direccion = clienteActualizado.direccion;
        if (clienteActualizado.documento_identidad !== clienteOriginal.documento_identidad) cambios.documento_identidad = clienteActualizado.documento_identidad;

        // Si no hay cambios, no enviamos la petición
        if (Object.keys(cambios).length === 0) {
            mostrarAlerta2('No hay cambios por enviar', 'error');
            return;
        }

        // Determinar si usar PUT o PATCH
        const metodo = Object.keys(cambios).length === 6 ? "PUT" : "PATCH";
        const datos = metodo === "PUT" ? clienteActualizado : cambios;

        try {
            // Enviar la actualización con una sola petición
            const respuestaUpdate = await fetch(`/api/clientes/${clienteId}`, {
                method: metodo,
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
            console.error('Error al actualizar cliente:', error);
            mostrarAlerta('Error', error.message, 'error');
        }
    });
}