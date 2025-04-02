if (window.location.pathname === "/admin/recepcion/habitacion") {

    // Elementos del DOM
    const listaSugerencias = document.getElementById("sugerenciaCorreo"); // ID corregido
    const inputCorreoCliente = document.getElementById("correo");
    const inputNombreCliente = document.getElementById("nombre");
    const inputDocumentoCliente = document.getElementById("documento");
    const inputTelefonoCliente = document.getElementById("telefono");
    const inputDireccionCliente = document.getElementById("direccion");

    const inputFechaEntrada = document.getElementById("fechaEntrada");
    const inputFechaSalida = document.getElementById("fechaSalida");

    const inputTipoDescuento = document.getElementById("tipoDescuento");
    const inputDescuento = document.getElementById("descuento");
    const inputCobroExtra = document.getElementById("cobroExtra");
    const inputAdelanto = document.getElementById("adelanto");
    const inputTotalPagar = document.getElementById("totalPagar");

    let totalOriginal = 0; //se obtiene al multiplicar el costo de la habitacion por noches 
    let totalPendiente = 0; // despues de aplicarle descuento, cobro extra y adelanto

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

    // // Definir la fecha máxima permitida (3 de abril de 2025)
    // const fechaMaxima = "2025-04-03";  

    // // Bloquear todas las fechas posteriores al 3 de abril de 2025
    // inputFechaSalida.setAttribute("max", fechaMaxima);
}
