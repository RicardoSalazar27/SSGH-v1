<!-- Modal Crear-->
<div class="modal fade" id="modalEditarNivel" tabindex="-1" role="dialog" aria-labelledby="modalEditarNivel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Nivel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEditarNivel">
          <div class="form-group">
            <label for="nombreEditar">Nombre</label>
            <input
              type="text"
              class="form-control"
              id="nombreEditar"
              name="nombreEditar"
              placeholder="Nombre Del Nivel Ej. Primer Nivel" />
          </div>
          <div class="form-group">
            <label for="numeroEditar">Numero</label>
            <input
              type="number"
              class="form-control"
              id="numeroEditar"
              name="numeroEditar"
              placeholder="Intervalo de Habitaciones Ej- 100-109" />
          </div>
          <div class="form-group">
            <label for="estatusEditar">Estatus</label>
            <select class="form-control" id="estatusEditar" name="estatusEditar">
              <option value="1">Activo</option>
              <option value="0">Desactivado</option>
            </select>
          </div>
          <div id="mensaje-resultado" class="alert alert-dismissible" style="display: none;"></div>
          <!-- Botones en el formulario -->
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary btnActualizarNivel">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>