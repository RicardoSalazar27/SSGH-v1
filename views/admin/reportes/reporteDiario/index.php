<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="nav-icon fas fa-file-invoice mr-2"></i>
                    <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/index">Inicio</a></li>
                    <li class="breadcrumb-item active">Reporte Diario</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">

        <!-- Filtros -->
        <div class="card">
            <div class="card-body">

                <!-- Filtros: Usuario y Fecha -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="usuario">Seleccionar usuario:</label>
                        <select id="usuario" name="usuario" class="form-control">
                            <option value="">Todos</option>
                            <?php foreach($usuariosReportes as $usuarioReporte): ?>
                                <option value="<?= $usuarioReporte->id ?>"><?= $usuarioReporte->nombre . ' ' . $usuarioReporte->apellido;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="fecha">Seleccionar fecha:</label>
                        <input type="date" id="fecha" name="fecha" class="form-control" value="<?php echo $fecha_hoy;?>">
                    </div>
                </div>


                <!-- Tabs -->
                <ul class="nav nav-tabs" id="tabsReporte" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-reservas-tab" data-toggle="tab" href="#tab-reservas" role="tab">RESERVACIONES</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-servicios-tab" data-toggle="tab" href="#tab-servicios" role="tab">SERVICIOS/VENTAS A HABITACION Y VENTAS DIRECTAS</a>
                    </li>
                </ul>

                <!-- Contenido de las tabs -->
                <div class="tab-content pt-3" id="tabsReporteContent">

                    <!-- Tab de Reservas -->
                    <div class="tab-pane fade show active" id="tab-reservas" role="tabpanel">

                    <div class="row text-center mb-3">
                        <div class="col-md-4">
                            <h6>TOTAL VENTAS/SERVICIOS</h6>
                            <p><strong id="totalVentas">MXN$</strong></p>
                        </div>
                        <div class="col-md-4">
                            <h6>TOTAL RESERVACIÓN</h6>
                            <p><strong id="totalReservas">MXN$</strong></p>
                        </div>
                        <div class="col-md-4">
                            <h6>TOTAL</h6>
                            <p><strong id="totalGeneral">MXN$</strong></p>
                        </div>
                    </div>

                        <div class="table-responsive">
                            <table id="tablaReservas" class="table table-bordered table-hover">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>No. Reserva</th>
                                        <th>Huesped</th><!-- borrar es pruba -->
                                        <th>Habitación(es)</th>
                                        <th>Total Neto</th>
                                        <th>Descuento</th>
                                        <th>Cobro Extra</th>
                                        <th>Adelanto</th>
                                        <th>Penalidad</th>
                                        <th>Ventas/Servicios</th>
                                        <th>Total</th>
                                        <th>Tiempo Rebasado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab de Servicios/Venta directa -->
                    <div class="tab-pane fade" id="tab-servicios" role="tabpanel">
                        <!-- Resumen Totales -->
                        <div class="row text-center mb-3">
                            <div class="col-md-4">
                                <h6>TOTAL VENTA DIRECTA</h6>
                                <p><strong id="totalVentasDirecta">MXN$</strong></p>
                            </div>
                            <div class="col-md-4">
                                <h6>TOTAL VENTA/SERVICIOS RESERVACION</h6>
                                <p><strong id="totalVentasReservacion">MXN$</strong></p>
                            </div>
                            <div class="col-md-4">
                                <h6>TOTAL</h6>
                                <p><strong id="totalVentasGeneral">MXN$</strong></p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tablaServicios" class="table table-bordered table-hover">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Venta No.</th>
                                        <th>Tipo</th>
                                        <th>Reserva No.</th>
                                        <th>Habitación(es)</th>
                                        <th>Artículo</th>
                                        <th>Cantidad</th>
                                        <th>Precio unitario</th>
                                        <th>Total</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> <!-- /tab-content -->
            </div>
        </div>
    </div>
</section>


