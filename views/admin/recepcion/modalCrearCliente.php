<!-- Modal Crear-->
<div class="modal fade" id="modalCrearCliente" tabindex="-1" role="dialog" aria-labelledby="modalCrearCliente" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar Nuevo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div id="mensaje-resultado"></div>
            <form id="formEditarCliente">
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input 
                        type="text"
                        class="form-control"
                        id="nombreNuevoCliente"
                        name="nombre"
                        placeholder="Tu Nombre"
                    />
                </div>
                <div class="form-group">
                    <label for="apellidos">Apellidos</label>
                    <input 
                        type="text"
                        class="form-control"
                        id="apellidosNuevoCliente"
                        name="apellidos"
                        placeholder="Tus Apellidos"
                    />
                </div>
                <div class="form-group">
                    <label for="correo">Correo</label>
                    <input 
                        type="email"
                        class="form-control"
                        id="correoNuevoCliente"
                        name="correo"
                        placeholder="Tu Correo"
                    />
                </div>
                <div class="form-group">
                    <label for="telefono">Telefono</label>
                    <input 
                        type="text"
                        class="form-control"
                        id="telefonoNuevoCliente"
                        name="telefono"
                        placeholder="Tu Telefono"
                    />
                </div>
                <div class="form-group">
                    <label for="documento_identidad">DNI</label>
                    <input 
                        type="text"
                        class="form-control"
                        id="documento_identidadNuevoCliente"
                        name="documento_identidad"
                        placeholder=""
                    />
                </div>
                <div class="form-group">
                    <label for="direccion">Dirección</label>
                    <textarea 
                        class="form-control"
                        id="direccionNuevoCliente"
                        name="direccion"
                        placeholder="Ingresa tu dirección completa"
                        rows="2"
                    ></textarea>
                </div>
                <div id="mensaje-resultado" class="alert alert-dismissible" style="display: none;"></div>
                <!-- Botones en el formulario -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btnCrearCliente">Guardar</button>
                </div>
            </form>
      </div>
    </div>
  </div>
</div>