if (window.location.pathname === '/admin/reservaciones') {
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var modalElement = document.getElementById('modalReservacion');
        var MyModal = new bootstrap.Modal(modalElement);
        // Definir todasHabitaciones globalmente
        let todasHabitaciones = [];
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
        let totalPriceBase = 0;

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

            // Antes de mostrar el modal, asegúrate de limpiar previamente las selecciones de habitaciones
            if (choices) {
                choices.clearChoices();
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
                await cargarHabitacionesDisponibles(reservacion.fecha_entrada.split(' ')[0], reservacion.fecha_salida.split(' ')[0], reservacion.ID_habitacion);

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
                todasHabitaciones = await responseHabitaciones.json();

                const habitacionesCompletasSeleccionadas = todasHabitaciones.filter(habitacion => habitacionesSeleccionadas.includes(habitacion.id.toString()));
                habitacionesDisponibles = habitacionesDisponibles.filter(h => !habitacionesSeleccionadas.includes(h.id.toString()));

                // Inicializa Choices solo si aún no está inicializado
                if (!choices) {
                    choices = new Choices(selectHabitacion, {
                        removeItemButton: true,
                        placeholder: true,
                        placeholderValue: "Seleccione una o más habitaciones",
                        searchEnabled: false,
                    });
                }

                // Elimina las elecciones anteriores antes de agregar nuevas para evitar duplicados
                choices.clearChoices();

                let opciones = [];

                // Mantén las habitaciones seleccionadas
                habitacionesCompletasSeleccionadas.forEach(habitacion => {
                    opciones.push({
                        value: habitacion.id,
                        label: `Habitación ${habitacion.numero} | ${habitacion.id_categoria.nombre} | Capacidad max. ${habitacion.id_categoria.capacidad_maxima} personas | $${habitacion.id_categoria.precio_base} MXN`,
                        selected: true  // Marcar como seleccionada
                    });
                });

                // Agrega habitaciones disponibles
                habitacionesDisponibles.forEach(habitacion => {
                    opciones.push({
                        value: habitacion.id,
                        label: `Habitación ${habitacion.numero} | ${habitacion.id_categoria.nombre} | Capacidad max. ${habitacion.id_categoria.capacidad_maxima} personas | $${habitacion.id_categoria.precio_base} MXN`
                    });
                });
                // Elimina todas las opciones antes de agregar las nuevas
                choices.clearStore();

                // Establece las nuevas opciones
                choices.setChoices(opciones);

                // Calcular precio total cuando cambien las habitaciones seleccionadas o las fechas
                calculateTotalPrice();

                // Obtener habitaciones seleccionadas desde Choices
                document.getElementById('habitacionEditar').addEventListener('change', function() {
                    let habitacionesSeleccionadas = choices.getValue(true);
                    //console.log('Habitaciones seleccionadas:', habitacionesSeleccionadas);
                    calculateTotalPrice();
                });

                // Detectar cambio de fechas
                document.getElementById('fechaEntradaEditar').addEventListener('change', function() {
                    calculateTotalPrice();
                });

                document.getElementById('fechaSalidaEditar').addEventListener('change', function() {
                    calculateTotalPrice();
                });

            } catch (error) {
                console.error('Error al obtener habitaciones disponibles:', error);
            }
        }

        // Calcular precio total
        function calculateTotalPrice() {
            const habitacionesSeleccionadas = choices.getValue(true);
            const fechaEntrada = document.getElementById('fechaEntradaEditar').value;
            const fechaSalida = document.getElementById('fechaSalidaEditar').value;

            if (!fechaEntrada || !fechaSalida || habitacionesSeleccionadas.length === 0) {
                return;
            }

            // Calcular la cantidad de noches
            const fechaEntradaObj = new Date(fechaEntrada);
            const fechaSalidaObj = new Date(fechaSalida);
            const diferenciaEnTiempo = fechaSalidaObj - fechaEntradaObj;
            const noches = diferenciaEnTiempo / (1000 * 3600 * 24); // Convertir a días

            let totalPrice = 0;

            // Obtener los precios de las habitaciones seleccionadas
            habitacionesSeleccionadas.forEach(id => {
                const habitacion = todasHabitaciones.find(h => h.id.toString() === id);
                if (habitacion) {
                    totalPrice += habitacion.id_categoria.precio_base * noches;
                }
            });
            totalPriceBase = totalPrice;
            // Ahora, ajustamos el total con base en los inputs de pago

            // Obtener valores de inputs de pago
            const adelanto = parseFloat(document.getElementById('adelantoEditar').value) || 0;
            const cobroExtra = parseFloat(document.getElementById('cobroExtraEditar').value) || 0;

            const descuento = parseFloat(document.getElementById('descuentoEditar').value) || 0;
            const descuentoPorcentaje = document.getElementById('descuentoPorcentajeEditar').checked;

            // Si el descuento es por porcentaje, aplicamos el porcentaje
            if (descuentoPorcentaje) {
                totalPrice -= (totalPrice * (descuento / 100));
            } else {
                totalPrice -= descuento;
            }

            // Sumamos el cobro extra y restamos el adelanto
            totalPrice += cobroExtra;
            totalPrice -= adelanto;

            // Mostrar el precio final
            console.log(`Precio total: $${totalPrice.toFixed(2)} MXN`);
            const totalPagarInput = document.getElementById('totalPagarEditar');
            totalPagarInput.value = totalPrice.toFixed(2);
        }

        // Función para actualizar el precio cada vez que se cambien los campos de pago
        function setupEventListenersForPriceUpdates() {
            // Escuchar cambios en los inputs de fecha, adelanto, cobro extra y descuento
            document.getElementById('fechaEntradaEditar').addEventListener('change', calculateTotalPrice);
            document.getElementById('fechaSalidaEditar').addEventListener('change', calculateTotalPrice);
            document.getElementById('adelantoEditar').addEventListener('input', calculateTotalPrice);
            document.getElementById('cobroExtraEditar').addEventListener('input', calculateTotalPrice);
            document.getElementById('descuentoEditar').addEventListener('input', calculateTotalPrice);
            document.getElementById('descuentoPorcentajeEditar').addEventListener('change', calculateTotalPrice);
            document.getElementById('descuentoMontoEditar').addEventListener('change', calculateTotalPrice);
        }

        // Llamamos la función de setup para los listeners
        setupEventListenersForPriceUpdates();

        document.getElementById('btnEditar').addEventListener('click', async function (e) {
            e.preventDefault();
            console.log('Diste click en editar');
        
            const reservacionId = document.getElementById('idReservacion').value;
        
            const reservacionActualizada = {
                cliente_nombre: document.getElementById('nombreEditar').value.trim(),
                cliente_apellidos: document.getElementById('apellidosEditar').value.trim(),
                correo: document.getElementById('searchEmailEditar').value.trim(),
                documento_identidad: document.getElementById('documento_identidadEditar').value.trim(),
                telefono: document.getElementById('telefonoEditar').value.trim(),
                direccion: document.getElementById('direccionEditar').value.trim(),
                fecha_entrada: document.getElementById('fechaEntradaEditar').value.trim(),
                fecha_salida: document.getElementById('fechaSalidaEditar').value.trim(),
                observaciones: document.getElementById('observacionesEditar').value.trim(),
                adelanto: parseFloat(document.getElementById('adelantoEditar').value.trim()) || 0,
                cobro_extra: parseFloat(document.getElementById('cobroExtraEditar').value.trim()) || 0,
                descuento_aplicado: parseFloat(document.getElementById('descuentoEditar').value.trim()) || 0,
                tipo_descuento: document.getElementById('descuentoPorcentajeEditar').checked ? 'PORCENTAJE' : 'MONTO',
                metodo_pago: document.getElementById('metodoPagoEditar').value.trim(),
                habitaciones: choices.getValue(true), // Suponiendo que choices está correctamente inicializado
                totalPagar: parseFloat(document.getElementById('totalPagarEditar').value.trim()) || 0,
                totalBase: totalPriceBase
            };
        
            if (!reservacionOriginal) {
                console.error('Error: No hay datos originales de la reservación');
                mostrarAlerta('Error', 'No se pudieron comparar los datos originales', 'error');
                return;
            }
        
            console.log('Datos actualizados:', reservacionActualizada);
            console.log('Datos originales:', reservacionOriginal);
            return;
        
            // Comparar los datos originales con los nuevos para detectar cambios
            let cambios = {};
            for (let key in reservacionActualizada) {
                // Convertir a string para evitar diferencias por tipos de datos
                if (JSON.stringify(reservacionActualizada[key]) !== JSON.stringify(reservacionOriginal[key])) {
                    cambios[key] = reservacionActualizada[key];
                }
            }
        
            // Si no hay cambios, no enviamos la petición
            if (Object.keys(cambios).length === 0) {
                mostrarAlerta('No hay cambios por enviar', 'error');
                return;
            }
        
            // Determinar si usar PUT o PATCH
            const metodo = Object.keys(cambios).length === Object.keys(reservacionActualizada).length ? "PUT" : "PATCH";
            const datos = metodo === "PUT" ? reservacionActualizada : cambios;
        
            try {
                // Mostrar spinner de carga
                document.getElementById('loadingSpinner').classList.remove('d-none');
        
                const respuestaUpdate = await fetch(`/api/reservaciones/${reservacionId}`, {
                    method: metodo,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(datos),
                });
        
                // Ocultar spinner
                document.getElementById('loadingSpinner').classList.add('d-none');
        
                if (!respuestaUpdate.ok) {
                    const errorData = await respuestaUpdate.json();
                    throw new Error(errorData.mensaje || 'Error desconocido al actualizar');
                }
        
                const resultado = await respuestaUpdate.json();
                mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo);
        
                // Cerrar modal y actualizar datos si es necesario
                $('#modalEditar').modal('hide');
                initDataTable(); // Suponiendo que esta función recarga la tabla de datos
        
            } catch (error) {
                console.error('Error al actualizar la reservación:', error);
                mostrarAlerta('Error', error.message, 'error');
            }
        });        
        
    });
}
