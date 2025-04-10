// src/js/alertas.js
(function() {
    function mostrarAlerta(titulo, mensaje, tipo, urlRedireccion = null) {
        Swal.fire({
            icon: tipo,
            title: titulo,
            text: mensaje,
        }).then(() => {
            $('.modal').modal('hide'); 
            if (urlRedireccion) {
                window.location.href = urlRedireccion;
            }
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

    function obtenerFechaFormateada() {
        const fecha = new Date();
        const year = fecha.getFullYear();
        const month = String(fecha.getMonth() + 1).padStart(2, '0'); // +1 porque enero es 0
        const day = String(fecha.getDate()).padStart(2, '0');
        const hours = String(fecha.getHours()).padStart(2, '0');
        const minutes = String(fecha.getMinutes()).padStart(2, '0');
        const seconds = String(fecha.getSeconds()).padStart(2, '0');
    
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }
    

    // Hacerlas accesibles globalmente
    window.mostrarAlerta = mostrarAlerta;
    window.mostrarAlerta2 = mostrarAlerta2;
    window.mostrarAlerta3 = mostrarAlerta3;
    window.obtenerFechaFormateada = obtenerFechaFormateada;
})();
