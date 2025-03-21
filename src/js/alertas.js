// src/js/alertas.js
(function() {
    function mostrarAlerta(titulo, mensaje, tipo) {
        Swal.fire({
            icon: tipo,
            title: titulo,
            text: mensaje,
        }).then(() => {
            $('.modal').modal('hide'); 
        });
    }

    function mostrarAlerta2(mensaje, tipo) {
        const mensajeResultado = document.getElementById('mensaje-resultado');
        mensajeResultado.style.display = 'block';
        mensajeResultado.textContent = mensaje;

        mensajeResultado.className = `alert alert-${tipo === 'error' ? 'danger' : tipo === 'success' ? 'success' : 'info'}`;

        setTimeout(() => {
            mensajeResultado.style.display = 'none';
        }, 5000);
    }

    function mostrarAlerta3(titulo, mensaje, tipo) {
            Swal.fire({
                icon: tipo,
                title: titulo,
                text: mensaje,
                willClose: () => {
                    // Al cerrar la alerta, recargar la página después de un pequeño retraso
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000); // Ajusta los milisegundos según tu preferencia
                }
            }).then(() => {
                $('.modal').modal('hide'); 
            });
        }

    // Hacerlas accesibles globalmente
    window.mostrarAlerta = mostrarAlerta;
    window.mostrarAlerta2 = mostrarAlerta2;
    window.mostrarAlerta3 = mostrarAlerta3;
})();
