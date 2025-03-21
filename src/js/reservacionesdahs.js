if (window.location.pathname === '/admin/reservaciones') {
    document.addEventListener('DOMContentLoaded', function () {
        // Verificar que FullCalendar esté disponible
        var calendarEl = document.getElementById('calendar');
        
        // Inicializar Modal de Bootstrap
        var modalElement = document.getElementById('modalReservacion');
        var MyModal = new bootstrap.Modal(modalElement);

        // Inicializar FullCalendar
        var calendar = new FullCalendar.Calendar(calendarEl, {
            displayEventTime: false,
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
            events: [],  // Aquí agregas tus eventos
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

        // Cerrar modal manualmente si el botón de cerrar no funciona
        var closeButton = document.querySelector('.btn-close');
        if (closeButton) {
            closeButton.addEventListener('click', function () {
                MyModal.hide(); // Esto se asegura de cerrar el modal si el atributo `data-bs-dismiss` no funciona.
            });
        }

        // Obtener las reservaciones desde el endpoint
        fetch('http://localhost:3000/api/reservaciones')
        .then(response => response.json())
        .then(data => {
            data.forEach(reservacion => {
                // Crear el evento con la información proporcionada por la API
                const evento = {
                    id: reservacion.ID_reserva,
                    title: `${reservacion.habitaciones} | ${reservacion.cliente_nombre}`, // Usa directamente el valor de habitaciones
                    start: reservacion.fecha_entrada, // Fecha y hora exactas de entrada
                    end: reservacion.fecha_salida, // Fecha y hora exactas de salida
                    description: reservacion.estado_descripcion, // Descripción del estado
                    allDay: false,
                    color: reservacion.estado_color // Color según el estado
                };

                // Agregar el evento al calendario
                calendar.addEvent(evento);
            });
        })
        .catch(error => console.error('Error al obtener las reservaciones:', error));


        const modalEditarReservacion = document.getElementById('modalEditar');
        const MyModalEditarReserva = new bootstrap.Modal(modalEditarReservacion);

        let reservacionOriginal = null;  // Variable global para la reserva original
        let choices = null;  // Guardar instancia de Choices.js
        
        calendar.on('eventClick', async function(info) {
            const evento = info.event;
            const idEvento = evento.id;
        
            try {
                // Obtener los datos de la reservación desde la API
                const respuesta = await fetch(`/api/reservaciones/${idEvento}`);
                if (!respuesta.ok) {
                    throw new Error(`Error al obtener la reservación: ${respuesta.statusText}`);
                }
                const reservacion = await respuesta.json();
                reservacionOriginal = { ...reservacion };  // Guardar en variable global
        
                // Llenar los campos del modal con los datos de la reservación
                document.getElementById('nombreEditar').value = reservacion.cliente_nombre;
                document.getElementById('searchEmailEditar').value = reservacion.correo;
                document.getElementById('apellidosEditar').value = reservacion.cliente_apellidos;
                document.getElementById('documento_identidadEditar').value = reservacion.documento_identidad;
                document.getElementById('telefonoEditar').value = reservacion.telefono;
                document.getElementById('direccionEditar').value = reservacion.direccion;
                document.getElementById('observacionesEditar').value = reservacion.observaciones;
        
                // Extraer solo la fecha (YYYY-MM-DD) de la fecha de entrada y salida
                let fechaEntrada = reservacion.fecha_entrada.split(' ')[0];
                let fechaSalida = reservacion.fecha_salida.split(' ')[0];
        
                // Asignar solo la fecha (sin hora) a los campos de fecha
                document.getElementById('fechaEntradaEditar').value = fechaEntrada;
                document.getElementById('fechaSalidaEditar').value = fechaSalida;
        
                // Llamada para cargar las habitaciones disponibles y seleccionadas
                await cargarHabitacionesDisponibles(fechaEntrada, fechaSalida, reservacion.ID_habitacion);
        
                const $totalOriginal = reservacion.precio_total;
        
                // Asignar valores para los costos
                document.getElementById('totalPagarEditar').value = reservacion.precio_pendiente;
        
                // Llenar los campos de pagos
                document.getElementById('adelantoEditar').value = reservacion.adelanto;
                document.getElementById('cobroExtraEditar').value = reservacion.cobro_extra;
        
                // Manejo del descuento
                if (reservacion.tipo_descuento === "PORCENTAJE") {
                    // Calcular el porcentaje de descuento
                    let porcentajeDescuento = (reservacion.descuento_aplicado / reservacion.precio_total) * 100;

                    // Asignar el porcentaje calculado al campo de descuento
                    document.getElementById('descuentoEditar').value = porcentajeDescuento.toFixed(2);  // Mostrar con dos decimales

                    // Marcar el checkbox de porcentaje y desmarcar el de monto
                    document.getElementById('descuentoPorcentajeEditar').checked = true;
                    document.getElementById('descuentoMontoEditar').checked = false;
                } else {
                    // Si el descuento es en monto, asignar el valor directamente
                    document.getElementById('descuentoEditar').value = reservacion.descuento_aplicado;

                    // Marcar el checkbox de monto y desmarcar el de porcentaje
                    document.getElementById('descuentoMontoEditar').checked = true;
                    document.getElementById('descuentoPorcentajeEditar').checked = false;
                }


                // Llenar el método de pago
                document.getElementById('metodoPagoEditar').value = reservacion.metodo_pago; // Asegúrate de que esto sea el campo correcto.
        
                MyModalEditarReserva.show();
            } catch (error) {
                console.error('Error al obtener los datos de la reservación:', error);
            }
        });
                
        // Función para cargar habitaciones disponibles
        async function cargarHabitacionesDisponibles(fechaEntrada, fechaSalida, habitacionesSeleccionadasIds) {
        const selectHabitacion = document.getElementById('habitacionEditar');

        try {
            // Obtener las habitaciones disponibles en el rango de fechas
            const response = await fetch(`/api/habitaciones/disponibles/${fechaEntrada}/${fechaSalida}`);
            let habitacionesDisponibles = await response.json();
            
            // Convertir IDs seleccionados en un array
            const habitacionesSeleccionadas = habitacionesSeleccionadasIds ? habitacionesSeleccionadasIds.split(',').map(id => id.trim()) : [];
            
            // Obtener información completa de las habitaciones seleccionadas
            const responseHabitaciones = await fetch('/api/habitaciones');
            const todasHabitaciones = await responseHabitaciones.json();
            
            // Filtrar las habitaciones seleccionadas en base a los IDs
            const habitacionesCompletasSeleccionadas = todasHabitaciones.filter(habitacion => habitacionesSeleccionadas.includes(habitacion.id.toString()));
            
            // Excluir habitaciones seleccionadas de la lista de disponibles
            habitacionesDisponibles = habitacionesDisponibles.filter(h => !habitacionesSeleccionadas.includes(h.id.toString()));

            // Inicializar Choices.js si aún no está creado
            if (!choices) {
                choices = new Choices(selectHabitacion, {
                    removeItemButton: true,
                    placeholder: true,
                    placeholderValue: "Seleccione una o más habitaciones",
                    searchEnabled: false,
                });
            }
            
            choices.clearChoices();
            
            // Si no hay habitaciones disponibles y ninguna seleccionada, mostrar mensaje
            if (habitacionesDisponibles.length === 0 && habitacionesSeleccionadas.length === 0) {
                choices.setChoices([{ value: "", label: "No hay habitaciones disponibles", disabled: true }]);
                return;
            }
            
            let opciones = [];
            
            // Agregar habitaciones seleccionadas primero (aunque no estén disponibles)
            habitacionesCompletasSeleccionadas.forEach(habitacion => {
                opciones.push({
                    value: habitacion.id,
                    label: `Habitación ${habitacion.numero} | ${habitacion.id_categoria.nombre} | ${habitacion.id_categoria.tipo_cama} | Capacidad max. ${habitacion.id_categoria.capacidad_maxima} personas | $${habitacion.id_categoria.precio_base} MXN`,
                    selected: true
                });
            });
            
            // Agregar habitaciones disponibles
            habitacionesDisponibles.forEach(habitacion => {
                opciones.push({
                    value: habitacion.id,
                    label: `Habitación ${habitacion.numero} | ${habitacion.id_categoria.nombre} | ${habitacion.id_categoria.tipo_cama} | Capacidad max. ${habitacion.id_categoria.capacidad_maxima} personas | $${habitacion.id_categoria.precio_base} MXN`
                });
            });
            
            // Llenar el select con las opciones
            choices.setChoices(opciones);
            
        } catch (error) {
            console.error('Error al obtener habitaciones disponibles:', error);
        }
        }
        
    });
}
