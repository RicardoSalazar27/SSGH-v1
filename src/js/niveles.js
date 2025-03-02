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

    // Función para obtener los datos de la API
    async function listarNiveles() {
        try {
            const response = await fetch('/api/niveles');
            const niveles = await response.json();
            return niveles;
        } catch (error) {
            console.error('Error al obtener niveles:', error);
            return [];
        }
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

    // Delegación de eventos para eliminación de usuarios
    document.getElementById('tableBody_niveles').addEventListener('click', async function (event) {
        if (event.target.closest('.btn-eliminarNivel')) {
            const nivelId = event.target.closest('.btn-eliminarNivel').getAttribute('data-id');
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
                    const url = `/api/niveles/${nivelId}`;
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
    
    // ---------------    LLENAR MODAL PARA ACTUALIZAR  -----------------
    document.addEventListener('click', async function (event) {
        if (event.target.closest('.btnEditarNivel')) {
            const boton = event.target.closest('.btnEditarNivel');
            const nivelId = boton.dataset.id;
            
            try {
                // Obtener los datos del usuario desde la API o una variable global
                const respuesta = await fetch(`/api/niveles/${nivelId}`);
                if (!respuesta.ok) {
                    throw new Error(`Error al obtener nivel: ${respuesta.statusText}`);
                }
    
                const nivel = await respuesta.json();
                // Llenar los campos del modal con los datos del nivel
                document.getElementById('nombreEditar').value = nivel.nombre;
                document.getElementById('numeroEditar').value = nivel.numero;
                document.getElementById('estatusEditar').value = nivel.estatus;
                
                // Guardar el ID en el botón de actualización
                document.querySelector('.btnActualizarNivel').dataset.id = nivelId;
                //window.nivelOriginal = { ...nivel }; // Guardar copia de los datos iniciales
                // Abrir el modal manualmente si es necesario
                //$('#modalEditarNivel').modal('show');
    
            } catch (error) {
                console.error('Error al obtener los datos del usuario:', error);
            }
        }
    });

    // ---------------    ACTUALIZAR NIVEL (PENDIENTE)     - ----------------
    // ---------------    ACTUALIZAR NIVEL -----------------
    document.getElementById('formEditarNivel').addEventListener('submit', async function(e) {
        e.preventDefault();
    
        const nivelId = document.querySelector('.btnActualizarNivel').dataset.id;
    
        const nivelActualizado = {
            nombre: document.getElementById('nombreEditar').value.trim(),
            numero: document.getElementById('numeroEditar').value.trim(),
            estatus: document.getElementById('estatusEditar').value.trim(),
        };
    
        try {
            const respuesta = await fetch(`/api/niveles/${nivelId}`);
            const nivelOriginal = await respuesta.json();
    
            let cambios = {};
    
            if (nivelActualizado.nombre !== nivelOriginal.nombre) cambios.nombre = nivelActualizado.nombre;
            if (nivelActualizado.numero !== nivelOriginal.numero) cambios.numero = nivelActualizado.numero;
            if (nivelActualizado.estatus !== nivelOriginal.estatus) cambios.estatus = nivelActualizado.estatus;
    
            const cambiosCount = Object.keys(cambios).length;
    
            if (cambiosCount === 0) {
                mostrarAlerta2('No hay cambios por enviar', 'error');
                return;
            }
    
            const metodo = cambiosCount === 3 ? "PUT" : "PATCH";
    
            //console.log({ metodo, datos: cambios });
            //console.log(cambios);

            try {
                const respuesta = await fetch(`/api/niveles/${nivelId}`, {
                    method: metodo,
                    headers: {
                        'Content-Type': 'application/json', // Aseguramos que el contenido sea JSON
                    },
                    body: JSON.stringify(cambios) // Convertimos el objeto `cambios` a JSON
                });
            
                const resultado = await respuesta.json();
                mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
                initDataTable();
            
            } catch (error) {
                console.error('Error al actualizar usuario:', error);
            }                        
    
        } catch (error) {
            console.log("Error obteniendo nivel:", error);
        }
    });    
    
    //  --------------    CREAR NUEVO NIVEL     ----------------
    const botonSubirNivel = document.querySelector('.btnSubirNivel');
    botonSubirNivel.addEventListener('click', async function (e) {
        e.preventDefault();

        const nivelNuevo = {
            nombre: document.getElementById('nombre').value.trim(),
            numero: document.getElementById('numero').value.trim(),
            estatus: document.getElementById('estatus').value.trim()
        };

        if (nivelNuevo.nombre === "" || nivelNuevo.numero === "") {
            mostrarAlerta2('Todos los campos son necesarios', 'error');
            return;
        }
        
        try {
            const datos = new FormData();
            Object.entries(nivelNuevo).forEach(([key, value]) => datos.append(key, value));
            const respuesta = await fetch('/api/niveles', {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
            initDataTable();

            // Limpiar los campos del formulario
            document.getElementById('nombre').value = '';
            document.getElementById('numero').value = '';
            document.getElementById('estatus').value = '';

        } catch (error) {
            console.error('Error en la solicitud:', error);
        }
    });
}

// try {
//     const datos = new FormData();
//     Object.entries(usuarioActualizado).forEach(([key, value]) => datos.append(key, value));
//     const respuesta = await fetch(`/api/usuarios/${userId}`, {
//         method: 'POST',
//         body: datos
//     });

//     const resultado = await respuesta.json();
//     mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
//     initDataTable();

// } catch (error) {
//     console.error('Error al actualizar usuario:', error);
// }