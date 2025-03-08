if(window.location.pathname === '/admin/puntodeventa/catalogo'){
    
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

        const productos = await listarProductos(); // Esperamos los datos antes de inicializar DataTable

        if (productos.length > 0) {
            llenarTabla(productos);
        }

        // Inicializa DataTable
        dataTable = $('#datatable_productos').DataTable(dataTableOption);
        dataTableInit = true;
    }

    // Función para obtener los datos de la API
    async function listarProductos() {
        try {
            const response = await fetch('/api/productos');
            const productos = await response.json();
            return productos;
        } catch (error) {
            console.error('Error al obtener los productos:', error);
            return [];
        }
    }

    // Función para llenar la tabla con los datos obtenidos
    function llenarTabla(productos) {
        const tbody = document.getElementById('tableBody_productos');
        tbody.innerHTML = ''; // Limpiamos el contenido previo
        console.log(productos);

        productos.forEach((producto, index) => {

            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${producto.nombre}</td>
                    <td>${producto.precio}</td>
                    <td class="text-center">${producto.stock}</td>
                    <td class="text-center">${producto.categoria_producto_id.nombre}</td>
                    <td class="text-center">${producto.codigo_barras}</td>
                    <td class="text-center">${producto.proveedor}</td>
                    <td><img src="/build/img/${producto.foto}.png" alt="Descripción de la imagen" width="65"></td>
                    <td>
                        <!-- Botón de editar que abre el modal -->
                        <button 
                            class="btn btn-sm btn-primary btnEditarNivel" 
                            data-id="${producto.id}" 
                            data-toggle="modal" 
                            data-target="#modalEditarNivel">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <!-- Botón de eliminar -->
                        <button class="btn btn-sm btn-danger btn-eliminarNivel" data-id="${producto.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

}