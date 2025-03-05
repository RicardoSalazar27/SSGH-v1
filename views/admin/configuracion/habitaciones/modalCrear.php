<!-- Modal Crear-->
<div class="modal fade" id="modalCrearHabitacion" tabindex="-1" role="dialog" aria-labelledby="modalCrearHabitacion" aria-hidden="true">
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
                    placeholder="Numero Ej. 101"
                />
            </div>
            <div class="form-group">
                <label for="id_nivel">Nivel</label>
                <select class="form-control" id="id_nivel" name="id_nivel">
                    <?php 
                        foreach($niveles as $nivel){
                            ?><option value="<?php echo $nivel->id ?>"><?php echo $nivel->nombre;?></option>
                        <?php };?>
                 </select>
            </div>
            <div class="form-group">
                <label for="id_categoria">Categoria</label>
                <select class="form-control" id="id_categoria" name="id_categoria">
                    <?php 
                        foreach($categorias as $categoria){
                            ?><option value="<?php echo $categoria->id ?>"><?php echo $categoria->nombre;?></option>
                        <?php };?>
                 </select>
            </div>
            <div class="form-group">
                <label for="detalles_personalizados">Detalles Extra</label>
                <textarea name="detalles_personalizados" id="detalles_personalizados" class="form-control" rows="2" placeholder="Ingrese detalles adicionales aquí..."></textarea>
            </div>
            <div class="form-group">
                <label for="estatus">Estatus</label>
                <select class="form-control" id="estatus" name="estatus">
                    <option value="1">Activo</option>
                    <option value="0">Desactivado</option>
                 </select>
            </div>
            <div id="mensaje-resultado" class="alert alert-dismissible" style="display: none;"></div>
            <!-- Botones en el formulario -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btnSubirHabitacion">Guardar</button>
            </div>
      </div>
    </div>
  </div>
</div>