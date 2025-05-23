if (window.location.pathname === '/registro') {

    const btnCrear = document.getElementById('crear-cuenta');

    if (btnCrear) {
        btnCrear.addEventListener('click', async function(event) { // Marca la función como 'async'

            event.preventDefault();

            const nuevoUsuario = {
                nombre: document.getElementById('nombre').value.trim(),
                apellido: document.getElementById('apellidos').value.trim(),
                email: document.getElementById('email').value.trim(),
                password: document.getElementById('password').value.trim(),
                password2: document.getElementById('password2').value.trim(),
                telefono: document.getElementById('telefono').value.trim(),
                direccion: document.getElementById('direccion').value.trim()
            };
            
            if (nuevoUsuario.email === "" || nuevoUsuario.nombre === "" || nuevoUsuario.direccion === "" || nuevoUsuario.password === "" || nuevoUsuario.password2 === "" || nuevoUsuario.telefono === "") {
                mostrarAlerta2('Todos los campos son necesarios', 'error');
                return;
            }
            
            if (nuevoUsuario.password !== nuevoUsuario.password2) {
                mostrarAlerta2('Las contraseñas no coinciden', 'error');
                return;
            }
            
            // Eliminar 'password2' antes de enviarlo al servidor
            delete nuevoUsuario.password2;

            try {
                // Crear FormData para enviar los datos
                const datos = new FormData();
                Object.entries(nuevoUsuario).forEach(([key, value]) => datos.append(key, value));
                const url = '/registro';
                const respuesta = await fetch(url, { // Corregido: Usar un objeto en lugar de un array
                    method: 'POST',
                    body: datos
                });

                // Esperar la respuesta en formato JSON
                const resultado = await respuesta.json();
                mostrarAlerta(resultado.titulo, resultado.mensaje, resultado.tipo, '/login');
                
            } catch (error) {
                console.log('Error en la solicitud:', error);
            }

        });
    }
}
