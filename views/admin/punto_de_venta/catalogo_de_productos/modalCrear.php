<!-- Modal Crear-->
<div class="modal fade" id="modalCrearProducto" tabindex="-1" role="dialog" aria-labelledby="crearProductoModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Crear Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formCrearProducto">
            <!-- Formulario igual que el de edición -->
            <div class="form-group text-center">
                <img id="img" alt="imagen-producto" class="img-fluid mb-2" style="height: 100px;">
                <input type="file" class="form-control-file" id="fotoPN" name="fotoPN">
            </div>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    class="form-control"
                    id="nombre"
                    name="nombre"
                    placeholder="Nombre del producto o servicio"
                />
            </div>
            <div class="form-group">
                <label for="precio">Precio</label>
                <input 
                    type="number"
                    class="form-control"
                    id="precio"
                    name="precio"
                    placeholder="Precio en MXN"
                    step="0.01"
                    min="0"
                />
            </div>
            <div class="form-group">
                <label for="stock">Stock</label>
                <input 
                    type="number"
                    class="form-control"
                    id="stock"
                    name="stock"
                    placeholder="Unidades en almacen"
                />
            </div>
            <div class="form-group">
                <label for="categoria_id">Categoría</label>
                <select class="form-control" id="categoria_id" name="categoria_id">
                    <!-- <option value="">Selecciona una categoría</option> -->
                    <?php foreach ($categoriasProductos as $categoria) : ?>
                        <option value="<?= $categoria->id; ?>"><?= htmlspecialchars($categoria->nombre); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="codigo_barras">Codigo</label>
                <input 
                    type="text"
                    class="form-control"
                    id="codigo_barras"
                    name="codigo_barras"
                    placeholder="Codigo de barras o SKU"
                />
            </div>
            <div class="form-group">
                <label for="proveedor">Proveedor</label>
                <input 
                    type="text"
                    class="form-control"
                    id="proveedor"
                    name="proveedor"
                    placeholder="Nombre de proveedor"
                />
            </div>
            <div id="mensaje-resultado" class="alert alert-dismissible" style="display: none;"></div>
            <!-- Botones en el formulario -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary btnSubirProducto">Guardar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
