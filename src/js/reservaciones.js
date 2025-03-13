if (window.location.pathname === '/admin/reservaciones') {
    document.addEventListener('DOMContentLoaded', function () {
        // Verificar que FullCalendar esté disponible
        var calendarEl = document.getElementById('calendar');
        
        // Inicializar Modal de Bootstrap
        var modalElement = document.getElementById('modalReservacion');
        var MyModal = new bootstrap.Modal(modalElement);

        // Inicializar FullCalendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'es',  // Establece el idioma a español
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            buttonText: {
                today: 'Hoy',
                prev: 'Anterior',
                next: 'Siguiente',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista',
            },
            allDayText: 'Todo el día',
             // **Aquí agregamos los eventos**
             events: [
            ],
            dateClick: function (info) {
                var startInput = document.getElementById('start');
                if (startInput) {
                    startInput.value = info.dateStr; // Asignar la fecha seleccionada al input
                } else {
                    console.error("El input con ID 'start' no se encontró.");
                }
                MyModal.show(); // Mostrar el modal
            }
        });

        // Renderizar el calendario
        calendar.render();

        // Funcionalidad para cerrar el modal mediante los botones
        var closeModalButtons = document.querySelectorAll('[data-close="modal"]'); // Seleccionar botones de cierre
        closeModalButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                MyModal.hide(); // Ocultar el modal
            });
        });

        // Cuando en vez de seleccionar por fecha, usas el boton para crear una nueva reservacion
        let btnNuevaReservacion = document.querySelector('#btnAgregarReservacion');
        btnNuevaReservacion.addEventListener('click', function() {
            MyModal.show(); // Mostrar el modal al hacer clic en el botón
        });
    });

    // Referencias a elementos del DOM
    const inputCorreo = document.getElementById('searchEmail');
    const listaSugerencias = document.getElementById('sugerenciasCorreo');
    const fechaEntrada = document.getElementById("fechaEntrada");
    const fechaSalida = document.getElementById("fechaSalida");
    const selectHabitacion = document.getElementById("habitacion");
    const btnSiguiente = document.getElementById('btnSiguiente'); // Botón Siguiente (cambia a Confirmar en el paso 3)
    const btnAtras = document.getElementById('btnAtras'); // Botón Atrás

    let timeoutBusqueda;
    let clienteNuevo = {};  
    let pasoActual = 1;  // Controla el paso en el que estamos

    // Inicializar Choices.js para el select de habitaciones
    const choices = new Choices(selectHabitacion, {
        removeItemButton: true,
        placeholder: true,
        placeholderValue: "Seleccione una o más habitaciones",
        searchEnabled: false, 
    });

    // Buscar clientes por correo en la API
    async function buscarClientes(correo) {
        try {
            const response = await fetch(`/api/clientes/correo/${encodeURIComponent(correo)}`);
            
            if (!response.ok) {
                return []; // Si el cliente no existe (404), retorna un array vacío
            }

            const clientes = await response.json();
            return clientes.length ? clientes : [];
        } catch (error) {
            console.error("Error al obtener clientes:", error);
            return [];
        }
    }

    // Evento al escribir en el input de correo
    inputCorreo.addEventListener('input', function () {
        clearTimeout(timeoutBusqueda);
        const valor = inputCorreo.value.trim();

        if (valor.length < 3) {
            listaSugerencias.classList.add('d-none');
            return;
        }

        timeoutBusqueda = setTimeout(async () => {
            const clientes = await buscarClientes(valor);
            if (clientes.length === 0) {
                clienteNuevo = {}; // Limpiar datos previos si no hay coincidencias
            }
            mostrarSugerencias(clientes);
        }, 300);
    });

    // Mostrar sugerencias de clientes
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

            item.addEventListener('click', function () {
                seleccionarCliente(cliente);
            });

            listaSugerencias.appendChild(item);
        });
    }

    // Llenar los campos cuando se selecciona un cliente existente
    function seleccionarCliente(cliente) {
        inputCorreo.value = cliente.correo;
        document.getElementById('nombre').value = cliente.nombre;
        document.getElementById('apellidos').value = cliente.apellidos;
        document.getElementById('documento_identidad').value = cliente.documento_identidad;
        document.getElementById('telefono').value = cliente.telefono;
        document.getElementById('direccion').value = cliente.direccion;

        listaSugerencias.classList.add('d-none');
    }

    // Guardar los datos de un cliente nuevo
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

    // Avanzar de paso en el wizard
    btnSiguiente.addEventListener('click', function() {
        if (pasoActual === 1) {
            if (!inputCorreo.value.trim()) {
                alert("Por favor, ingrese un correo.");
                return;
            }

            const clienteYaSeleccionado = document.getElementById('nombre').value.trim();
            if (!clienteYaSeleccionado) {
                guardarClienteNuevo();
                console.log("Nuevo cliente guardado:", clienteNuevo);
            }

            document.getElementById('step1').classList.add('d-none');
            document.getElementById('step2').classList.remove('d-none');
            document.getElementById('btnAtras').classList.remove('d-none'); 
            pasoActual = 2;
            btnSiguiente.textContent = "Siguiente";
        } else if (pasoActual === 2) {
            const habitacionesSeleccionadas = choices.getValue(true);
            if (habitacionesSeleccionadas.length === 0) {
                alert("Por favor, seleccione una habitación.");
                return;
            }

            document.getElementById('step2').classList.add('d-none');
            document.getElementById('step3').classList.remove('d-none');
            btnSiguiente.textContent = "Confirmar";
            pasoActual = 3;
        } else if (pasoActual === 3) {
            const datosReserva = {
                cliente: clienteNuevo.correo ? clienteNuevo : {
                    correo: inputCorreo.value,
                    nombre: document.getElementById('nombre').value,
                    apellidos: document.getElementById('apellidos').value,
                    documento_identidad: document.getElementById('documento_identidad').value,
                    telefono: document.getElementById('telefono').value,
                    direccion: document.getElementById('direccion').value,
                },
                fechaEntrada: fechaEntrada.value,
                fechaSalida: fechaSalida.value,
                habitaciones: choices.getValue(true)
            };

            console.log("Datos de la reserva:", datosReserva);
            alert("Reserva confirmada!");
        }
    });

    // Cargar habitaciones disponibles al cambiar las fechas
    async function cargarHabitaciones() {
        const inicio = fechaEntrada.value;
        const fin = fechaSalida.value;

        if (!inicio || !fin) return;

        try {
            const response = await fetch(`/api/habitaciones/disponibles/${inicio}/${fin}`);
            const habitaciones = await response.json();

            choices.clearChoices();

            if (habitaciones.length === 0) {
                choices.setChoices([{ value: "", label: "No hay habitaciones disponibles", disabled: true }]);
                return;
            }

            const opciones = habitaciones.map(habitacion => ({
                value: habitacion.id,
                label: `Habitación ${habitacion.numero} | ${habitacion.id_categoria.nombre} | Capacidad max. ${habitacion.id_categoria.capacidad_maxima} personas | $${habitacion.id_categoria.precio_base} MXN`
            }));
            choices.setChoices(opciones);
        } catch (error) {
            console.error("Error al obtener habitaciones:", error);
        }
    }

    // Eventos para actualizar habitaciones al cambiar fechas
    fechaEntrada.addEventListener("change", cargarHabitaciones);
    fechaSalida.addEventListener("change", cargarHabitaciones);

    // Cerrar sugerencias al hacer clic fuera del input
    document.addEventListener('click', function (e) {
        if (!inputCorreo.contains(e.target) && !listaSugerencias.contains(e.target)) {
            listaSugerencias.classList.add('d-none');
        }
    });

    // Retroceder en el wizard
    btnAtras.addEventListener('click', function() {
        if (pasoActual === 2) {
            document.getElementById('step2').classList.add('d-none');
            document.getElementById('step1').classList.remove('d-none');
            document.getElementById('btnAtras').classList.add('d-none');
            pasoActual = 1;
        } else if (pasoActual === 3) {
            document.getElementById('step3').classList.add('d-none');
            document.getElementById('step2').classList.remove('d-none');
            btnSiguiente.textContent = "Siguiente";
            pasoActual = 2;
        }
    });
}
