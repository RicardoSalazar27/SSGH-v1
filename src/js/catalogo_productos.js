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
        //console.log(productos);

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
                            class="btn btn-sm btn-primary btnEditarProducto" 
                            data-id="${producto.id}" 
                            data-toggle="modal" 
                            data-target="#modalEditarProducto">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <!-- Botón de eliminar -->
                        <button class="btn btn-sm btn-danger btn-eliminarProducto" data-id="${producto.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }

    // ---------------    LLENAR MODAL PARA ACTUALIZAR  -----------------
    document.addEventListener('click', async function (event) {
        if (event.target.closest('.btnEditarProducto')) {
            
            const boton = event.target.closest('.btnEditarProducto');
            const productoId = boton.dataset.id;
            
            try {
                // Obtener los datos del usuario desde la API o una variable global
                const respuesta = await fetch(`/api/productos/${productoId}`);
                if (!respuesta.ok) {
                    throw new Error(`Error al obtener usuario: ${respuesta.statusText}`);
                }
    
                const producto = await respuesta.json();
                // Llenar los campos del modal con los datos del producto
                document.getElementById('nombreEditar').value = producto.nombre;
                document.getElementById('precioEditar').value = producto.precio;
                document.getElementById('stockEditar').value = producto.stock;
                document.getElementById('categoria_idEditar').value = producto.categoria_producto_id;
                document.getElementById('codigo_barrasEditar').value = producto.codigo_barras;
                document.getElementById('proveedorEditar').value = producto.proveedor;
                
                // Mostrar la imagen si existe
                const imgElement = document.getElementById('imgEditarP');
                imgElement.src = producto.foto ? `/build/img/${producto.foto}.png` : '/build/img/default.png';                    
    
                // Guardar el ID en el botón de actualización
                document.querySelector('.btnActualizarProducto').dataset.id = productoId;
        
            } catch (error) {
                console.error('Error al obtener los datos del producto:', error);
            }
        }
    });

    // ---------------    ACTUALIZAR PRODUCTO     -----------------
    document.getElementById('formEditarProducto').addEventListener('submit', async function (e) {
        e.preventDefault();
    
        const productoId = document.querySelector('.btnActualizarProducto').dataset.id;
    
        const productoActualizado = {
            nombre: document.getElementById('nombreEditar').value.trim(),
            precio: document.getElementById('precioEditar').value.trim(),
            stock: document.getElementById('stockEditar').value.trim(),
            categoria_producto_id: document.getElementById('categoria_idEditar').value.trim(),
            codigo_barras: document.getElementById('codigo_barrasEditar').value.trim(),
            proveedor: document.getElementById('proveedorEditar').value.trim(),
            foto: document.getElementById('fotoP').files[0]
        };

        //  console.log(productoActualizado);
        //  return;
    
        if (!productoActualizado.nombre || !productoActualizado.precio || !productoActualizado.categoria_producto_id) {
            mostrarAlerta('Error', 'No pueden ir vacios.', 'error');
            return;
        }

        try {
            const datos = new FormData();
            Object.entries(productoActualizado).forEach(([key, value]) => datos.append(key, value));
            const respuesta = await fetch(`/api/productos/${productoId}`, {
                method: 'POST',
                body: datos
            });
    
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
            const inputFile = document.getElementById('fotoP');
            inputFile.value = ''; // Intenta resetear primero
            if (inputFile.value) { 
                inputFile.parentNode.replaceChild(inputFile.cloneNode(true), inputFile);
            }

            initDataTable();
    
        } catch (error) {
            console.error('Error al actualizar producto:', error);
        }
    });

    // Delegación de eventos para eliminación de niveles
    document.getElementById('tableBody_productos').addEventListener('click', async function (event) {
        if (event.target.closest('.btn-eliminarProducto')) {
            const productoId = event.target.closest('.btn-eliminarProducto').getAttribute('data-id');
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
                    const url = `/api/productos/${productoId}`;
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
}