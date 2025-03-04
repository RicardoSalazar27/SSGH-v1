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
}