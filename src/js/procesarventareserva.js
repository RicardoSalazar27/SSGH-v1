if (window.location.pathname === '/admin/puntodeventa/vender/reserva') {
    const inputBuscador = document.getElementById('inputBuscarProducto');
    const listaSugerencias = document.getElementById('listaSugerencias');
    const tablaVenta = document.getElementById('tablaVentaProductos');

    let serviciosVendidos = [];
    let productosDisponibles = []; // Para guardar los productos obtenidos de la búsqueda

    inputBuscador.addEventListener('input', async (e) => {
        const query = e.target.value.trim();
        if (query.length >= 3) {
            try {
                const url = `/api/productos/codigo/${query}`;
                const respuesta = await fetch(url);
                productosDisponibles = await respuesta.json(); // Guardamos los productos disponibles
                renderSugerencias(productosDisponibles);
            } catch (error) {
                console.error('Error al buscar productos:', error);
                listaSugerencias.innerHTML = '<li class="list-group-item text-danger">Error en la búsqueda</li>';
                listaSugerencias.classList.remove('d-none');
            }
        } else {
            limpiarLista();
        }
    });

    function renderSugerencias(productos) {
        listaSugerencias.innerHTML = '';
        if (!Array.isArray(productos) || productos.length === 0) {
            listaSugerencias.innerHTML = '<li class="list-group-item">No se encontraron productos</li>';
        } else {
            productos.forEach(producto => {
                const li = document.createElement('li');
                li.classList.add('list-group-item', 'list-group-item-action');
                li.textContent = `${producto.codigo_barras} - ${producto.nombre}`;
                li.addEventListener('click', () => {
                    agregarProducto(producto);  // Le pasamos el objeto completo del producto
                    limpiarLista();
                    inputBuscador.value = '';
                });
                listaSugerencias.appendChild(li);
            });
        }
        listaSugerencias.classList.remove('d-none');
    }

    function limpiarLista() {
        listaSugerencias.innerHTML = '';
        listaSugerencias.classList.add('d-none');
    }

    function agregarProducto(producto) {
        const precioUnitario = parseFloat(producto.precio);
    
        // Verificar si el producto ya está en la lista de serviciosVendidos
        let productoExistente = serviciosVendidos.find(p => p.codigo_barras === producto.codigo_barras);
    
        if (productoExistente) {
            // Si el producto ya existe, incrementamos la cantidad
            productoExistente.cantidad++;
            productoExistente.total = productoExistente.cantidad * precioUnitario;
        } else {
            // Si el producto no existe, lo agregamos a la lista
            serviciosVendidos.push({
                codigo_barras: producto.codigo_barras,
                nombre: producto.nombre,  // Agregamos el nombre del producto
                proveedor: producto.proveedor,  // Agregamos el proveedor
                precio: producto.precio,  // Precio unitario
                foto: producto.foto,  // Foto del producto
                cantidad: 1,
                total: precioUnitario,
                stock: producto.stock  // Agregar stock al producto
            });
        }
    
        // Actualizamos la tabla con los productos
        actualizarTabla();
    }
    
    // Función para actualizar la tabla
    function actualizarTabla() {
        // Limpiar la tabla antes de actualizarla
        tablaVenta.innerHTML = '';
    
        // Recorrer todos los productos vendidos
        serviciosVendidos.forEach(servicio => {
            const fila = document.createElement('tr');
            fila.innerHTML = `
                <td>${servicio.nombre}</td>
                <td>${servicio.proveedor}</td>
                <td>
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <button class="btn btn-sm btn-outline-secondary btn-restar" data-id="${servicio.codigo_barras}">-</button>
                        <span class="cantidad">${servicio.cantidad}</span>
                        <button class="btn btn-sm btn-outline-secondary btn-sumar" data-id="${servicio.codigo_barras}" ${servicio.cantidad >= servicio.stock ? 'disabled' : ''}>+</button>
                    </div>
                </td>
                <td>$${parseFloat(servicio.precio).toFixed(2)}</td>
                <td>$${servicio.total.toFixed(2)}</td>
                <td><img src="/build/img/${servicio.foto}.png" alt="Foto" width="50" height="50"></td>
                <td><button class="btn btn-danger btn-sm" data-id="${servicio.codigo_barras}" id="eliminarProducto">Eliminar</button></td>
            `;
            tablaVenta.appendChild(fila);
        });
    
        // Agregar los eventos de eliminar, sumar y restar después de que la tabla haya sido actualizada
        agregarEventosEliminar();
        agregarEventosContador();
    }
    
    // Función para agregar eventos de eliminación
    function agregarEventosEliminar() {
        const botonesEliminar = document.querySelectorAll('#eliminarProducto');
    
        botonesEliminar.forEach(boton => {
            boton.addEventListener('click', function () {
                const codigoBarras = this.getAttribute('data-id');
                eliminarProducto(codigoBarras);
            });
        });
    }
    
    // Función para eliminar un producto de serviciosVendidos
    function eliminarProducto(codigoBarras) {
        // Filtrar el producto que queremos eliminar
        serviciosVendidos = serviciosVendidos.filter(servicio => servicio.codigo_barras !== codigoBarras);
    
        // Volver a actualizar la tabla
        actualizarTabla();
    }
    
    // Función para agregar eventos de incremento (+) y decremento (-)
    function agregarEventosContador() {
        const btnSumar = document.querySelectorAll('.btn-sumar');
        const btnRestar = document.querySelectorAll('.btn-restar');
    
        // Evento de Sumar
        btnSumar.forEach(btn => {
            btn.addEventListener('click', function () {
                const codigoBarras = this.getAttribute('data-id');
                const producto = serviciosVendidos.find(p => p.codigo_barras === codigoBarras);
    
                if (producto && producto.cantidad < producto.stock) {
                    producto.cantidad++;
                    producto.total = producto.cantidad * parseFloat(producto.precio);
                }
    
                // Actualizamos la tabla con los productos
                actualizarTabla();
            });
        });
    
        // Evento de Restar
        btnRestar.forEach(btn => {
            btn.addEventListener('click', function () {
                const codigoBarras = this.getAttribute('data-id');
                const producto = serviciosVendidos.find(p => p.codigo_barras === codigoBarras);
    
                if (producto && producto.cantidad > 1) {
                    producto.cantidad--;
                    producto.total = producto.cantidad * parseFloat(producto.precio);
                }
    
                // Actualizamos la tabla con los productos
                actualizarTabla();
            });
        });
    }
            
    // Cerrar la lista si haces clic fuera
    document.addEventListener('click', function (e) {
        const isClickInside = inputBuscador.contains(e.target) || listaSugerencias.contains(e.target);
        if (!isClickInside) {
            limpiarLista();
        }
    });
}
