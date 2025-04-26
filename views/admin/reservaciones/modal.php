<div id="modalReservacion" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nueva Reservación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="progress mb-3">
                <div id="progressBar" class="progress-bar bg-primary" role="progressbar" style="width: 33%;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">
                    33%
                </div>
            </div>

                <div id="wizardSteps">
                    <!-- Paso 1: Datos del Cliente -->
                    <div class="step" id="step1">
                        <h5 class="font-weight-bold text-center">Paso 1: DATOS DEL CLIENTE</h5>
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
                        <h5 class="font-weight-bold text-center">Paso 2: SELECIONAR HABITACION Y FECHAS</h5>
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
                        <h5 class="font-weight-bold text-center">Paso 3: COSTO</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label me-2">Descuento:</label>
                                        <div>
                                            <input type="radio" name="tipoDescuento" value="PORCENTAJE" id="descuentoPorcentaje" checked>
                                            <label for="descuentoPorcentaje" class="ms-1 me-2">%</label>

                                            <input type="radio" name="tipoDescuento" value="MONTO" id="descuentoMonto">
                                            <label for="descuentoMonto" class="ms-1 fw-bold">MXN$</label>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">% | MXN$</span>
                                        <input type="number" id="descuento" class="form-control" value="0">
                                    </div> 
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Cobro extra:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">+MXN$</span>
                                        <input type="number" id="cobroExtra" class="form-control" value="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Adelanto:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MXN$</span>
                                        <input type="number" id="adelanto" class="form-control" value="0">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Total a pagar:</label>
                                    <div class="input-group">
                                        <span class="input-group-text">MXN$</span>
                                        <input type="number" id="totalPagar" class="form-control" value="0" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="form-label">Método de pago:</label>
                                    <select id="metodoPago" class="form-control">
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Tarjeta">Tarjeta</option>
                                        <option value="Transferencia">Transferencia</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Observaciones:</label>
                                    <textarea id="observaciones" class="form-control" rows="2"></textarea>
                                </div>
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
