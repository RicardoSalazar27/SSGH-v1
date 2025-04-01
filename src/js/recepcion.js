if (window.location.pathname === '/admin/recepcion') {
    // Seleccionar todos los enlaces con la clase "small-box-footer"
    const habitaciones = document.querySelectorAll('.small-box-footer');

    habitaciones.forEach(habitacion => {
        // Obtener los valores de los atributos data
        const id = habitacion.getAttribute('data-id');
        const estado = habitacion.getAttribute('data-estado');

        // Si la habitación está en estado 3 o 6, mostrar alerta al hacer clic
        if (estado == 3 || estado == 6 || estado == 8) {
            habitacion.addEventListener('click', (event) => {
                event.preventDefault(); // Evita la redirección por defecto
                
                Swal.fire({
                    title: "¿Limpieza Terminada?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, confirmar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let nuevoEstado; // Declarar variable correctamente
                        
                        if (estado == 3) {
                            nuevoEstado = 2;
                        } else if (estado == 6) {
                            nuevoEstado = 1;
                        } else if( estado == 8){
                            nuevoEstado = 5;
                        }

                        if (nuevoEstado !== undefined) {
                            // Llamar a la función para actualizar el estado
                            actualizarEstadoHabitacion(id, nuevoEstado);
                        } else {
                            console.error("Error: nuevoEstado no está definido.");
                        }
                    }
                });
            });
        }
    });

    // Función para actualizar el estado de la habitación mediante PATCH
    function actualizarEstadoHabitacion(id, nuevoEstado) {
        fetch(`/api/habitaciones/${id}`, {
            method: "PATCH",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id_estado_habitacion: nuevoEstado
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            //console.log("Respuesta del servidor:", data);
            Swal.fire({
                title: "Habitación nuevamente disponible",
                icon: "success"
            }).then(() => {
                location.reload(); // Recargar la página para reflejar cambios
            });
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                title: "Error al actualizar",
                text: "No se pudo cambiar la habitación.",
                icon: "error"
            });
        });
    }
}