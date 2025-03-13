if(window.location.pathname === '/admin/reservaciones'){
    // Elementos del DOM
    const inputCorreo = document.getElementById('searchEmail');
    const listaSugerencias = document.getElementById('sugerenciasCorreo');
    const fechaEntrada = document.getElementById("fechaEntrada");
    const fechaSalida = document.getElementById("fechaSalida");
    const selectHabitacion = document.getElementById("habitacion");
    const btnSiguiente = document.getElementById('btnSiguiente');
    const btnAtras = document.getElementById('btnAtras');
    const btnConfirmar = document.getElementById('btnConfirmar'); // Asegúrate de tener este botón en el modal
    const totalPagarInput = document.getElementById("totalPagar");
    const descuentoInput = document.getElementById("descuento");
    const cobroExtraInput = document.getElementById("cobroExtra");

    // Variables de control
    let timeoutBusqueda;
    let clienteNuevo = {};  
    let pasoActual = 1;
    let habitacionesDisponibles = []; // Definir la variable global para las habitaciones
    let habitacionesSeleccionadas = [];

    // Inicializar Choices.js para la selección de habitaciones
    const choices = new Choices(selectHabitacion, {
        removeItemButton: true,
        placeholder: true,
        placeholderValue: "Seleccione una o más habitaciones",
        searchEnabled: false,
    });

    /**
     * Busca clientes por correo en la API
     */
    async function buscarClientes(correo) {
        try {
            const response = await fetch(`/api/clientes/correo/${encodeURIComponent(correo)}`);
            return await response.json();
        } catch (error) {
            console.error("Error al obtener clientes:", error);
            return [];
        }
    }

    /**
     * Muestra sugerencias de clientes en la lista desplegable
     */
    function mostrarSugerencias(clientes) {
        listaSugerencias.innerHTML = '';
        if (clientes.length === 0) {
            listaSugerencias.classList.add('d-none');
            return;
        }
        listaSugerencias.classList.remove('d-none');
        clientes.forEach(cliente => {
            const item = document.createElement('li');
            item.classList.add('list-group-item', 'list-group-item-action');
            item.textContent = cliente.correo;
            item.dataset.id = cliente.id;
            item.addEventListener('click', () => seleccionarCliente(cliente));
            listaSugerencias.appendChild(item);
        });
    }

    /**
     * Llena los campos con la información del cliente seleccionado
     */
    function seleccionarCliente(cliente) {
        inputCorreo.value = cliente.correo;
        document.getElementById('nombre').value = cliente.nombre;
        document.getElementById('apellidos').value = cliente.apellidos;
        document.getElementById('documento_identidad').value = cliente.documento_identidad;
        document.getElementById('telefono').value = cliente.telefono;
        document.getElementById('direccion').value = cliente.direccion;
        listaSugerencias.classList.add('d-none');
    }

    /**
     * Guarda los datos de un nuevo cliente ingresado
     */
    function guardarClienteNuevo() {
        clienteNuevo = {
            correo: inputCorreo.value.trim(),
            nombre: document.getElementById('nombre').value.trim(),
            apellidos: document.getElementById('apellidos').value.trim(),
            documento_identidad: document.getElementById('documento_identidad').value.trim(),
            telefono: document.getElementById('telefono').value.trim(),
            direccion: document.getElementById('direccion').value.trim()
        };
    }

    /**
     * Maneja el cambio de pasos en el modal
     */
    btnSiguiente.addEventListener('click', () => {
        switch (pasoActual) {
            case 1:
                if (!inputCorreo.value.trim()) {
                    alert("Por favor, ingrese un correo.");
                    return;
                }
                if (!document.getElementById('nombre').value.trim()) {
                    guardarClienteNuevo();
                }
                cambiarPaso(2);
                break;
            case 2:
                habitacionesSeleccionadas = choices.getValue(true);
                if (habitacionesSeleccionadas.length === 0) {
                    alert("Por favor, seleccione una habitación.");
                    return;
                }
                cambiarPaso(3);
                btnSiguiente.classList.add('d-none'); // Ocultar el botón de Siguiente en el paso 3
                btnConfirmar.classList.remove('d-none'); // Mostrar el botón de Confirmar en el paso 3
                calcularTotalPagar(); // Calcular total cuando lleguemos al paso 3
                break;
            case 3:
                alert("Reserva confirmada!");
                break;
        }
    });

    /**
     * Cambia entre los pasos del modal
     */
    function cambiarPaso(nuevoPaso) {
        document.getElementById(`step${pasoActual}`).classList.add('d-none');
        document.getElementById(`step${nuevoPaso}`).classList.remove('d-none');
        pasoActual = nuevoPaso;
        btnAtras.classList.toggle('d-none', pasoActual === 1);
        btnSiguiente.textContent = "Siguiente";
    }

    /**
     * Carga habitaciones disponibles según las fechas seleccionadas
     */
    async function cargarHabitaciones() {
        if (!fechaEntrada.value || !fechaSalida.value) return;
        try {
            const response = await fetch(`/api/habitaciones/disponibles/${fechaEntrada.value}/${fechaSalida.value}`);
            habitacionesDisponibles = await response.json();  // Guardar habitaciones disponibles
            choices.clearChoices();
            if (habitacionesDisponibles.length === 0) {
                choices.setChoices([{ value: "", label: "No hay habitaciones disponibles", disabled: true }]);
                return;
            }
            choices.setChoices(habitacionesDisponibles.map(habitacion => ({
                value: habitacion.id,
                label: `Habitación ${habitacion.numero} | ${habitacion.id_categoria.nombre} | Capacidad max. ${habitacion.id_categoria.capacidad_maxima} personas | $${habitacion.id_categoria.precio_base} MXN`
            })));
        } catch (error) {
            console.error("Error al obtener habitaciones:", error);
        }
    }

    /**
     * Evento para detectar cambios en las fechas y cargar habitaciones
     */
    fechaEntrada.addEventListener("change", cargarHabitaciones);
    fechaSalida.addEventListener("change", cargarHabitaciones);

    /**
     * Maneja el retroceso de pasos en el modal
     */
    btnAtras.addEventListener('click', () => {
        if (pasoActual > 1) {
            cambiarPaso(pasoActual - 1);
        }
    });

    /**
     * Maneja la búsqueda de clientes por correo con retraso
     */
    inputCorreo.addEventListener('input', () => {
        clearTimeout(timeoutBusqueda);
        const valor = inputCorreo.value.trim();
        if (valor.length < 3) {
            listaSugerencias.classList.add('d-none');
            return;
        }
        timeoutBusqueda = setTimeout(async () => {
            mostrarSugerencias(await buscarClientes(valor));
        }, 300);
    });

    /**
     * Oculta las sugerencias si se hace clic fuera del input
     */
    document.addEventListener('click', (e) => {
        if (!inputCorreo.contains(e.target) && !listaSugerencias.contains(e.target)) {
            listaSugerencias.classList.add('d-none');
        }
    });

    /**
     * Calcular el total a pagar en el paso 3
     */
    // Código actualizado para el paso 3 del modal:

function calcularTotalPagar() {
    let total = 0;
    habitacionesSeleccionadas.forEach(habitacionId => {
        const habitacion = habitacionesDisponibles.find(h => h.id === habitacionId);  // Buscar en habitacionesDisponibles
        if (habitacion) {
            total += parseFloat(habitacion.id_categoria.precio_base);
        }
    });

    // Aplicar descuento
    const descuento = parseFloat(descuentoInput.value) || 0;
    const tipoDescuento = document.querySelector('input[name="tipoDescuento"]:checked').value;
    if (tipoDescuento === 'porcentaje') {
        total -= (total * descuento) / 100;
    } else {
        total -= descuento;
    }

    // Aplicar cobro extra
    const cobroExtra = parseFloat(cobroExtraInput.value) || 0;
    total += cobroExtra;

    // Actualizar el total en el campo correspondiente
    totalPagarInput.value = total.toFixed(2);

    // Obtener el cliente
    let clienteFinal = {};

    if (clienteNuevo.correo) {
        // Si el cliente es nuevo, usamos los datos que se ingresaron
        clienteFinal = clienteNuevo;
    } else {
        // Si el cliente ya existía y fue seleccionado, usamos sus datos
        clienteFinal = {
            correo: inputCorreo.value,
            nombre: document.getElementById('nombre').value,
            apellidos: document.getElementById('apellidos').value,
            documento_identidad: document.getElementById('documento_identidad').value,
            telefono: document.getElementById('telefono').value,
            direccion: document.getElementById('direccion').value
        };
    }

    // Mostrar los datos del cliente y la reserva en consola para depuración
    // console.log({
    //     cliente: clienteFinal,  // Muestra los datos del cliente (nuevo o existente)
    //     fechas: {
    //         entrada: fechaEntrada.value,
    //         salida: fechaSalida.value
    //     },
    //     habitaciones: habitacionesSeleccionadas,
    //     totalPagar: total,
    //     descuento: descuento,
    //     cobroExtra: cobroExtra
    // });
}

    // Evento de Confirmar en el paso 3 del modal:
    btnConfirmar.addEventListener('click', async () => {
        // Primero, obtendremos todos los valores actualizados
        let total = 0;
        habitacionesSeleccionadas.forEach(habitacionId => {
            const habitacion = habitacionesDisponibles.find(h => h.id === habitacionId);  // Buscar en habitacionesDisponibles
            if (habitacion) {
                total += parseFloat(habitacion.id_categoria.precio_base);
            }
        });

        // Obtener el descuento
        const descuento = parseFloat(descuentoInput.value) || 0;
        const tipoDescuento = document.querySelector('input[name="tipoDescuento"]:checked').value;
        if (tipoDescuento === 'porcentaje') {
            // Si el descuento es porcentaje, lo convertimos a cantidad en pesos
            total -= (total * descuento) / 100; // Se resta el porcentaje del total
        } else {
            // Si es un monto, simplemente lo restamos
            total -= descuento;
        }

        // Aplicar cobro extra
        const cobroExtra = parseFloat(cobroExtraInput.value) || 0;
        total += cobroExtra;

        // Obtener observaciones y tipo de pago
        const observaciones = document.getElementById('observaciones').value.trim();
        const metodoPago = document.getElementById('metodoPago').value;
        // Obtener el adelanto ingresado en el formulario
        const adelanto = parseFloat(document.getElementById('adelanto').value) || 0;  // Si no se ingresa un valor, por defecto será 0


        // Actualizar el total en el campo correspondiente
        totalPagarInput.value = total.toFixed(2);

        // Obtener el cliente
        let clienteFinal = {};

        if (clienteNuevo.correo) {
            // Si el cliente es nuevo, usamos los datos que se ingresaron
            clienteFinal = clienteNuevo;
        } else {
            // Si el cliente ya existía y fue seleccionado, usamos sus datos
            clienteFinal = {
                correo: inputCorreo.value,
                nombre: document.getElementById('nombre').value,
                apellidos: document.getElementById('apellidos').value,
                documento_identidad: document.getElementById('documento_identidad').value,
                telefono: document.getElementById('telefono').value,
                direccion: document.getElementById('direccion').value
            };
        }

        // Mostrar los datos finales en el console.log
        console.log({
            cliente: clienteFinal,  // Datos del cliente (nuevo o existente)
            fechas: {
                entrada: fechaEntrada.value,
                salida: fechaSalida.value
            },
            habitaciones: habitacionesSeleccionadas,
            totalPagar: total.toFixed(2),  // Total a pagar con descuento y cobro extra
            descuento: tipoDescuento === 'porcentaje' ? (total * descuento) / 100 : descuento,  // Si es porcentaje, mostramos en pesos
            cobroExtra: cobroExtra,
            observaciones: observaciones,  // Observaciones ingresadas
            metodoPago: metodoPago  // Método de pago seleccionado
        });

        // Enviar los datos al servidor
        const url = '/api/reservaciones';  // URL de la API de reservaciones
        const datos = new FormData();

        // Añadir los datos al FormData
        datos.append('cliente', JSON.stringify(clienteFinal));
        datos.append('fechas', JSON.stringify({ entrada: fechaEntrada.value, salida: fechaSalida.value }));
        datos.append('habitaciones', JSON.stringify(habitacionesSeleccionadas));
        datos.append('totalPagar', total.toFixed(2));
        datos.append('descuento', tipoDescuento === 'porcentaje' ? (total * descuento) / 100 : descuento);  // Descuento calculado
        datos.append('cobroExtra', cobroExtra);
        datos.append('observaciones', observaciones);
        datos.append('metodoPago', metodoPago);
        datos.append('adelanto', adelanto.toFixed(2));  // Añadir el adelanto al FormData

        try {
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();
            // Mostrar alerta
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);

            // Resetear el modal para una nueva reservación
            resetearModal();

            var modalElement = document.getElementById('modalReservacion');
            var MyModal = new bootstrap.Modal(modalElement);
            MyModal.hide();

        } catch (error) {
            console.error('Error en la solicitud:', error);
        }
    });

    // Agregar listeners para actualizar en tiempo real el total cuando haya cambios
    descuentoInput.addEventListener('input', calcularTotalPagar);
    cobroExtraInput.addEventListener('input', calcularTotalPagar);

    function resetearModal() {
        // Limpiar todos los campos del formulario en el modal
        document.getElementById('nombre').value = '';
        document.getElementById('apellidos').value = '';
        document.getElementById('documento_identidad').value = '';
        document.getElementById('telefono').value = '';
        document.getElementById('direccion').value = '';
        document.getElementById('observaciones').value = '';
        selectHabitacion.value = '';  // Limpiar la selección de habitación
        fechaEntrada.value = '';  // Limpiar la fecha de entrada
        fechaSalida.value = '';  // Limpiar la fecha de salida
        totalPagarInput.value = '';  // Limpiar el total a pagar
        adelanto.value = ''; // Limpiar el adelanto
        
        // Limpiar los campos específicos de descuento y cobro extra
        descuentoInput.value = '';  // Limpiar descuento
        cobroExtraInput.value = '';  // Limpiar cobro extra
        
        // Limpiar las habitaciones seleccionadas
        choices.clearChoices();  // Esto debería limpiar las opciones seleccionadas del selector de habitaciones
        
        // Limpiar la selección de las habitaciones en el select (si existe el elemento selectHabitacion)
        const habitacionesSelect = document.getElementById('selectHabitacion');
        if (habitacionesSelect) {
            for (let option of habitacionesSelect.options) {
                option.selected = false;  // Deseleccionar todas las opciones
            }
        }
    
        // Limpiar el campo de correo
        inputCorreo.value = '';  // Limpiar correo
        
        // Resetear el paso actual al step 1
        pasoActual = 1;
        
        // Mostrar solo el paso 1, ocultando los demás
        document.getElementById('step1').classList.remove('d-none');
        document.getElementById('step2').classList.add('d-none');
        document.getElementById('step3').classList.add('d-none');
        document.getElementById('btnConfirmar').classList.add('d-none');
        
        // Resetear la visibilidad de los botones
        btnAtras.classList.add('d-none');  // Ocultar el botón "Previo"
        btnSiguiente.classList.remove('d-none');  // Mostrar el botón "Siguiente"
    }
            
}
