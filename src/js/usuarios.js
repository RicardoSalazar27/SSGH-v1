if (window.location.pathname === '/admin/usuarios') {
    let dataTable;
    let dataTableInit = false;

    // Diccionarios para roles y estatus
    const rolesDictionary = {
        1: 'Administrador',
        2: 'General',
        3: 'Limpieza'
    };

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
            { orderable: false, targets: [3, 8] },  // Desactiva ordenación en Dirección y Estatus
            { visible: false, targets: [5] }       // Oculta la columna Password
        ]
    };

    // Ejecutar funciones
    initDataTable();

    // Inicializamos DataTable
    async function initDataTable() {
        if (dataTableInit) {
            dataTable.destroy(); // Destruye la tabla si ya existe previamente
        }

        const usuarios = await listarUsers(); // Esperamos los datos antes de inicializar DataTable

        if (usuarios.length > 0) {
            llenarTabla(usuarios);
        }

        // Inicializa DataTable
        dataTable = $('#datatable_users').DataTable(dataTableOption);
        dataTableInit = true;
    }

    // Función para obtener los datos de la API
    async function listarUsers() {
        try {
            const response = await fetch('/api/usuarios');
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Error al obtener usuarios:', error);
            return [];
        }
    }

    // Función para llenar la tabla con los datos obtenidos
    function llenarTabla(usuarios) {
        const tbody = document.getElementById('tableBody_users');
        tbody.innerHTML = ''; // Limpiamos el contenido previo

        usuarios.forEach((user, index) => {
            const rol = rolesDictionary[user.rol_id] || 'Desconocido';
            const estatus = estatusDictionary[user.estatus] || 'Desconocido';

            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${user.nombre}</td>
                    <td>${user.apellido}</td>
                    <td>${user.direccion}</td>
                    <td>${user.email}</td>
                    <td>******</td> <!-- Ocultamos la contraseña -->
                    <td>${rol}</td>
                    <td class="text-center">${estatus}</td>
                    <td>
                        <!-- Botón de editar que abre el modal -->
                        <button 
                            class="btn btn-sm btn-primary btn-editarUsuario"
                            data-id="${user.id}" 
                            data-toggle="modal" 
                            data-bs-target="#UsuarioModal">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <!-- Botón de eliminar -->
                        <button class="btn btn-sm btn-danger btn-eliminarUsuario" data-id="${user.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });

        tbody.addEventListener('click', function(e){
            //Delegacion para actualizar el cliente
            if( e.target.closest('.btn-editarUsuario') ){
                // Cambiar la clase del botón para que sea de 'Actualizar' y no de 'Crear'
                const botonSubirUsuario = document.querySelector('.btnSubirUsuario');
                if ( botonSubirUsuario ){
                    botonSubirUsuario.classList.replace('btnSubirUsuario','btnActualizarUusuario')
                }

                const usuarioId = e.target.closest('.btn-editarUsuario').getAtribute('data-id');
                cargarDatosUsuario(usuarioId); //Llama a la funcion de cargar los datos
            }
            if( e.target.classList.contains('btn-eliminarUsuario') ){
                const usuarioId = e.target.getAttribute('data-id');
                confirmarEliminacion(usuarioId);
            }
        })

        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const datos = new FormData();
                        datos.append('id', id);

                        const url = `http://localhost:3000/eliminar`;
                        const respuesta = await fetch(url, {
                            method: 'POST',
                            body: datos
                        });

                        const resultado = await respuesta.json();
                        mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);

                        if (resultado.tipo === 'success') {
                            await initDataTable(); // Recarga la tabla de clientes
                        }
                    } catch (error) {
                        console.error(error);
                    }
                }
            });
        }
    }
}
