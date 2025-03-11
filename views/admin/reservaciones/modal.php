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
                            
                            <!-- Lista desplegable para sugerencias -->
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
                            <select id="habitacion" class="form-control">
                                <option value="">Seleccione una habitación</option>
                            </select>
                        </div>
                    </div>

                    <!-- Paso 3: Confirmación -->
                    <div class="step d-none" id="step3">
                        <h5>Paso 3: Confirmar Reservación</h5>
                        <p>Verifique los datos antes de confirmar.</p>
                        <ul>
                            <li><strong>Cliente:</strong> <span id="resumenCliente"></span></li>
                            <li><strong>Correo:</strong> <span id="resumenCorreo"></span></li>
                            <li><strong>Fecha de Entrada:</strong> <span id="resumenEntrada"></span></li>
                            <li><strong>Fecha de Salida:</strong> <span id="resumenSalida"></span></li>
                            <li><strong>Habitación:</strong> <span id="resumenHabitacion"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btnAtras" class="btn btn-secondary d-none">Atrás</button>
                <button id="btnSiguiente" class="btn btn-primary">Siguiente</button>
                <button id="btnConfirmar" class="btn btn-success d-none">Confirmar</button>
            </div>
        </div>
    </div>
</div>
