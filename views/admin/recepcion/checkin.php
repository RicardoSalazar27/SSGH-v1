<div class="content-header">
  <div class="container">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?php echo $titulo; ?></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Layout</a></li>
          <li class="breadcrumb-item active">Top Navigation</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content pb-2">
  <div class="container">
    
    <!-- Primera fila: Card que ocupa toda la fila con diseño mejorado -->
    <div class="row">
        <div class="col-12">
            <div class="card card-success card-outline">
            <div class="card-header">
                <h5 class="m-0">Datos de la habitación</h5>
            </div>
            <div class="card-body">
                <div class="row">
                <!-- Primera columna -->
                <div class="col-md-3">
                    <p><strong class="text-primary">Nombre</strong></p>
                    <p><?php echo $habitacion->numero;?></p>
                    <p><strong class="text-primary">Detalles</strong></p>
                    <p><?php echo $habitacion->id_categoria->servicios_incluidos;?></p>
                </div>
                
                <!-- Segunda columna -->
                <div class="col-md-3">
                    <p><strong class="text-primary">Piso</strong></p>
                    <p><?php echo $habitacion->id_nivel->nombre;?></p>
                    <p><strong class="text-primary">Precio</strong></p>
                    <p id="precio_habitacion"><?php echo $habitacion->id_categoria->precio_base;?></p>
                </div>

                <!-- Tercera columna -->
                <div class="col-md-3">
                    <p><strong class="text-primary">Categoria</strong></p>
                    <p><?php echo $habitacion->id_categoria->nombre ?></p>
                    <p><strong class="text-primary">Estado</strong></p>
                    <span class="badge bg-<?php echo $habitacion->id_estado_habitacion->color;?>"><?php echo $habitacion->id_estado_habitacion->nombre;?></span>
                </div>

                <!-- Cuarta columna -->
                <div class="col-md-3">
                    <p><strong class="text-primary">Capacidad</strong></p>
                    <p><?php echo $habitacion->id_categoria->capacidad_maxima;?> personas</p>
                    <p><strong class="text-primary">Tipo Cama</strong></p>
                    <p><?php echo $habitacion->id_categoria->tipo_cama;?></p>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <!-- Segunda fila: Dos cards en columnas separadas -->
    <div class="row">
        <!-- Tarjeta : Datos del cliente -->
        <div class="col-lg-6">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h5 class="card-title m-0">Datos del cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <label for="correo" class="text-dark col-form-label"><strong>Correo</strong></label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="correo" placeholder="Busca cliente por correo aquí"
                                    value="<?php echo isset($reservacion) ? $reservacion->correo : ''; ?>">
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalCrearCliente" type="button">
                                    <i class="fa-solid fa-user-plus text-white"></i>
                                </button>
                            </div>
                            <ul id="sugerenciaCorreo" class="list-group position-absolute d-none w-100" style="z-index: 1000;"></ul>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" placeholder=""
                                value="<?php echo isset($reservacion) ? $reservacion->cliente_nombre : ''; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos" placeholder=""
                                value="<?php echo isset($reservacion) ? $reservacion->cliente_apellidos : ''; ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="documento" class="text-dark col-form-label"><strong>Documento</strong></label>
                            <input type="text" class="form-control" id="documento" placeholder="Ej. SASE09038ID8"
                                value="<?php echo isset($reservacion) ? $reservacion->documento_identidad : ''; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="text-dark col-form-label"><strong>Teléfono</strong></label>
                            <input type="text" class="form-control" id="telefono" placeholder="Ej. 78192374848"
                                value="<?php echo isset($reservacion) ? $reservacion->telefono : ''; ?>" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="direccion">Dirección</label>
                            <input type="text" class="form-control" id="direccion" placeholder="Puede ser solo ciudad"
                                value="<?php echo isset($reservacion) ? $reservacion->direccion : ''; ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
         </div>
         <!-- Tarjeta : Datos del ALOJAMIENTO -->
         <div class="col-lg-6">
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h5 class="card-title m-0">Datos del alojamiento</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Primera columna FECHAS-->
                        <div class="col-md-6">
                            <label for="fechaEntrada" class="form-label">Fecha de Entrada</label>
                            <input type="date" id="fechaEntrada" class="form-control" min="<?php echo isset($reservacion) ? $reservacion->fecha_entrada : $date; ?>"
                            max="<?php echo isset($reservacion) ? $reservacion->fecha_entrada : $date; ?>" 
                            value="<?php echo isset($reservacion) ? $reservacion->fecha_entrada : $date; ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="fechaSalida" class="form-label">Fecha de Salida</label>
                            <input type="date" id="fechaSalida" class="form-control"
                            min="<?php echo isset($reservacion) ? $reservacion->fecha_entrada : $nextday; ?>"
                            max="<?php echo isset($reservacion) ? $reservacion->fecha_entrada : $fechaMax; ?>"
                            value="<?php echo isset($reservacion) ? $reservacion->fecha_salida : $nextday; ?>">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <label class="form-label me-2">Descuento:</label>
                                <div>
                                    <input type="radio" name="tipoDescuento" value="PORCENTAJE" id="descuentoPorcentaje"
                                    <?php 
                                        if (!isset($reservacion) || (isset($reservacion) && $reservacion->tipo_descuento === "PORCENTAJE")) {
                                            echo "checked";
                                        }
                                    ?>>
                                    <label for="descuentoPorcentaje" class="ms-1 me-2">%</label>
                                    
                                    <input type="radio" name="tipoDescuento" value="MONTO" id="descuentoMonto"
                                    <?php 
                                        if (isset($reservacion) && $reservacion->tipo_descuento === "MONTO") {
                                            echo "checked";
                                        }
                                    ?>>
                                    <label for="descuentoMonto" class="ms-1 fw-bold">MXN$</label>
                                </div>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"> % | MXN$</span>
                                <input type="number" id="descuento" class="form-control" 
                                value="<?php echo isset($reservacion) ? $reservacion->descuento_aplicado : 0; ?>">
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cobro extra:</label>
                            <div class="input-group">
                                <span class="input-group-text">+MXN$</span>
                                <input type="number" id="cobroExtra" class="form-control" 
                                value="<?php echo isset($reservacion) ? $reservacion->cobro_extra : 0; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Adelanto:</label>
                            <div class="input-group">
                                <span class="input-group-text">MXN$</span>
                                <input type="number" id="adelanto" class="form-control" 
                                value="<?php echo isset($reservacion) ? $reservacion->adelanto : 0; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Total a pagar:</label>
                            <div class="input-group">
                                <span class="input-group-text">MXN$</span>
                                <input type="number" id="totalPagar" class="form-control" 
                                value="<?php echo isset($reservacion) ? $reservacion->precio_total : 0; ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">Método de pago:</label>
                            <select id="metodoPago" class="form-control">
                                <option value="Efectivo" <?php echo isset($reservacion) && $reservacion->metodo_pago === "Efectivo" ? "selected" : ""; ?>>Efectivo</option>
                                <option value="Tarjeta" <?php echo isset($reservacion) && $reservacion->metodo_pago === "Tarjeta" ? "selected" : ""; ?>>Tarjeta</option>
                                <option value="Transferencia" <?php echo isset($reservacion) && $reservacion->metodo_pago === "Transferencia" ? "selected" : ""; ?>>Transferencia</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Observaciones:</label>
                            <textarea id="observaciones" class="form-control" rows="2"><?php echo isset($reservacion) ? $reservacion->observaciones : "sin"; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-between">
        <a href="/admin/recepcion" class="btn btn-danger">Regresar</a>
        <button id="reservarHabitacion" class="btn btn-success">Agregar Registro</button>
    </div>
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
<?php //debuguear($reservacion); ?>
<?php include_once __DIR__ . '/modalCrearCliente.php'; ?>