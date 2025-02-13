if (window.location.pathname === '/registro') {

    const btnCrear = document.getElementById('crear-cuenta');

    if(btnCrear){
        btnCrear.addEventListener('click', function(){

            event.preventDefault();

            const nuevoUsuario = {
                nombre: document.getElementById('nombre').value.trim(),
                email: document.getElementById('email').value.trim(),
                password: document.getElementById('password').value.trim(),
                password2: document.getElementById('password2').value.trim(),
                telefono: document.getElementById('telefono').value.trim(),
                direccion: document.getElementById('direccion').value.trim()
            }

            if(nuevoUsuario.email === "" || nuevoUsuario.nombre === "" || nuevoUsuario.direccion === "" || nuevoUsuario.password === "" || nuevoUsuario.password2 === ""){
                alert('Todos los campos son necesarios');
            }
            if (nuevoUsuario.password === nuevoUsuario.password2) {
                delete nuevoUsuario.password2;
            } else{
                alert('las constraseñas no coinciden');
            }

            //Enviar por POST al servidor los datos del nuevo usuario
            try {
                //Crear FormData para enviar los datos
                const datos = new FormData();
                Object.entries(nuevoUsuario).forEach(([key, value]) => datos.append(key, value));
                // Imprimir los valores de FormData
                datos.forEach((value, key) => {
                    console.log(key + ": " + value);
                });
            } catch (error) {
                console.log(error);
            }
 
        })
    }

}