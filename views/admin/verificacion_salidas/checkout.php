<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="nav-icon fas fa-right-from-bracket mr-2"></i>
                    <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/index">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="/admin/index">Checkout</a></li>
                    <li class="breadcrumb-item active">Proceso de salida</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <!-- Fila principal -->
        <div class="row">
            <!-- Datos habitación -->
            <div class="col-md-4">
            <div class="card card-outline card-success mb-3">
                <div class="card-header">
                    <h3 class="card-title">Datos de la habitación</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <p class=""><strong>Habitación(es):</strong></p>
                        <p class="text-primary">202,205</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class=""><strong>Tipo:</strong></p>
                        <p class="">Sencilla - Doble</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class=""><strong>Costo:</strong></p>
                        <p class="">MXN$360 - MXN$530</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class=""><strong>Descuento:</strong></p>
                        <p class="">MXN$360</p>
                    </div>
                </div>
            </div>
            </div>
            <!-- Datos cliente -->
            <div class="col-md-4">
                <div class="card card-outline card-success mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Datos del cliente</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Nombre:</strong></p>
                            <p class="text-primary">ALEMINERO CASTELLNAOS UTURIBIDE</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Documento:</strong></p>
                            <p class="">1004380120</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Telefono:</strong></p>
                            <p class="">782480120</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Correo:</strong></p>
                            <p class="">correo@correo.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Datos hospedaje -->
            <div class="col-md-4">
                <div class="card card-outline card-success mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Datos hospedaje</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Fecha Entrada:</strong></p>
                            <p class="text-primary">10-04-2025</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Fecha Salida:</strong></p>
                            <p class="text-primary">11-04-2025</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Noches:</strong></p>
                            <p class="">1</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Tiempo Rebasado:</strong></p>
                            <p class="text-success">Sin tiempo rebasado</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Costos -->
        <div class="card mt-3">
            <div class="card-header bg-success"><strong>Costo del alojamiento</strong></div>
            <div class="card-body p-0">
                <table class="table table-bordered m-0 text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Dinero extra</th>
                            <th>Costo calculado</th>
                            <th>Dinero adelantado</th>
                            <th>Mora/Penalidad</th>
                            <th>Por pagar</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>MXN$0</td>
                            <td>MXN$360</td>
                            <td>MXN$0</td>
                            <td><input type="text" class="form-control form-control-sm" placeholder="—"></td>
                            <td>MXN$360</td>
                            <td>dayana</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Servicios al cuarto -->
        <div class="card mt-3">
            <div class="card-header bg-warning"><strong>Servicio al cuarto</strong></div>
            <div class="card-body p-0">
                <table class="table table-bordered m-0 text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Producto/Servicio</th>
                            <th>Precio unitario</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Agua</td>
                            <td>MXN$15</td>
                            <td>2</td>
                            <td class="text-danger font-weight-bold">FALTA PAGAR</td>
                            <td>MXN$30</td>
                            <td><button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total a pagar + Método de pago -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <h5><strong>TOTAL A PAGAR:</strong> MXN$390</h5>
                    </div>
                    <div class="col-md-4">
                        <select class="form-control" name="metodo_pago" required>
                            <option value="">Método de pago</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-right">
                        <button class="btn btn-success">Finalizar pago</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
