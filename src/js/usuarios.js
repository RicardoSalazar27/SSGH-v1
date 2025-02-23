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
                    <td><img src="/build/img/${user.img}.png" alt="Descripción de la imagen" width="65"></td>
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
                            class="btn btn-sm btn-primary btn-editarUsuario"
                            data-id="${user.id}" 
                            data-toggle="modal" 
                            data-target="#usuariosModal">
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
                    botonSubirUsuario.classList.replace('btnSubirUsuario','btnActualizarUsuario');
                }

                const usuarioId = e.target.closest('.btn-editarUsuario').getAttribute('data-id');
                cargarDatosUsuario(usuarioId); //Llama a la funcion de cargar los datos
            }
            if (e.target.classList.contains('btn-eliminarUsuario')) {
                const usuarioId = e.target.getAttribute('data-id');
                confirmarEliminacion(usuarioId);
            }            
        })

        async function confirmarEliminacion(id) {
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
                        const url = `/api/usuarios/${id}`; // 🔥 Aquí se inyecta el ID en la URL
        
                        const respuesta = await fetch(url, {
                            method: 'DELETE',  // 🔥 Método DELETE para eliminar
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

    async function cargarDatosUsuario(id) {
        try {
            const url = `/api/usuarios/${id}`;
            const resultado = await fetch(url);
    
            if (resultado.ok) {
                const usuario = await resultado.json();
                console.log(usuario);
                llenarModal(usuario);
    
                let btnActualizarUsuario = document.querySelector('.btnActualizarUsuario');
    
                if (btnActualizarUsuario) {
                    // Eliminar eventos previos correctamente
                    let nuevoBtn = btnActualizarUsuario.cloneNode(true);
                    btnActualizarUsuario.replaceWith(nuevoBtn);
                    btnActualizarUsuario = document.querySelector('.btnActualizarUsuario');
    
                    btnActualizarUsuario.addEventListener('click', async function (e) {
                        e.preventDefault();
    
                        // Obtener los valores actualizados
                        const usuarioactualizado = {
                            nombre: document.getElementById('nombre').value.trim(),
                            apellido: document.getElementById('apellido').value.trim(),
                            direccion: document.getElementById('direccion').value.trim(),
                            email: document.getElementById('email').value.trim(),
                            telefono: document.getElementById('telefono').value.trim(),
                            password: document.getElementById('password').value.trim(),
                            password2: document.getElementById('password2').value.trim(),
                            rol_id: document.getElementById('rol_id').value.trim(),
                            estatus: document.getElementById('estatus').value.trim(),
                            img: document.getElementById('logo').files[0] || usuario.img // Si no se carga imagen, mantener la existente
                        };
    
                        // **Validación de contraseñas**
                        if (usuarioactualizado.password || usuarioactualizado.password2) {
                            if (usuarioactualizado.password !== usuarioactualizado.password2) {
                                mostrarAlerta2("Las contraseñas no coinciden.", "error");
                                return; // Detiene la ejecución si las contraseñas no coinciden
                            }
                        }
    
                        // **Validación de email**
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(usuarioactualizado.email)) {
                            mostrarAlerta2("El correo electrónico no tiene un formato válido.", "error");
                            return; // Detiene la ejecución si el email no es válido
                        }
    
                        let cambios = {};
    
                        if (usuarioactualizado.nombre !== usuario.nombre) cambios.nombre = usuarioactualizado.nombre;
                        if (usuarioactualizado.apellido !== usuario.apellido) cambios.apellido = usuarioactualizado.apellido;
                        if (usuarioactualizado.direccion !== usuario.direccion) cambios.direccion = usuarioactualizado.direccion;
                        if (usuarioactualizado.email !== usuario.email) cambios.email = usuarioactualizado.email;
                        if (usuarioactualizado.estatus !== usuario.estatus) cambios.estatus = usuarioactualizado.estatus;
                        if (usuarioactualizado.telefono !== usuario.telefono) cambios.telefono = usuarioactualizado.telefono;
                        if (usuarioactualizado.password && usuarioactualizado.password2) {
                            cambios.password = usuarioactualizado.password;
                        }
                        if (usuarioactualizado.rol_id !== usuario.rol_id) cambios.rol_id = usuarioactualizado.rol_id;
                        
                        // Si se seleccionó una imagen nueva, incluirla
                        if (usuarioactualizado.img instanceof File) {
                            cambios.img = usuarioactualizado.img;
                        }

                        delete usuarioactualizado.password2;

                        //console.log(cambios);
    
                        if (Object.keys(cambios).length > 0) {
                            try {
                                const datos = new FormData();
                                Object.entries(usuarioactualizado).forEach(([key, value]) => datos.append(key, value));
                                const url = `/api/usuarios/${id}`; 
                                const respuesta = await fetch(url, { // Corregido: Usar un objeto en lugar de un array
                                    method: 'POST',
                                    body: datos
                                });
                                
                                // Esperar la respuesta en formato JSON
                                const resultado = await respuesta.json();
                                mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
                            } catch (error) {
                                console.log(error);
                            }
                            
                        } else {
                            mostrarAlerta('Sin Cambios', 'No hay cambios por mostrar', 'warning')
                        }    
                        // Aquí iría tu función de envío de datos al servidor
                    });
                }
            }
    
            function llenarModal(usuario) {
                document.getElementById('nombre').value = usuario.nombre;
                document.getElementById('apellido').value = usuario.apellido;
                document.getElementById('direccion').value = usuario.direccion;
                document.getElementById('email').value = usuario.email;
                document.getElementById('telefono').value = usuario.telefono;
                document.getElementById('rol_id').value = usuario.rol_id;
                document.getElementById('estatus').value = usuario.estatus;
    
                const imgElement = document.getElementById('img');
                imgElement.src = `/build/img/${usuario.img}.png`;
            }
    
        } catch (error) {
            console.error(error);
        }
    }

    function mostrarAlerta(titulo, mensaje, tipo) {
        Swal.fire({
            icon: tipo,
            title: titulo,
            text: mensaje,
        }).then(() => {
            $('.modal').modal('hide'); // Cierra todos los modales activos
        });
    }
    
    function mostrarAlerta2(mensaje, tipo) {
        const mensajeResultado = document.getElementById('mensaje-resultado');
        mensajeResultado.style.display = 'block'; // Asegúrate de que el contenedor se muestre
        mensajeResultado.textContent = mensaje; // Mostrar solo el mensaje
    
        // Cambiar el color de fondo del contenedor según el tipo de mensaje
        if (tipo === 'error') {
            mensajeResultado.className = 'alert alert-danger'; // Rojo para error
        } else if (tipo === 'success') {
            mensajeResultado.className = 'alert alert-success'; // Verde para éxito
        } else {
            mensajeResultado.className = 'alert alert-info'; // Azul o información por defecto
        }
    
        // Opcional: Ocultar el mensaje después de 5 segundos
        setTimeout(() => {
            mensajeResultado.style.display = 'none';
        }, 5000);
    }
}
