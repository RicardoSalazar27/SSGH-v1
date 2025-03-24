if (window.location.pathname === '/admin/reservaciones') {
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var modalElement = document.getElementById('modalReservacion');
        var MyModal = new bootstrap.Modal(modalElement);

        // Configuración del calendario
        var calendar = new FullCalendar.Calendar(calendarEl, {
            displayEventTime: false,
            locale: 'es',
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
            events: [],
            dateClick: dateClickHandler
        });

        // Inicializar el calendario
        calendar.render();
        setupEventListeners();
        loadReservations();

        // Variables y modal para edición
        const modalEditarReservacion = document.getElementById('modalEditar');
        const MyModalEditarReserva = new bootstrap.Modal(modalEditarReservacion);
        let reservacionOriginal;
        let choices = null;

        // Configuración de eventos
        calendar.on('eventClick', async function(info) {
            await handleEventClick(info, MyModalEditarReserva);
        });

        // Manejador de clic en la fecha del calendario
        function dateClickHandler(info) {
            var startInput = document.getElementById('start');
            if (startInput) {
                startInput.value = info.dateStr;
            } else {
                console.error("El input con ID 'start' no se encontró.");
            }
            MyModal.show();
        }

        // Configuración de event listeners
        function setupEventListeners() {
            let btnNuevaReservacion = document.querySelector('#btnAgregarReservacion');
            btnNuevaReservacion.addEventListener('click', function() {
                MyModal.show();
            });
        }

        // Cargar reservas desde el API
        function loadReservations() {
            fetch('http://localhost:3000/api/reservaciones')
                .then(response => response.json())
                .then(data => {
                    data.forEach(reservacion => {
                        const evento = {
                            id: reservacion.ID_reserva,
                            title: `${reservacion.habitaciones} | ${reservacion.cliente_nombre}`,
                            start: reservacion.fecha_entrada,
                            end: reservacion.fecha_salida,
                            description: reservacion.estado_descripcion,
                            allDay: false,
                            color: reservacion.estado_color
                        };
                        calendar.addEvent(evento);
                    });
                })
                .catch(error => console.error('Error al obtener las reservaciones:', error));
        }

        // Manejar clic en evento del calendario
        async function handleEventClick(info, modal) {
            const evento = info.event;
            const idEvento = evento.id;
        
            try {
                const respuesta = await fetch(`/api/reservaciones/${idEvento}`);
                if (!respuesta.ok) {
                    throw new Error(`Error al obtener la reservación: ${respuesta.statusText}`);
                }
                const reservacion = await respuesta.json();
                reservacionOriginal = { ...reservacion };
                populateEditForm(reservacion);
        
                const fechaEntradaEditar = document.getElementById('fechaEntradaEditar');
                const fechaSalidaEditar = document.getElementById('fechaSalidaEditar');
        
                const changeHandler = () => {
                    cargarHabitacionesDisponibles(fechaEntradaEditar.value, fechaSalidaEditar.value, reservacionOriginal.ID_habitacion);
                };
        
                fechaEntradaEditar.removeEventListener('change', changeHandler);
                fechaSalidaEditar.removeEventListener('change', changeHandler);
        
                fechaEntradaEditar.addEventListener('change', changeHandler);
                fechaSalidaEditar.addEventListener('change', changeHandler);
        
                modal.show();
        
            } catch (error) {
                console.error('Error al obtener los datos de la reservación:', error);
            }
        }        

        // Población de formulario de edición
        function populateEditForm(reservacion) {
            document.getElementById('nombreEditar').value = reservacion.cliente_nombre;
            document.getElementById('searchEmailEditar').value = reservacion.correo;
            document.getElementById('apellidosEditar').value = reservacion.cliente_apellidos;
            document.getElementById('documento_identidadEditar').value = reservacion.documento_identidad;
            document.getElementById('telefonoEditar').value = reservacion.telefono;
            document.getElementById('direccionEditar').value = reservacion.direccion;
            document.getElementById('observacionesEditar').value = reservacion.observaciones;

            let fechaEntrada = reservacion.fecha_entrada.split(' ')[0];
            let fechaSalida = reservacion.fecha_salida.split(' ')[0];
            document.getElementById('fechaEntradaEditar').value = fechaEntrada;
            document.getElementById('fechaSalidaEditar').value = fechaSalida;

            // Agregar aquí la llamada a cargarHabitacionesDisponibles
            cargarHabitacionesDisponibles(fechaEntrada, fechaSalida, reservacion.ID_habitacion);
            
            // Rellenar campos de pago
            document.getElementById('adelantoEditar').value = reservacion.adelanto;
            document.getElementById('cobroExtraEditar').value = reservacion.cobro_extra;

            if (reservacion.tipo_descuento === "PORCENTAJE") {
                let porcentajeDescuento = (reservacion.descuento_aplicado / reservacion.precio_total) * 100;
                document.getElementById('descuentoEditar').value = porcentajeDescuento.toFixed(2);
                document.getElementById('descuentoPorcentajeEditar').checked = true;
                document.getElementById('descuentoMontoEditar').checked = false;
            } else {
                document.getElementById('descuentoEditar').value = reservacion.descuento_aplicado;
                document.getElementById('descuentoMontoEditar').checked = true;
                document.getElementById('descuentoPorcentajeEditar').checked = false;
            }

            document.getElementById('metodoPagoEditar').value = reservacion.metodo_pago;
            document.getElementById('totalPagarEditar').value = reservacion.precio_pendiente;
        }

        // Cargar habitaciones disponibles
        async function cargarHabitacionesDisponibles(fechaEntrada, fechaSalida, habitacionesSeleccionadasIds) {
            const selectHabitacion = document.getElementById('habitacionEditar');
        
            try {
                const response = await fetch(`/api/habitaciones/disponibles/${fechaEntrada}/${fechaSalida}`);
                let habitacionesDisponibles = await response.json();
        
                const habitacionesSeleccionadas = habitacionesSeleccionadasIds ? habitacionesSeleccionadasIds.split(',').map(id => id.trim()) : [];
        
                const responseHabitaciones = await fetch('/api/habitaciones');
                const todasHabitaciones = await responseHabitaciones.json();
        
                // Usar un conjunto para eliminar duplicados
                const habitacionesCompletasSeleccionadas = todasHabitaciones.filter(habitacion => habitacionesSeleccionadas.includes(habitacion.id.toString()));
                habitacionesDisponibles = habitacionesDisponibles.filter(h => !habitacionesSeleccionadas.includes(h.id.toString()));
        
                if (!choices) {
                    choices = new Choices(selectHabitacion, {
                        removeItemButton: true,
                        placeholder: true,
                        placeholderValue: "Seleccione una o más habitaciones",
                        searchEnabled: false,
                    });
                }
        
                choices.clearChoices();
        
                let opciones = new Set();  // Usar un Set para evitar duplicados
                habitacionesCompletasSeleccionadas.forEach(habitacion => {
                    opciones.add({
                        value: habitacion.id,
                        label: `Habitación ${habitacion.numero} | ${habitacion.id_categoria.nombre} | ${habitacion.id_categoria.tipo_cama} | Capacidad max. ${habitacion.id_categoria.capacidad_maxima} personas | $${habitacion.id_categoria.precio_base} MXN`,
                        selected: true
                    });
                });
        
                habitacionesDisponibles.forEach(habitacion => {
                    opciones.add({
                        value: habitacion.id,
                        label: `Habitación ${habitacion.numero} | ${habitacion.id_categoria.nombre} | ${habitacion.id_categoria.tipo_cama} | Capacidad max. ${habitacion.id_categoria.capacidad_maxima} personas | $${habitacion.id_categoria.precio_base} MXN`
                    });
                });
        
                choices.setChoices(Array.from(opciones));  // Convertir el Set a Array
            } catch (error) {
                console.error('Error al obtener habitaciones disponibles:', error);
            }
        }        
    });
}
