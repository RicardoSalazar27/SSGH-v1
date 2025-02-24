<!-- Modal Crear-->
<div class="modal fade" id="categoriasModal" tabindex="-1" role="dialog" aria-labelledby="categoriasModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="categoriasModalLabel">Agregar Nuevo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    class="form-control"
                    id="nombre"
                    name="nombre"
                    placeholder="Nombre De Categoria Ej. Deluxe"
                />
            </div>
            <div class="form-group">
                <label for="capacidad_maxima">Capacidad Max.</label>
                <input 
                    type="number"
                    class="form-control"
                    id="capacidad_maxima"
                    name="capacidad_maxima"
                    min="0"
                    placeholder="Ej. 4 (personas)"
                />
            </div>
            <div class="form-group">
                <label for="estatus">Estatus</label>
                <select class="form-control" id="estatus" name="estatus">
                    <option value="1">Activo</option>
                    <option value="0">Desactivado</option>
                 </select>
            </div>

            <!-- Botones en el formulario -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btnSubirCategoria">Guardar</button>
            </div>
      </div>
    </div>
  </div>
</div>