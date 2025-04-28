<!-- Modal Crear-->
<div class="modal fade" id="modalEditarProducto" tabindex="-1" role="dialog" aria-labelledby="editarProductoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Actualizar Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formEditarProducto">
            <!-- Formulario igual que el de edición -->
            <div class="form-group text-center">
                <img id="imgEditarP" alt="imagen-producto" class="img-fluid mb-2" style="height: 100px;">
                <input type="file" class="form-control-file" id="fotoP" name="fotoP" accept=".png,image/png">
            </div>
            <div class="form-group">
                <label for="nombreEditar">Nombre</label>
                <input 
                    type="text"
                    class="form-control"
                    id="nombreEditar"
                    name="nombreEditar"
                    placeholder="Nombre del producto o servicio"
                />
            </div>
            <div class="form-group">
                <label for="precioEditar">Precio</label>
                <input 
                    type="number"
                    class="form-control"
                    id="precioEditar"
                    name="precioEditar"
                    placeholder="Precio en MXN"
                    step="0.01"
                    min="0"
                />
            </div>
            <div class="form-group">
                <label for="stockEditar">Stock</label>
                <input 
                    type="number"
                    class="form-control"
                    id="stockEditar"
                    name="stockEditar"
                    placeholder="Unidades en almacen"
                />
            </div>
            <div class="form-group">
                <label for="categoria_idEditar">Categoría</label>
                <select class="form-control" id="categoria_idEditar" name="categoria_idEditar">
                    <!-- <option value="">Selecciona una categoría</option> -->
                    <?php foreach ($categoriasProductos as $categoria) : ?>
                        <option value="<?= $categoria->id; ?>"><?= htmlspecialchars($categoria->nombre); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="codigo_barrasEditar">Codigo</label>
                <input 
                    type="text"
                    class="form-control"
                    id="codigo_barrasEditar"
                    name="codigo_barrasEditar"
                    placeholder="Codigo de barras o SKU"
                />
            </div>
            <div class="form-group">
                <label for="proveedorEditar">Proveedor</label>
                <input 
                    type="text"
                    class="form-control"
                    id="proveedorEditar"
                    name="proveedorEditar"
                    placeholder="Nombre de proveedor"
                />
            </div>
            <div id="mensaje-resultado" class="alert alert-dismissible" style="display: none;"></div>
            <!-- Botones en el formulario -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btnActualizarProducto">Guardar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
