<!-- Modal Editar-->
<div class="modal fade" id="modalEditarHabitacion" tabindex="-1" role="dialog" aria-labelledby="modalEditarHabitacion" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="habitacionesModalLabel">Editar Habitación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEditarHabitacion">
          <div class="form-group">
            <label for="numeroEditar">Número</label>
            <input 
              type="number"
              class="form-control"
              id="numeroEditar"
              name="numeroEditar"
              placeholder="Número Ej. 101"
            />
          </div>

          <div class="form-group">
            <label for="id_nivelEditar">Nivel</label>
            <select class="form-control" id="id_nivelEditar" name="id_nivelEditar">
              <?php foreach ($niveles as $nivel) { ?>
                <option value="<?php echo $nivel->id; ?>"><?php echo $nivel->nombre; ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="id_categoriaEditar">Categoría</label>
            <select class="form-control" id="id_categoriaEditar" name="id_categoriaEditar">
              <?php foreach ($categorias as $categoria) { ?>
                <option value="<?php echo $categoria->id; ?>"><?php echo $categoria->nombre; ?></option>
              <?php } ?>
            </select>
          </div>

          <div class="form-group">
            <label for="detalles_personalizadosEditar">Detalles Extra</label>
            <textarea name="detalles_personalizadosEditar" id="detalles_personalizadosEditar" class="form-control" rows="2" placeholder="Ingrese detalles adicionales aquí..."></textarea>
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
            <button type="submit" class="btn btn-primary btnActualizarHabitacion">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
