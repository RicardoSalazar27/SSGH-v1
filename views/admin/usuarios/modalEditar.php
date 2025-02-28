<!-- Modal Editar-->
<div class="modal fade" id="usuarioEditarModal" tabindex="-1" role="dialog" aria-labelledby="usuariosModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="usuariosEditarModalLabel">Editar Usuario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEditarUsuario">
            <div class="form-group text-center">
                <img id="imgEditar" alt="imagen-empleado" class="img-fluid mb-2" style="height: 100px;">
                <input type="file" class="form-control-file" id="logoEditar" name="logoEditar">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    class="form-control"
                    id="nombreEditar"
                    name="nombre"
                    placeholder="Tu Nombre"
                />
            </div>
            <div class="form-group">
                <label for="apellido">Apellidos</label>
                <input 
                    type="text"
                    class="form-control"
                    id="apellidoEditar"
                    name="apellido"
                    placeholder="Tus Apellidos"
                />
            </div>
            <div class="form-group">
                <label for="direccion">Direccion</label>
                <input 
                    type="text"
                    class="form-control"
                    id="direccionEditar"
                    name="direccion"
                    placeholder="Tu Direccion"
                />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email"
                    class="form-control"
                    id="emailEditar"
                    name="email"
                    placeholder="Tu Email"
                />
            </div>
            <div class="form-group">
                <label for="telefono">Telefono</label>
                <input 
                    type="telefono"
                    class="form-control"
                    id="telefonoEditar"
                    name="telefono"
                    placeholder="Tu telefono"
                />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password"
                    class="form-control"
                    id="passwordEditar"
                    name="password"
                    placeholder="Tu Contraseña"
                />
            </div>
            <div class="form-group">
                <label for="password2">Repite Tu Password</label>
                <input 
                    type="password"
                    class="form-control"
                    id="password2Editar"
                    name="password2"
                    placeholder="Tu Contraseña"
                />
            </div>
            <div class="form-group">
                <label for="rol_idEditar">Rol</label>
                <select class="form-control" id="rol_idEditar" name="rol_idEditar">
                    <option value="1">Administrador</option>
                    <option value="2">General</option>
                    <option value="3">Limpieza</option>
                 </select>
            </div>
            <div class="form-group">
                <label for="estatusEditar">Estatus</label>
                <select class="form-control" id="estatusEditar" name="estatusEditar">
                    <option value="0">Inactivo</option>
                    <option value="1">Activo</option>
                 </select>
            </div>
            <div id="mensaje-resultado" class="alert alert-dismissible" style="display: none;"></div>
            <!-- Botones en el formulario -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btnActualizarUsuario">Guardar Cambios</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
