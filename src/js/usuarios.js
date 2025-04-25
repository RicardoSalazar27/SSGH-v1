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
            { visible: false, targets: [7] }       // Oculta la columna Password
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
                    <td><img src="/build/img/${user.img ? user.img : 'user'}.png" alt="Imagen-usuario" width="65"></td>
                    <td>${user.nombre}</td>
                    <td>${user.apellido}</td>
                    <td>${user.direccion}</td>
                    <td>${user.email}</td>
                    <td>${user.telefono}</td>
                    <td>******</td> <!-- Ocultamos la contraseña en opciones de la tabla -->
                    <td>${rol}</td>
                    <td class="text-center">${estatus}</td>
                    <td>
                        <!-- Botón de editar que abre el modal -->
                        <button 
                            class="btn btn-sm btn-primary btnEditarUsuario" 
                            data-id="${user.id}" 
                            data-toggle="modal" 
                            data-target="#usuarioEditarModal">
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
    }

    // Delegación de eventos para eliminación de usuarios
    document.getElementById('tableBody_users').addEventListener('click', async function (event) {
        if (event.target.closest('.btn-eliminarUsuario')) {
            const usuarioId = event.target.closest('.btn-eliminarUsuario').getAttribute('data-id');
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
                    const url = `/api/usuarios/${usuarioId}`;
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
        if (event.target.closest('.btnEditarUsuario')) {
            const boton = event.target.closest('.btnEditarUsuario');
            const userId = boton.dataset.id;
            
            try {
                // Obtener los datos del usuario desde la API o una variable global
                const respuesta = await fetch(`/api/usuarios/${userId}`);
                if (!respuesta.ok) {
                    throw new Error(`Error al obtener usuario: ${respuesta.statusText}`);
                }
    
                const usuario = await respuesta.json();
                // Llenar los campos del modal con los datos del usuario
                document.getElementById('nombreEditar').value = usuario.nombre;
                document.getElementById('apellidoEditar').value = usuario.apellido;
                document.getElementById('direccionEditar').value = usuario.direccion;
                document.getElementById('emailEditar').value = usuario.email;
                document.getElementById('telefonoEditar').value = usuario.telefono;
                document.getElementById('rol_idEditar').value = usuario.rol_id;
                document.getElementById('estatusEditar').value = usuario.estatus;
    
                // Mostrar la imagen si existe
                const imgElement = document.getElementById('imgEditar');
                imgElement.src = usuario.img ? `/build/img/${usuario.img}.png` : '/build/img/user.png';                    
    
                // Guardar el ID en el botón de actualización
                document.querySelector('.btnActualizarUsuario').dataset.id = userId;
    
                // Abrir el modal manualmente si es necesario
                $('#usuarioEditarModal').modal('show');
    
            } catch (error) {
                console.error('Error al obtener los datos del usuario:', error);
            }
        }
    });
    
    // ---------------    ACTUALIZAR USUARIO     - ----------------
    document.getElementById('formEditarUsuario').addEventListener('submit', async function (e) {
        e.preventDefault();
    
        const userId = document.querySelector('.btnActualizarUsuario').dataset.id;
    
        const usuarioActualizado = {
            nombre: document.getElementById('nombreEditar').value.trim(),
            apellido: document.getElementById('apellidoEditar').value.trim(),
            direccion: document.getElementById('direccionEditar').value.trim(),
            email: document.getElementById('emailEditar').value.trim(),
            telefono: document.getElementById('telefonoEditar').value.trim(),
            password: document.getElementById('passwordEditar').value.trim(),
            password2: document.getElementById('password2Editar').value.trim(),
            rol_id: document.getElementById('rol_idEditar').value.trim(),
            estatus: document.getElementById('estatusEditar').value.trim(),
            img: document.getElementById('logoEditar').files[0]
        };
    
        if (usuarioActualizado.password !== usuarioActualizado.password2) {
            mostrarAlerta('Error', 'Las contraseñas no coinciden.', 'error');
            return;
        }

        if (usuarioActualizado.telefono.length > 10) {
            mostrarAlerta('Error', 'Telefono no valido', 'error');
            return;
        }
    
        delete usuarioActualizado.password2; // No enviar password2 al backend
    
        try {
            const datos = new FormData();
            Object.entries(usuarioActualizado).forEach(([key, value]) => datos.append(key, value));
            const respuesta = await fetch(`/api/usuarios/${userId}`, {
                method: 'POST',
                body: datos
            });
    
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
            initDataTable();
    
        } catch (error) {
            console.error('Error al actualizar usuario:', error);
        }
    });

    //  --------------    CREAR NUEVO USUARIO     ----------------
    const botonSubirUsuario = document.querySelector('.btnSubirUsuario');
    botonSubirUsuario.addEventListener('click', async function (e) {
        e.preventDefault();

        const usuarioNuevo = {
            nombre: document.getElementById('nombre').value.trim(),
            apellido: document.getElementById('apellido').value.trim(),
            direccion: document.getElementById('direccion').value.trim(),
            email: document.getElementById('email').value.trim(),
            telefono: document.getElementById('telefono').value.trim(),
            password: document.getElementById('password').value.trim(),
            password2: document.getElementById('password2').value.trim(),
            rol_id: document.getElementById('rol_id').value.trim(),
            estatus: document.getElementById('estatus').value.trim(),
            img: document.getElementById('logo').files[0]
        };

        if (usuarioNuevo.email === "" || usuarioNuevo.nombre === "" || usuarioNuevo.direccion === "" || usuarioNuevo.password === "" || usuarioNuevo.password2 === "" || usuarioNuevo.telefono === "") {
            mostrarAlerta2('Todos los campos son necesarios', 'error');
            return;
        }

        if (usuarioNuevo.password !== usuarioNuevo.password2) {
            mostrarAlerta2("Las contraseñas no coinciden.", "error");
            return;
        }

        if (usuarioNuevo.telefono.length > 10) {
            mostrarAlerta2('Telefono no valido', 'error');
            return;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(usuarioNuevo.email)) {
            mostrarAlerta2("El correo electrónico no tiene un formato válido.", "error");
            return;
        }

        delete usuarioNuevo.password2;

        try {
            const datos = new FormData();
            Object.entries(usuarioNuevo).forEach(([key, value]) => datos.append(key, value));
            const respuesta = await fetch('/api/usuarios', {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
            initDataTable();

        } catch (error) {
            console.error('Error en la solicitud:', error);
        }
    });
    
}