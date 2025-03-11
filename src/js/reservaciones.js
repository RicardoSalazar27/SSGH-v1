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

    const inputCorreo = document.getElementById('searchEmail');
    const listaSugerencias = document.getElementById('sugerenciasCorreo');

    let timeoutBusqueda; // Para evitar hacer demasiadas peticiones seguidas

    let clienteNuevo = {};  // Variable para almacenar los datos del nuevo cliente

    // Buscar clientes por correo en la API
    async function buscarClientes(correo) {
        try {
            // Modificar la URL para que coincida con el formato del endpoint
            const response = await fetch(`/api/clientes/correo/${encodeURIComponent(correo)}`);
            const clientes = await response.json();
            
            // Si no se encuentra ningún cliente, devolver un array vacío
            if (clientes.length === 0) {
                return []; // No hay clientes
            }
            console.log(clientes);
            return clientes; // Si hay clientes, devolver el array
        } catch (error) {
            console.error("Error al obtener clientes:", error);
            return [];
        }
    }

    // Evento al escribir en el input
    inputCorreo.addEventListener('input', function () {
        clearTimeout(timeoutBusqueda); // Limpiar timeout anterior

        const valor = inputCorreo.value.trim();
        if (valor.length < 3) { // No buscar si hay menos de 3 caracteres
            listaSugerencias.classList.add('d-none');
            return;
        }

        timeoutBusqueda = setTimeout(async () => { // Esperar antes de hacer la petición
            const clientes = await buscarClientes(valor);
            mostrarSugerencias(clientes);
        }, 300); // Retraso de 300ms para evitar sobrecarga en la API
    });

    // Función para mostrar sugerencias
    function mostrarSugerencias(clientes) {
        listaSugerencias.innerHTML = '';

        if (clientes.length === 0) {
            listaSugerencias.classList.add('d-none');
            return;
        }

        listaSugerencias.classList.remove('d-none');  // Asegurarse de que la lista esté visible

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

    // Función para llenar los campos cuando se selecciona un cliente
    function seleccionarCliente(cliente) {
        inputCorreo.value = cliente.correo;
        document.getElementById('nombre').value = cliente.nombre;
        document.getElementById('apellidos').value = cliente.apellidos;
        document.getElementById('documento_identidad').value = cliente.documento_identidad;
        document.getElementById('telefono').value = cliente.telefono;
        document.getElementById('direccion').value = cliente.direccion;

        listaSugerencias.classList.add('d-none');
    }

    // Función para almacenar los datos de un nuevo cliente si no se encontró
    function guardarClienteNuevo() {
        // Obtener los valores de los inputs
        clienteNuevo = {
            correo: inputCorreo.value.trim(),
            nombre: document.getElementById('nombre').value.trim(),
            apellidos: document.getElementById('apellidos').value.trim(),
            documento_identidad: document.getElementById('documento_identidad').value.trim(),
            telefono: document.getElementById('telefono').value.trim(),
            direccion: document.getElementById('direccion').value.trim()
        };
    }

   // Evento para el botón "Siguiente"
    const btnSiguiente = document.getElementById('btnSiguiente'); // Asegúrate de que este botón existe en tu HTML
    btnSiguiente.addEventListener('click', function() {
        // Si no se encontraron clientes, guardar los datos como cliente nuevo
        if (!inputCorreo.value.trim() || listaSugerencias.classList.contains('d-none')) {
            guardarClienteNuevo();
            console.log(clienteNuevo); // Aquí puedes hacer lo que necesites con el cliente nuevo
        }

        // Cambiar al paso 2
        document.getElementById('step1').classList.add('d-none');  // Ocultar el paso 1
        document.getElementById('step2').classList.remove('d-none');  // Mostrar el paso 2

        // Mostrar el botón "Atrás" y cambiar "Siguiente" a "Confirmar"
        document.getElementById('btnAtras').classList.remove('d-none');
        document.getElementById('btnSiguiente').classList.add('d-none');
        document.getElementById('btnConfirmar').classList.remove('d-none');
    });

    // Ocultar sugerencias si se hace clic fuera
    document.addEventListener('click', function (e) {
        if (!inputCorreo.contains(e.target) && !listaSugerencias.contains(e.target)) {
            listaSugerencias.classList.add('d-none');
        }
    });

}
