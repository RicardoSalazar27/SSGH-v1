<!-- Modal Crear-->
<div class="modal fade" id="modalEditarCategoria" tabindex="-1" role="dialog" aria-labelledby="modalEditarCategoria" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Categoria</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formEditarCategoria">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input
                            type="text"
                            class="form-control"
                            id="nombreEditar"
                            name="nombre"
                            placeholder="Nombre De Categoria Ej. Deluxe" />
                    </div>
                    <div class="form-group">
                        <label for="capacidad_maxima">Capacidad Max.</label>
                        <input
                            type="number"
                            class="form-control"
                            id="capacidad_maximaEditar"
                            name="capacidad_maxima"
                            min="0"
                            placeholder="Ej. 4 (personas)" />
                    </div>
                    <div class="form-group">
                        <label for="tipo_cama">Cama</label>
                        <select class="form-control" id="tipo_camaEditar" name="tipo_cama">
                            <option value="Individual">Individual</option>
                            <option value="Individual Doble">Individual Doble</option>
                            <option value="Matrimonial">Matrimonial</option>
                            <option value="Matrimonial Doble">Matrimonial Doble</option>
                            <option value="King Size">King Size</option>
                            <option value="King Size Doble">King Size Doble</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="precio_base">Precio</label>
                        <input
                            type="number"
                            class="form-control"
                            id="precio_baseEditar"
                            name="precio_base"
                            min="0"
                            placeholder="Precio de habitacion" />
                    </div>
                    <div class="form-group">
                        <label for="servicios_incluidos">Servicios</label>
                        <textarea
                            class="form-control"
                            id="servicios_incluidosEditar"
                            name="servicios_incluidos"
                            placeholder="Ej. Agua caliente, aire acondicionado, servicio a la habitación"
                            rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="estado">Estatus</label>
                        <select class="form-control" id="estadoEditar" name="estado">
                            <option value="1">Activo</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </div>
                    <!-- Botones en el formulario -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary btnActualizarCategoria">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>