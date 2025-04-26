if (window.location.pathname === "/admin/recepcion/habitacion") {

    // Elementos del DOM
    const listaSugerencias = document.getElementById("sugerenciaCorreo");
    const inputCorreoCliente = document.getElementById("correo");
    const inputNombreCliente = document.getElementById("nombre");
    const inputApellidosCliente = document.getElementById("apellidos");
    const inputDocumentoCliente = document.getElementById("documento");
    const inputTelefonoCliente = document.getElementById("telefono");
    const inputDireccionCliente = document.getElementById("direccion");

    //Modal para crear cliente si es nuevo
    const btnCrearCliente = document.querySelector(".btnCrearCliente");
    const inputNombreClienteNuevo = document.getElementById("nombreNuevoCliente");
    const inputApellidosClienteNuevo = document.getElementById("apellidosNuevoCliente");
    const inputCorreoClienteNuevo = document.getElementById("correoNuevoCliente");
    const inputTelefonoClienteNuevo = document.getElementById("telefonoNuevoCliente");
    const inputDocumentoClienteNuevo = document.getElementById("documento_identidadNuevoCliente");
    const inputDireccionClienteNuevo = document.getElementById("direccionNuevoCliente");

    let clienteNuevo = '';

    const inputFechaEntrada = document.getElementById("fechaEntrada");
    const inputFechaSalida = document.getElementById("fechaSalida");
    const inputTipoDescuento = document.querySelectorAll("input[name='tipoDescuento']");
    const inputDescuento = document.getElementById("descuento");
    const inputCobroExtra = document.getElementById("cobroExtra");
    const inputAdelanto = document.getElementById("adelanto");
    const inputTotalPagar = document.getElementById("totalPagar");
    const precioHabitacion = parseFloat(document.getElementById("precio_habitacion").textContent.trim()) || 0;
    const inputMetodoPago = document.getElementById("metodoPago");
    const inputObservaciones = document.getElementById("observaciones");
    
    let noches = 1;
    let totalOriginal = noches * precioHabitacion;
    let totalPendiente = totalOriginal;
    let descuento = 0;
    let tipoDescuento = '';
    let cobroExtra = 0;
    let adelanto = 0;

    // Función para buscar clientes por correo en la API
    async function buscarClientes(correo) {
        if (correo.length < 3) {
            listaSugerencias.classList.add("d-none");
            return;
        }

        try {
            const response = await fetch(`/api/clientes/correo/${encodeURIComponent(correo)}`);

            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }

            const clientes = await response.json();
            mostrarSugerencias(clientes);
        } catch (error) {
            console.error("Error al obtener clientes:", error);
            listaSugerencias.classList.add("d-none");
        }
    }

    // Función para mostrar sugerencias de clientes en la lista desplegable
    function mostrarSugerencias(clientes) {
        listaSugerencias.innerHTML = "";

        if (clientes.length === 0) {
            listaSugerencias.classList.add("d-none");
            return;
        }

        listaSugerencias.classList.remove("d-none");

        clientes.forEach((cliente) => {
            const item = document.createElement("li");
            item.classList.add("list-group-item", "list-group-item-action");
            item.textContent = cliente.correo;
            item.dataset.id = cliente.id;
            item.addEventListener("click", () => seleccionarCliente(cliente));
            listaSugerencias.appendChild(item);
        });
    }

    // Función para llenar los campos con la información del cliente seleccionado
    function seleccionarCliente(cliente) {
        inputCorreoCliente.value = cliente.correo;
        inputNombreCliente.value = cliente.nombre;
        inputApellidosCliente.value = cliente.apellidos;
        inputDocumentoCliente.value = cliente.documento_identidad;
        inputTelefonoCliente.value = cliente.telefono;
        inputDireccionCliente.value = cliente.direccion;

        listaSugerencias.classList.add("d-none");
    }

    // Evento para buscar clientes cuando el usuario escribe en el input
    inputCorreoCliente.addEventListener("input", (e) => {
        buscarClientes(e.target.value);
    });

    // Ocultar sugerencias si el usuario hace clic fuera
    document.addEventListener("click", (e) => {
        if (!inputCorreoCliente.contains(e.target) && !listaSugerencias.contains(e.target)) {
            listaSugerencias.classList.add("d-none");
        }
    });

    //Crear cliente nuevo si no esta registrado y llenar la reserva con sus datos
    btnCrearCliente.addEventListener("click", (event) =>{
        event.preventDefault();

        clienteNuevo = {
            nombre: inputNombreClienteNuevo.value.trim(),
            apellidos: inputApellidosClienteNuevo.value.trim(),
            correo: inputCorreoClienteNuevo.value.trim(),
            telefono: inputTelefonoClienteNuevo.value.trim(),
            documento_identidad: inputDocumentoClienteNuevo.value.trim(), // Tenías un error en "identad"
            direccion: inputDireccionClienteNuevo.value.trim()
        };

        //Validar los datos del cliente nuevo
        if(!clienteNuevo.nombre || !clienteNuevo.apellidos || !clienteNuevo.telefono){
            mostrarAlerta2('Datos Incompletos: El nombre, apellidos y telefono son obligatorios', 'error')
            return;
        }

        // Llenar los inputs principales con los datos del nuevo cliente
        inputNombreCliente.value = clienteNuevo.nombre + " " + clienteNuevo.apellidos;
        inputCorreoCliente.value = clienteNuevo.correo ?? '';
        inputDocumentoCliente.value = clienteNuevo.documento_identidad ?? '';
        inputTelefonoCliente.value = clienteNuevo.telefono;
        inputDireccionCliente.value = clienteNuevo.direccion ?? '';
        
        mostrarAlerta('Huesped Agregado','Datos capturados con exito','info');
    })

    /////////////////////////////
    function calcularNoches() {
        const fechaEntrada = new Date(inputFechaEntrada.value);
        const fechaSalida = new Date(inputFechaSalida.value);
    
        if (isNaN(fechaEntrada) || isNaN(fechaSalida) || fechaSalida <= fechaEntrada) {
            noches = 1;
        } else {
            noches = (fechaSalida - fechaEntrada) / (1000 * 60 * 60 * 24);
        }
    }
    
    function calcularTotalPagar() {
        totalOriginal = noches * precioHabitacion;
        
        descuento = parseFloat(inputDescuento.value) || 0;
        cobroExtra = parseFloat(inputCobroExtra.value) || 0;
        adelanto = parseFloat(inputAdelanto.value) || 0;
        
        const tipoDescuentoSeleccionado = document.querySelector("input[name='tipoDescuento']:checked");
        tipoDescuento = tipoDescuentoSeleccionado ? tipoDescuentoSeleccionado.value : '';
        
        let totalConDescuento = totalOriginal;
        if (tipoDescuento === "PORCENTAJE") {
            // Aquí calculas el descuento como cantidad real
            descuento = totalOriginal * (descuento / 100);
            totalConDescuento -= descuento;
        } else if (tipoDescuento === "MONTO") {
            totalConDescuento -= descuento;
        }
        
        totalPendiente = totalConDescuento + cobroExtra - adelanto;
        inputTotalPagar.value = totalPendiente.toFixed(2);
    }
    
    function actualizarCalculo() {
        calcularNoches();
        calcularTotalPagar();
    }
    
    [inputFechaEntrada, inputFechaSalida, inputDescuento, inputCobroExtra, inputAdelanto].forEach(input => {
        input.addEventListener("input", actualizarCalculo);
    });
    
    inputTipoDescuento.forEach(radio => {
        radio.addEventListener("change", actualizarCalculo);
    });
    
    // Calcular total inicial al cargar la página
    actualizarCalculo();

    //Obtener datos y enviar datos de la reserva de la habitacion al servidor
    const metodoPago = inputMetodoPago.value;
    const observaciones = inputObservaciones.value.trim();
    document.getElementById("reservarHabitacion").addEventListener('click', async (e) => {
        
        e.preventDefault();
        
        // Obtener la hora actual con ceros asegurados
        const ahora = new Date();
        const horas = String(ahora.getHours()).padStart(2, '0');
        const minutos = String(ahora.getMinutes()).padStart(2, '0');
        const segundos = String(ahora.getSeconds()).padStart(2, '0');
        const horaActual = `${horas}:${minutos}:${segundos}`;

        // Concatenar con la fecha de entrada
        const fechaEntrada = `${inputFechaEntrada.value} ${horaActual}`;
        
        //mostrar alertas o alerta si faltan datos
        if(!inputNombreCliente.value.trim() || !inputApellidosCliente.value.trim() || !inputTelefonoCliente.value.trim()){
            mostrarAlerta('Faltan datos del Huesped', 'El nombre, apellidos y telefono son obligatorios', 'warning');
            return;
        }

        const params = new URLSearchParams(window.location.search);
        const idHabitacion = params.get("id");

        const reserva = {
            cliente: {
                correo : inputCorreoCliente.value.trim(),
                nombre : inputNombreCliente.value.trim(),
                apellidos : inputApellidosCliente.value.trim(),
                documento_identidad : inputDocumentoCliente.value.trim(),
                telefono : inputTelefonoCliente.value.trim(),
                direccion : inputDireccionCliente.value.trim()
            },
            fechas : {
                // entrada: `${inputFechaEntrada.value} 14:00:00`,
                entrada: fechaEntrada,
                salida: `${inputFechaSalida.value} 12:00:00`
            },
            habitaciones: [idHabitacion],
            pago: {
                totalPagar: parseFloat(totalPendiente),
                totalPagarOriginal: parseFloat(totalOriginal),
                descuento: parseFloat(descuento),
                tipoDescuento: tipoDescuento,
                cobroExtra: parseFloat(cobroExtra),
                adelanto: parseFloat(adelanto)
            },
            observaciones: observaciones,
            metodo_pago: metodoPago // METODO de PAGO va aquí si el backend lo espera dentro de pago
        };

        // ✅ NO sobreescribas el FormData
        const formData = new FormData();
        formData.append('reserva', JSON.stringify(reserva));
        
        // ✅ Enviar correctamente el FormData
        try {
            const respuesta = await fetch('/api/reservaciones', {
                method: 'POST',
                body: formData
            });
        
            if (!respuesta.ok) throw new Error('Error en la respuesta del servidor');
        
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo , '/admin/recepcion');
        } catch (error) {
            console.error('Error:', error);
            mostrarAlerta('Error', 'No se pudo enviar la reserva', 'error');
        }        
    })
}
