<div id="modalEditar" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Reservación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Campo oculto para ID de la reservación -->
                <input type="hidden" id="idReservacion">

                <!-- Datos del Cliente -->
                <h5 class="font-weight-bold text-center">DATOS DEL CLIENTE</h5>
                <div class="mb-3 position-relative">
                    <label for="searchEmail" class="form-label">Buscar por Correo</label>
                    <input type="email" id="searchEmailEditar" class="form-control" placeholder="Ingrese el correo" autocomplete="off">
                    <ul id="sugerenciasCorreo" class="list-group position-absolute d-none w-100" style="z-index: 1000;"></ul>
                </div>
                <div id="clienteFields">
                    <div class="mb-3">
                        <label for="nombreEditar" class="form-label">Nombre</label>
                        <input type="text" id="nombreEditar" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="apellidosEditar" class="form-label">Apellidos</label>
                        <input type="text" id="apellidosEditar" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="documento_identidadEditar" class="form-label">Documento de Identidad</label>
                        <input type="text" id="documento_identidadEditar" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="telefonoEditar" class="form-label">Teléfono</label>
                        <input type="text" id="telefonoEditar" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="direccionEditar" class="form-label">Dirección</label>
                        <input type="text" id="direccionEditar" class="form-control">
                    </div>
                </div>

                <!-- Selección de Habitación y Fechas -->
                <h5 class="font-weight-bold text-center mt-4">SELECCIONAR HABITACIÓN Y FECHAS</h5>
                <div class="mb-3">
                    <label for="fechaEntradaEditar" class="form-label">Fecha de Entrada</label>
                    <input type="date" id="fechaEntradaEditar" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="fechaSalidaEditar" class="form-label">Fecha de Salida</label>
                    <input type="date" id="fechaSalidaEditar" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="habitacionEditar" class="form-label">Seleccionar Habitación</label>
                    <select id="habitacionEditar" multiple></select>
                </div>

                <!-- Costos y Métodos de Pago -->
                <h5 class="font-weight-bold text-center mt-4">COSTO</h5>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Descuento:</label>
                        <div class="d-flex">
                            <input type="radio" name="tipoDescuento" value="porcentaje" id="descuentoPorcentaje">
                            <label for="descuentoPorcentaje" class="ms-2">%</label>

                            <input type="radio" name="tipoDescuento" value="monto" id="descuentoMonto" checked class="ms-3">
                            <label for="descuentoMonto" class="ms-2">MXN$</label>
                        </div>
                        <input type="number" id="descuento" class="form-control mt-2" value="0">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Cobro extra:</label>
                        <div class="input-group">
                            <input type="number" id="cobroExtra" class="form-control" value="0">
                            <button class="btn btn-outline-secondary">+</button>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <label class="form-label">Adelanto:</label>
                        <input type="number" id="adelantoEditar" class="form-control" value="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Total a pagar:</label>
                        <input type="number" id="totalPagarEditar" class="form-control" value="0" readonly>
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Método de pago:</label>
                    <select id="metodoPagoEditar" class="form-control">
                        <option value="Efectivo">Efectivo</option>
                        <option value="Tarjeta">Tarjeta</option>
                        <option value="Transferencia">Transferencia</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Observaciones:</label>
                    <textarea id="observacionesEditar" class="form-control" rows="2"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button id="btnEditar" class="btn btn-primary">
                    Guardar Cambios <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none"></span>
                </button>
            </div>
        </div>
    </div>
</div>