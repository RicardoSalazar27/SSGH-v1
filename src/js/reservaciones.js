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
    const adelantoInput = document.getElementById("adelanto");

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

    // Variables para la barra de progreso
const progressBar = document.getElementById('progressBar');

// Función para actualizar la barra de progreso
function actualizarBarraProgreso(porcentaje) {
    // Actualizamos el estilo de la barra y el texto dentro de ella
    progressBar.style.width = `${porcentaje}%`;
    progressBar.setAttribute('aria-valuenow', porcentaje);
    progressBar.textContent = `${porcentaje}%`;
}

// Modificar la lógica de los pasos
btnSiguiente.addEventListener('click', () => {
    switch (pasoActual) {
        case 1:
            // if (!inputCorreo.value.trim()) {
            //     alert("Por favor, ingrese un correo.");
            //     return;
            // }
            if (!document.getElementById('nombre').value.trim()) {
                guardarClienteNuevo();
            }
            
            const inputNombre = document.getElementById('nombre').value.trim();
            const inputTelefono = document.getElementById('telefono').value.trim();
            
            if (!inputNombre || !inputTelefono) {
                mostrarAlerta('Campos Obligatorios', 'El nombre y el teléfono son obligatorios para crear reservación', 'warning');
                return;
            }            
            cambiarPaso(2);
            actualizarBarraProgreso(66);  // Actualizar barra a 66% en el paso 2
            break;
        case 2:
            habitacionesSeleccionadas = choices.getValue(true);
            if (habitacionesSeleccionadas.length === 0) {
                mostrarAlerta('No selecciono habitaciones','Por favor, seleccione una habitación.','warning');
                return;
            }
            cambiarPaso(3);
            // Calcular el total cuando llegues al paso 3
            calcularTotalPagar();  // Llamada aquí para calcular el total automáticamente
            btnSiguiente.classList.add('d-none'); // Ocultar el botón de Siguiente en el paso 3
            btnConfirmar.classList.remove('d-none'); // Mostrar el botón de Confirmar en el paso 3
            actualizarBarraProgreso(100);  // Actualizar barra a 100% en el paso 3
            break;
        // case 3:
        //     alert("Reserva confirmada!");
        //     break;
    }
});


