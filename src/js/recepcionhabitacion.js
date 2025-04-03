if (window.location.pathname === "/admin/recepcion/habitacion") {

    // Elementos del DOM
    const listaSugerencias = document.getElementById("sugerenciaCorreo"); // ID corregido
    const inputCorreoCliente = document.getElementById("correo");
    const inputNombreCliente = document.getElementById("nombre");
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

    // Obtener precio base de la habitación
    let precioHabitacion = parseFloat(document.getElementById("precio_habitacion").textContent.trim()) || 0;
    let noches = 1;
    let totalOriginal = 0; //se obtiene al multiplicar el costo de la habitacion por noches 
    let totalPendiente = 0; // despues de aplicarle descuento, cobro extra y adelanto
    
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
        inputNombreCliente.value = cliente.nombre + " " + cliente.apellidos;
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

    inputFechaSalida.addEventListener("change", () => {
        calcularNoches();
        calcularTotalPagar();
    });
    
    function calcularNoches() {
        const fechaEntrada = new Date(inputFechaEntrada.value);
        const fechaSalida = new Date(inputFechaSalida.value);
    
        if (isNaN(fechaEntrada) || isNaN(fechaSalida)) {
            console.log("Fechas inválidas");
            return;
        }
    
        // Calcular la diferencia en días
        noches = (fechaSalida - fechaEntrada) / (1000 * 60 * 60 * 24);
    }

    //llenar total pendiente en base a las noches
    totalOriginal = noches * precioHabitacion;
    totalPendiente = noches * precioHabitacion;
    inputTotalPagar.value = totalPendiente;

    function calcularTotalPagar(){
        totalOriginal = noches * precioHabitacion;
        totalPendiente = noches * precioHabitacion - descuento + cobroExtra - adelanto;
        inputTotalPagar.value = totalPendiente;
    }

    //totalOriginal = noches * precioHabitacion
    //descuento = inputDescuento
    //tipoDescuento = inputTipoDescuento
    //cobroExtra = inputCobroExtra
    //adelanto = inputAdelanto
    //totalPendiente = totalOriginal - descuento + cobroExtra - adelanto

    //si el inputradio tipoDescuento = PORCENTAJE
    //entomces totalPendiente = totalOriginal-(totalOriginal*descuento)

    //si es MONTO ENTONCES:
    //totalPendinte = totalOriginal-descuento
}
