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


        calendar.on('eventClick', function(info) {
            var evento = info.event;
            console.log("ID del evento:", evento.id);
            console.log("Título:", evento.title);
            console.log("Fecha de inicio:", evento.start);
        });
        
    });
}
