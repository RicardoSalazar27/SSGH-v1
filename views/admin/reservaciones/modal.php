<div id="modalReservacion" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Reservación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="wizardSteps">
                    <!-- Paso 1: Datos del Cliente -->
                    <div class="step" id="step1">
                        <h5>Paso 1: Datos del Cliente</h5>
                        <div class="mb-3 position-relative">
                            <label for="searchEmail" class="form-label">Buscar por Correo</label>
                            <input type="email" id="searchEmail" class="form-control" placeholder="Ingrese el correo" autocomplete="off">
                            <ul id="sugerenciasCorreo" class="list-group position-absolute d-none w-100" style="z-index: 1000;"></ul>
                        </div>
                        <div id="clienteFields">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" id="nombre" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="apellidos" class="form-label">Apellidos</label>
                                <input type="text" id="apellidos" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="documento_identidad" class="form-label">Documento de Identidad</label>
                                <input type="text" id="documento_identidad" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="text" id="telefono" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" id="direccion" class="form-control">
                            </div>
                        </div>
                    </div>

                    <!-- Paso 2: Selección de habitación y fechas -->
                    <div class="step d-none" id="step2">
                        <h5>Paso 2: Seleccionar Habitación y Fechas</h5>
                        <div class="mb-3">
                            <label for="fechaEntrada" class="form-label">Fecha de Entrada</label>
                            <input type="date" id="fechaEntrada" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="fechaSalida" class="form-label">Fecha de Salida</label>
                            <input type="date" id="fechaSalida" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="habitacion" class="form-label">Seleccionar Habitación</label>
                            <select id="habitacion" multiple></select>
                        </div>
                    </div>

                    <!-- Paso 3: Costos y Confirmación -->
                    <div class="step d-none" id="step3">
                        <h5>Paso 3: COSTO</h5>

                        <div class="progress mb-3">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

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
                                <input type="number" id="adelanto" class="form-control" value="0">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Total a pagar:</label>
                                <input type="number" id="totalPagar" class="form-control" value="0" readonly>
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label class="form-label">Método de pago:</label>
                            <select id="metodoPago" class="form-control">
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta">Tarjeta</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Observaciones:</label>
                            <textarea id="observaciones" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button id="btnAtras" class="btn btn-secondary d-none">Previo</button>
                <button id="btnSiguiente" class="btn btn-primary">Siguiente</button>
                <button id="btnConfirmar" class="btn btn-success d-none">
                    Registrar <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none"></span>
                </button>
            </div>
        </div>
    </div>
</div>