function cambiarPaso(nuevoPaso) {
    // Ocultar el paso actual y mostrar el nuevo paso
    document.getElementById(`step${pasoActual}`).classList.add('d-none');
    document.getElementById(`step${nuevoPaso}`).classList.remove('d-none');
    
    pasoActual = nuevoPaso;
    
    // Mostrar u ocultar el botón de "Atras" dependiendo del paso actual
    btnAtras.classList.toggle('d-none', pasoActual === 1);
    
    // Actualizar el texto y la visibilidad de los botones
    if (pasoActual === 3) {
        btnSiguiente.textContent = "Registrar";  // En el paso 3 se muestra "Registrar"
        btnConfirmar.classList.remove('d-none');
        btnSiguiente.classList.add('d-none');
    } else {
        btnSiguiente.textContent = "Siguiente";  // En los otros pasos se muestra "Siguiente"
        btnConfirmar.classList.add('d-none');
        btnSiguiente.classList.remove('d-none');
    }
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

    let totalPagarOriginal = 0;  // Definir fuera para que sea accesible globalmente

    // Función para calcular el total a pagar
    function calcularTotalPagar() {
        let total = 0;
        
        // Obtener la diferencia de noches entre las fechas de entrada y salida
        const fechaEntradaDate = new Date(fechaEntrada.value);
        const fechaSalidaDate = new Date(fechaSalida.value);
        const diferenciaNoches = (fechaSalidaDate - fechaEntradaDate) / (1000 * 60 * 60 * 24);  // Calculamos la diferencia en días
        
        if (diferenciaNoches <= 0) {
            alert("La fecha de salida debe ser posterior a la de entrada.");
            return;
        }
        
        // Sumar los precios base de las habitaciones seleccionadas, multiplicados por las noches
        habitacionesSeleccionadas.forEach(habitacionId => {
            const habitacion = habitacionesDisponibles.find(h => h.id === habitacionId);  // Buscar la habitación en las disponibles
            if (habitacion) {
                total += parseFloat(habitacion.id_categoria.precio_base) * diferenciaNoches;  // Multiplicar por la cantidad de noches
            }
        });
        
        // Guardamos el total de las habitaciones seleccionadas antes de aplicar ningún descuento o cobro extra
        totalPagarOriginal = total;  // Aquí almacenamos el precio total sin descuentos ni cobros extras
        
        // Obtener el descuento ingresado
        const descuento = parseFloat(descuentoInput.value) || 0;  // Si no se ingresa un valor, el descuento será 0
        const tipoDescuento = document.querySelector('input[name="tipoDescuento"]:checked') ? document.querySelector('input[name="tipoDescuento"]:checked').value : 'monto';  // Si no está seleccionado, por defecto es 'monto'

        // Aplicar el descuento
        if (tipoDescuento === 'PORCENTAJE') {
            total -= (total * descuento) / 100;  // Descuento en porcentaje
        } else {
            total -= descuento;  // Descuento en monto fijo
        }

        // Aplicar cobro extra
        const cobroExtra = parseFloat(cobroExtraInput.value) || 0;  // Si no se ingresa un valor, el cobro extra será 0
        total += cobroExtra;  // Sumar el cobro extra

        // Asegurarse de que el total no sea negativo después de aplicar el descuento y el cobro extra
        total = total < 0 ? 0 : total;

        // Obtener el adelanto
        const adelanto = parseFloat(adelantoInput.value) || 0;

        // Restar el adelanto del total
        total -= adelanto;  // Descontamos el adelanto del total a pagar

        // Asegurarse de que el total no sea negativo
        total = total < 0 ? 0 : total;

        // Actualizar el total en el campo correspondiente
        totalPagarInput.value = total.toFixed(2);  // Mostrar el total con 2 decimales
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
        if (tipoDescuento === 'PORCENTAJE') {
            // Si el descuento es porcentaje, lo convertimos a cantidad en pesos
            total -= (total * descuento) / 100; // Se resta el porcentaje del total
        } else {
            // Si es un monto, simplemente lo restamos
            total -= descuento;
        }

        // Aplicar cobro extra
        const cobroExtra = parseFloat(cobroExtraInput.value) || 0;
        total += cobroExtra;

        // Obtener el adelanto ingresado en el formulario
        const adelanto = parseFloat(document.getElementById('adelanto').value) || 0;  // Si no se ingresa un valor, por defecto será 0

        // Restar el adelanto
        total -= adelanto;  // Restar el adelanto del total

        // Asegurarse de que el total no sea negativo
        total = total < 0 ? 0 : total;

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

        // Enviar los datos al servidor
        const url = '/api/reservaciones';  // URL de la API de reservaciones
        const datos = new FormData();

        // Crear la estructura de datos
        const datosReserva = {
            cliente: {
                correo: clienteFinal.correo,
                nombre: clienteFinal.nombre,
                apellidos: clienteFinal.apellidos,
                documento_identidad: clienteFinal.documento_identidad,
                telefono: clienteFinal.telefono,
                direccion: clienteFinal.direccion
            },
            fechas: {
                entrada: `${fechaEntrada.value} 14:00:00`, // Agregar manualmente la hora de entrada
                salida: `${fechaSalida.value} 12:00:00`   // Agregar manualmente la hora de salida
            },
            habitaciones: habitacionesSeleccionadas,  // Array de habitaciones seleccionadas
            pago: {
                totalPagar: total.toFixed(2),
                totalPagarOriginal: totalPagarOriginal.toFixed(2),
                descuento: tipoDescuento === 'PORCENTAJE' ? (totalPagarOriginal * descuento) / 100 : descuento,
                tipoDescuento : tipoDescuento,
                cobroExtra: cobroExtra,
                adelanto: adelanto.toFixed(2)
            },
            observaciones: document.getElementById('observaciones').value.trim(),
            metodo_pago: document.getElementById('metodoPago').value
        };
        //console.log(datosReserva);
        //return;
        
        // Convertirlo a JSON para enviar al servidor
        const jsonDatosReserva = JSON.stringify(datosReserva);

        // Enviar el JSON usando FormData (si es necesario)
        datos.append('reserva', jsonDatosReserva);

        // Realizar la solicitud fetch
        try {
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            if (!respuesta.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const resultado = await respuesta.json();

            // Mostrar alerta con los resultados
            mostrarAlerta3(resultado.titulo, resultado.mensaje, resultado.tipo);
        } catch (error) {
            console.error('Error en la solicitud:', error);
            // Puedes agregar aquí un mensaje de error al usuario si es necesario
            mostrarAlerta('Error', 'Hubo un problema al procesar la solicitud', 'error');
        }
    });    

    // Agregar listeners para actualizar en tiempo real el total cuando haya cambios
    descuentoInput.addEventListener('input', calcularTotalPagar);
    cobroExtraInput.addEventListener('input', calcularTotalPagar);
    adelantoInput.addEventListener('input', calcularTotalPagar);


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
