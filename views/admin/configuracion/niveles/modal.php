<!-- Modal Crear-->
<div class="modal fade" id="nivelesModal" tabindex="-1" role="dialog" aria-labelledby="nivelesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="nivelesModalLabel">Agregar Nuevo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="form-group">
                <label for="numero">Numero</label>
                <input 
                    type="number"
                    class="form-control"
                    id="numero"
                    name="numero"
                    placeholder="Numero Ej. 1"
                />
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    class="form-control"
                    id="nombre"
                    name="nombre"
                    placeholder="Nombre Del Nivel Ej. Primer Nivel"
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
                <button type="submit" class="btn btn-primary btnSubirNivel">Guardar</button>
            </div>
      </div>
    </div>
  </div>
</div>