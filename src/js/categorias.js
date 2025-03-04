if(window.location.pathname === '/admin/configuracion/categorias'){
    
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
            { orderable: false, targets: [7] }  // Desactiva ordenación en Dirección y Estatus
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

        const categorias = await listarCategorias(); // Esperamos los datos antes de inicializar DataTable

        if (categorias.length > 0) {
            llenarTabla(categorias);
        }

        // Inicializa DataTable
        dataTable = $('#datatable_categorias').DataTable(dataTableOption);
        dataTableInit = true;
    }

    // Función para obtener los datos de la API
    async function listarCategorias() {
        try {
            const response = await fetch('/api/categorias');
    
            if (response.status === 204) {
                return []; // No hay contenido, devolvemos un array vacío
            }
    
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
    
            return await response.json();
        } catch (error) {
            console.error('Error al obtener categorías:', error);
            return null;
        }
    }
    
    // Delegación de eventos para eliminación de categorias
    document.getElementById('tableBody_categorias').addEventListener('click', async function (event) {
        if (event.target.closest('.btn-eliminarCategoria')) {
            const categoriaId = event.target.closest('.btn-eliminarCategoria').getAttribute('data-id');
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
                    const url = `/api/categorias/${categoriaId}`;
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

    // Función para llenar la tabla con los datos obtenidos
    function llenarTabla(categorias) {
        const tbody = document.getElementById('tableBody_categorias');
        tbody.innerHTML = ''; // Limpiamos el contenido previo

        categorias.forEach((categoria, index) => {
            const estatus = estatusDictionary[categoria.estado] || 'Desconocido';

            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${categoria.nombre}</td>
                    <td class="text-center">${categoria.capacidad_maxima}</td>
                    <td>${categoria.tipo_cama}</td>
                    <td>${categoria.precio_base}</td>
                    <td>${categoria.servicios_incluidos}</td>
                    <td>${estatus}</td>
                    <td>
                        <!-- Botón de editar que abre el modal -->
                        <button 
                            class="btn btn-sm btn-primary btnEditarCategoria" 
                            data-id="${categoria.id}" 
                            data-toggle="modal" 
                            data-target="#modalEditarCategoria">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <!-- Botón de eliminar -->
                        <button class="btn btn-sm btn-danger btn-eliminarCategoria" data-id="${categoria.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // Variable global para almacenar la categoria original
    let categoriaOriginal = null;

    // ---------------------------    LLENAR MODAL PARA ACTUALIZAR    ------------------------------
    document.addEventListener('click', async function (e) {
        if(e.target.closest('.btnEditarCategoria')) { //boton editar que abre modal
            const boton = e.target.closest('.btnEditarCategoria');
            const categoriaId = boton.dataset.id;
            categoriaOriginal = null;

            try {
                
                // Obtener datos de la categoria desde la API
                const url = `/api/categorias/${categoriaId}`;
                const respuesta = await fetch(url);
                if(!respuesta.ok){
                    throw new Error(`Error al obtener categoria: ${respuesta.statusText}`);
                }
                const categoria = await respuesta.json();
                categoriaOriginal = { ...categoria }; // Guarda en la variable global

                // Llenar campos del modal con los datos de la categoria
                document.getElementById('nombreEditar').value = categoria.nombre;
                document.getElementById('capacidad_maximaEditar').value = categoria.capacidad_maxima;
                document.getElementById('tipo_camaEditar').value = categoria.tipo_cama;
                document.getElementById('precio_baseEditar').value = categoria.precio_base;
                document.getElementById('servicios_incluidosEditar').value = categoria.servicios_incluidos;
                document.getElementById('estadoEditar').value = categoria.estado;

                // Guardar el ID de la categoria en el botón de actualización
                document.querySelector('.btnActualizarCategoria').dataset.id = categoriaId;

            } catch (error) {
                console.log('Error al obtener los datos de la categoria:', error);
            }
        }
    });

    // ------------------------     ACTUALIZAR CATEGORIA    ------------------------
    document.getElementById('formEditarCategoria').addEventListener('submit', async function (e) {

        e.preventDefault();

        const categoriaId = document.querySelector('.btnActualizarCategoria').dataset.id;

        const categoriaActualizada = {
            nombre: document.getElementById('nombreEditar').value.trim(),
            capacidad_maxima: document.getElementById('capacidad_maximaEditar').value.trim(),
            tipo_cama: document.getElementById('tipo_camaEditar').value.trim(),
            precio_base: document.getElementById('precio_baseEditar').value.trim(),
            servicios_incluidos: document.getElementById('servicios_incluidosEditar').value.trim(),
            estado: document.getElementById('estadoEditar').value.trim()
        }

        if (!categoriaOriginal) {
            console.error('Error: No hay datos originales de la categoria');
            mostrarAlerta('Error', 'No se pudieron comparar los datos originales', 'error');
            return;
        }

        //Comparar con los datos originales
        let cambios = {};
        if (categoriaActualizada.nombre !== categoriaOriginal.nombre) cambios.nombre = categoriaActualizada.nombre;
        if (categoriaActualizada.capacidad_maxima !== categoriaOriginal.capacidad_maxima) cambios.capacidad_maxima = categoriaActualizada.capacidad_maxima;
        if (categoriaActualizada.tipo_cama !== categoriaOriginal.tipo_cama) cambios.tipo_cama = categoriaActualizada.tipo_cama;
        if (categoriaActualizada.precio_base !== categoriaOriginal.precio_base) cambios.precio_base = categoriaActualizada.precio_base;
        if (categoriaActualizada.servicios_incluidos !== categoriaOriginal.servicios_incluidos) cambios.servicios_incluidos = categoriaActualizada.servicios_incluidos;
        if (categoriaActualizada.estado !== categoriaOriginal.estado) cambios.estado = categoriaActualizada.estado;

        // Si no hay cambios, no se envia la peticion
        if(Object.keys(cambios).length === 0){
            mostrarAlerta2('No hay cambios por enviar', 'error');
            return;
        }

        // Determinar si es PUT O PATCH
        const metodo = Object.keys(cambios).length === 6 ? 'PUT' : 'PATCH';
        const datos = metodo === 'PUT' ? categoriaActualizada : cambios;

        try {
            // Enviar la actualizacion al servidor
            const url = `/api/categorias/${categoriaId}`;
            const respuesta = await fetch(url, {
                method: metodo,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datos)
            });

            if(!respuesta.ok){
                const errorData = await respuestaUpdate.json();
                throw new Error(errorData.mensaje || 'Error desconocido al actualizar');
            }

            const resultado = await respuesta.json();
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
            initDataTable();
            
        } catch (error) {
            console.error('Error al actualizar nivel:', error);
            mostrarAlerta('Error', error.message, 'error');
        }
    });

    //  --------------    CREAR NUEVO NIVEL     ----------------
    const botonSubirCategoria = document.querySelector('.btnSubirCategoria');
    botonSubirCategoria.addEventListener('click', async function (e) {
        e.preventDefault();

        const categoriaNueva = {
            nombre: document.getElementById('nombre').value.trim(),
            capacidad_maxima: document.getElementById('capacidad_maxima').value.trim(),
            tipo_cama: document.getElementById('tipo_cama').value.trim(),
            precio_base: document.getElementById('precio_base').value.trim(),
            servicios_incluidos: document.getElementById('servicios_incluidos').value.trim(),
            estado: document.getElementById('estado').value.trim()
        };

        if (categoriaNueva.nombre === "" || categoriaNueva.capacidad_maxima === "" || categoriaNueva.tipo_cama === "" || categoriaNueva.precio_base === "") {
            mostrarAlerta2('Todos los campos son necesarios', 'error');
            return;
        }
        
        try {
            const datos = new FormData();
            Object.entries(categoriaNueva).forEach(([key, value]) => datos.append(key, value));
            const respuesta = await fetch('/api/categorias', {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
            initDataTable();

            // Limpiar los campos del formulario
            document.getElementById('nombre').value = '';
            document.getElementById('capacidad_maxima').value = '';
            document.getElementById('tipo_cama').value = '';
            document.getElementById('servicios_incluidos').value = '';
            document.getElementById('precio_base').value = '';
            document.getElementById('estatado').value = '';

        } catch (error) {
            console.error('Error en la solicitud:', error);
        }
    });
}