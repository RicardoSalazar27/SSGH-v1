if (window.location.pathname === '/admin/configuracion/informacion') {
    //Obtener datos del formulario por si hay actualizacion
    const btnActualizarInfo = document.getElementById('btnActualizarInfo');

    if(btnActualizarInfo){
        btnActualizarInfo.addEventListener('click', async function(e){
            e.preventDefault();
            //alert('diste click en actualizar info');

            const hotel = {
                nombre : document.getElementById('nombre').value.trim(),
                telefono : document.getElementById('telefono').value.trim(),
                correo : document.getElementById('correo').value.trim(),
                ubicacion : document.getElementById('ubicacion').value.trim(),
                img : document.getElementById('logo').files[0]
            }

            try {
                //Crear datos para enviar en formdata
                const datos  = new FormData();
                Object.entries(hotel).forEach(([key, value]) => datos.append(key, value));
                const url = 'http://localhost:3000/admin/configuracion/informacion/actualizar';
                const respuesta = await fetch(url, { // Corregido: Usar un objeto en lugar de un array
                    method: 'POST',
                    body: datos
                });

                
                
            } catch (error) {
                console.log('error');
            }

        })
    }
}