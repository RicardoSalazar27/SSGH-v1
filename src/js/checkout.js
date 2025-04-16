if (window.location.pathname === '/admin/salidas/checkout') {

    const params = new URLSearchParams(window.location.search);
    const idReserva = params.get("id");

    // Elementos del DOM
    const inputPenalidad = document.getElementById('inputPenalidad');
    const totalPagarElement = document.getElementById('totalPagar');
    const metodoPagoSelect = document.getElementById('metodoPago');
    const btnTerminarReservacion = document.getElementById('btnTerminarReservacion');
    const grupoEfectivo = document.getElementById('grupoEfectivo');
    const cantidadEfectivoInput = document.getElementById('cantidadEfectivo');
    const feriaCalculadaInput = document.getElementById('feriaCalculada');

    // Obtener y guardar el total original
    const totalOriginal = parseFloat(totalPagarElement.innerText.replace(/,/g, ''));
    let totalActual = totalOriginal; // se actualizará dinámicamente

    // Función para actualizar el total y mostrar u ocultar el select
    const actualizarTotalYMetodoPago = () => {
        let penalidad = parseFloat(inputPenalidad.value);
        totalActual = totalOriginal;

        if (!isNaN(penalidad)) {
            totalActual += penalidad;
        }

        // Actualiza el texto del total
        totalPagarElement.innerText = totalActual.toLocaleString('es-MX', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Mostrar u ocultar el <select>
        metodoPagoSelect.style.display = (totalActual > 0) ? 'block' : 'none';

        // Ocultar la sección de efectivo si el total es 0 o menos
        if (totalActual <= 0) {
            grupoEfectivo.classList.add('d-none');
        }
    };

    // Mostrar u ocultar sección de efectivo según método de pago
    metodoPagoSelect.addEventListener('change', () => {
        if (metodoPagoSelect.value === 'efectivo' && totalActual > 0) {
            grupoEfectivo.classList.remove('d-none');
        } else {
            grupoEfectivo.classList.add('d-none');
            cantidadEfectivoInput.value = '';
            feriaCalculadaInput.value = '';
        }
    });

    // Calcular feria (cambio) al ingresar la cantidad pagada
    cantidadEfectivoInput.addEventListener('input', () => {
        const cantidad = parseFloat(cantidadEfectivoInput.value);

        if (!isNaN(cantidad) && cantidad >= totalActual) {
            const feria = cantidad - totalActual;
            feriaCalculadaInput.value = feria.toLocaleString('es-MX', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        } else {
            feriaCalculadaInput.value = '';
        }
    });

    // Validación al hacer clic en "Terminar reservación"
    btnTerminarReservacion.addEventListener('click', async function (e) {
        if (totalActual > 0) {
            if (metodoPagoSelect.value === '') {
                e.preventDefault();
                alert('Por favor, selecciona un método de pago.');
                return;
            }
    
            if (metodoPagoSelect.value === 'efectivo') {
                const cantidad = parseFloat(cantidadEfectivoInput.value);
                if (isNaN(cantidad) || cantidad < totalActual) {
                    e.preventDefault();
                    alert('La cantidad en efectivo es insuficiente para cubrir el total.');
                    return;
                }
            }
            
            const penalidad = parseFloat(inputPenalidad.value) || 0;
            
            const datos = {
                id_reserva: idReserva,
                penalidad: penalidad,
                deudas_liquidadas: true
            };
    
            const respuesta = await fetch('/api/reservacion/terminar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(datos)
            });
    
            const resultado = await respuesta.json();
            mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo, '/admin/salidas');
            return;
        }
    
        // Total es 0, terminar reservación sin pago
        const datos = {
            id_reserva: idReserva,
            penalidad: 0,
            deudas_liquidadas: true
        };
    
        const respuesta = await fetch('/api/reservacion/terminar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        });
    
        const resultado = await respuesta.json();
        mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo, '/admin/salidas');
    });

    // Ejecutar al cargar
    actualizarTotalYMetodoPago();
    inputPenalidad.addEventListener('input', actualizarTotalYMetodoPago);
}
