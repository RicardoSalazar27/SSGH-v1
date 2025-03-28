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
                    <p><?php echo $habitacion->id_categoria->precio_base;?></p>
                </div>

                <!-- Tercera columna -->
                <div class="col-md-3">
                    <p><strong class="text-primary">Categoria</strong></p>
                    <p><?php echo $habitacion->id_categoria->nombre ?></p>
                    <p><strong class="text-primary">Estado</strong></p>
                    <span class="badge bg-success"><?php echo $habitacion->id_estado_habitacion->nombre;?></span>
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
                            <label for="nombre" class="text-dark col-form-label"><strong>Nombre</strong></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nombre" placeholder="Nombre del huésped">
                                <button class="btn btn-primary" type="button">
                                    <i class="fa-solid fa-user-plus text-white"></i> <!-- Cambiar el color del ícono si es necesario -->
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Primera columna-->
                        <div class="col-md-6">
                            <label for="tipo_documento" class="text-dark col-form-label"><strong>Tipo Documento</strong></label>
                            <input type="text" class="form-control" id="tipo_documento" placeholder="Ej. DNI">
                        </div>
                        <div class="col-md-6">
                            <label for="documento" class="text-dark col-form-label"><strong>Documento</strong></label>
                            <input type="text" class="form-control" id="documento" placeholder="Ej. SASE09038ID8">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="nit" class="text-dark col-form-label"><strong>RFC</strong></label>
                            <input type="text" class="form-control" id="nit" placeholder="RFC">
                        </div>
                        <div class="col-md-6">
                            <label for="direccion" class="text-dark col-form-label"><strong>Direccion</strong></label>
                            <input type="text" class="form-control" id="direccion" placeholder="Puede ser solo ciudad">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="correo">Correo</label>
                            <input type="email" class="form-control" id="correo" placeholder="correo@ejemplo.com">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label for="telefono">Telefono</label>
                            <input type="text" class="form-control" id="telefono">
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
                            <label for="fechaEntrada" class="form-label">Fecha y hora de Entrada</label>
                            <input type="date" id="fechaEntrada" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="fechaSalida" class="form-label">Fecha y hora de Salida</label>
                            <input type="date" id="fechaSalida" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between">
                                <label class="form-label me-2">Descuento:</label>
                                <div>
                                    <input type="radio" name="tipoDescuento" value="PORCENTAJE" id="descuentoPorcentaje">
                                    <label for="descuentoPorcentaje" class="ms-1 me-2">%</label>
                                    <input type="radio" name="tipoDescuento" value="MONTO" id="descuentoMonto" checked>
                                    <label for="descuentoMonto" class="ms-1 fw-bold">MXN$</label>
                                </div>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text"> % | MXN$</span>
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
                                <input type="number" id="totalPagar" class="form-control" value="360" readonly>
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
    </div>
    <div class="row justify-content-between">
        <a href="/admin/recepcion" class="btn btn-danger">Regresar</a>
        <button class="btn btn-success">Agregar Registro</button>
    </div>
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
