if (window.location.pathname === '/login') {
    const btniniciarSesion = document.getElementById('btnIniciarSesion');

    if( btniniciarSesion){
        btniniciarSesion.addEventListener('click', async function (event) {
            event.preventDefault();

            const usuario = {
                email: document.getElementById('email').value.trim(),
                password: document.getElementById('password').value.trim()
            };

            if( usuario.email === "" || usuario.password === ""){
                mostrarAlerta2('Todos los campos son obligatorios', 'error');
                return;
            }

            try {
                // Crear FormData para enviar los datos
                const datos = new FormData();
                Object.entries(usuario).forEach(([key, value]) => datos.append(key, value));
                const url = 'http://localhost:3000/login';
                const respuesta = await fetch(url, { // Corregido: Usar un objeto en lugar de un array
                    method: 'POST',
                    body: datos
                });

                // Esperar la respuesta en formato JSON
                const resultado = await respuesta.json();
                if(resultado.autorizado){
                    window.location.href = '/admin/index';
                } else{
                    mostrarAlerta2(resultado.mensaje, resultado.tipo);
                }

            } catch (error) {
                console.log(error);
            }
        })
    }

    function mostrarAlerta2(mensaje, tipo) {
        const mensajeResultado = document.getElementById('mensaje-resultado');
        mensajeResultado.style.display = 'block'; // Asegúrate de que el contenedor se muestre
        mensajeResultado.textContent = mensaje; // Mostrar solo el mensaje
    
        // Cambiar el color de fondo del contenedor según el tipo de mensaje
        if (tipo === 'error') {
            mensajeResultado.className = 'alert alert-danger'; // Rojo para error
        } else if (tipo === 'success') {
            mensajeResultado.className = 'alert alert-success'; // Verde para éxito
        } else {
            mensajeResultado.className = 'alert alert-info'; // Azul o información por defecto
        }
    
        // Opcional: Ocultar el mensaje después de 5 segundos
        setTimeout(() => {
            mensajeResultado.style.display = 'none';
        }, 5000);
    }
}