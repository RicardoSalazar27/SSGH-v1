<!-- Modal Crear-->
<div class="modal fade" id="usuarioNuevoModal" tabindex="-1" role="dialog" aria-labelledby="crearUsuarioModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="crearUsuarioModalLabel">Crear Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formCrearUsuario">
            <!-- Formulario igual que el de edición -->
            <div class="form-group text-center">
                <img id="img" alt="imagen-empleado" class="img-fluid mb-2" style="height: 100px;">
                <input type="file" class="form-control-file" id="logo" name="logo" accept=".png,image/png">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    class="form-control"
                    id="nombre"
                    name="nombre"
                    placeholder="Tu Nombre"
                />
            </div>
            <div class="form-group">
                <label for="apellido">Apellidos</label>
                <input 
                    type="text"
                    class="form-control"
                    id="apellido"
                    name="apellido"
                    placeholder="Tus Apellidos"
                />
            </div>
            <div class="form-group">
                <label for="direccion">Direccion</label>
                <input 
                    type="text"
                    class="form-control"
                    id="direccion"
                    name="direccion"
                    placeholder="Tu Direccion"
                />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    placeholder="Tu Email"
                />
            </div>
            <div class="form-group">
                <label for="telefono">Telefono</label>
                <input 
                    type="telefono"
                    class="form-control"
                    id="telefono"
                    name="telefono"
                    placeholder="Tu telefono"
                />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    placeholder="Tu Contraseña"
                />
            </div>
            <div class="form-group">
                <label for="passowrd2">Repite Tu Password</label>
                <input 
                    type="password"
                    class="form-control"
                    id="password2"
                    name="password2"
                    placeholder="Tu Contraseña"
                />
            </div>
            <div class="form-group">
                <label for="rol_id">Rol</label>
                <select class="form-control" id="rol_id" name="rol_id">
                    <option value="1">Administrador</option>
                    <option value="2">General</option>
                    <option value="3">Limpieza</option>
                 </select>
            </div>
            <div class="form-group">
                <label for="estatus">Estatus</label>
                <select class="form-control" id="estatus" name="estatus">
                    <option value="0">Inactivo</option>
                    <option value="1">Activo</option>
                 </select>
            </div>
            <div id="mensaje-resultado" class="alert alert-dismissible" style="display: none;"></div>
            <!-- Botones en el formulario -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btnSubirUsuario">Guardar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
