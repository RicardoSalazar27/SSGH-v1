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
                // Iterar sobre cada reservación y agregarla al calendario
                data.forEach(reservacion => {
                    // Formatear el título del evento con las habitaciones y el nombre del cliente
                    const habitaciones = reservacion.habitaciones.split(',').map((habitacion, index) => {
                        const tipoCama = habitacionesInfo[parseInt(reservacion.ID_habitacion.split(',')[index])] || 'Desconocido';
                        return `${habitacion.trim()} - ${tipoCama}`;
                    }).join(', ');

                    const evento = {
                        title: `${habitaciones} | ${reservacion.cliente_nombre}`, // Título del evento con habitaciones y nombre del cliente
                        start: `${reservacion.fecha_entrada}T09:00:00`, // Fecha de entrada
                        end: `${reservacion.fecha_salida}T11:00:00`, // Fecha de salida
                        description: reservacion.estado_descripcion, // Descripción del estado
                        allDay: false,
                        color: reservacion.estado_color // Color según el estado
                    };

                    // Agregar el evento al calendario
                    calendar.addEvent(evento);
                });
            })
            .catch(error => console.error('Error al obtener las reservaciones:', error));
        
        // Aquí puedes agregar una variable `habitacionesInfo` que contenga el tipo de cama para cada habitación
        const habitacionesInfo = {
            1: 'Individual',
            5: 'Individual Doble',
            6: 'Matrimonial',
            13: 'Matrimonial Doble',
            // Agrega el resto de habitaciones según los datos de tu base de datos
        };
        
    });
}
