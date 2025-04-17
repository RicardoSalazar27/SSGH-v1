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

                <!-- Tabs -->
                <ul class="nav nav-tabs" id="tabsReporte" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-reservas-tab" data-toggle="tab" href="#tab-reservas" role="tab">Tabla alquiler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tab-servicios-tab" data-toggle="tab" href="#tab-servicios" role="tab">Tabla servicio a la habitación y venta directa</a>
                    </li>
                </ul>

                <!-- Contenido de las tabs -->
                <div class="tab-content pt-3" id="tabsReporteContent">

                    <!-- Tab de Reservas -->
                    <div class="tab-pane fade show active" id="tab-reservas" role="tabpanel">

                        <!-- Resumen Totales -->
                        <div class="row text-center mb-3">
                            <div class="col-md-4">
                                <h6>TOTAL VENTAS/SERVICIOS</h6>
                                <p><strong>MXN$<?php echo $ventasServicios;?></strong></p>
                            </div>
                            <div class="col-md-4">
                                <h6>TOTAL RESERVACIÓN</h6>
                                <p><strong>MXN$<?php echo $totalReservaciones;?></strong></p>
                            </div>
                            <div class="col-md-4">
                                <h6>TOTAL</h6>
                                <p><strong>MXN$<?php echo $totalTablaAlquiler;?></strong></p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tablaReservas" class="table table-bordered table-hover">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>No. Reserva</th>
                                        <th>Habitación(es)</th>
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
                                    <?php foreach ($reservas as $i => $reserva): ?>
                                        <tr class="text-center">
                                            <td><?= $i + 1 ?></td>
                                            <td><?= $reserva->No_Reserva ?></td>
                                            <td><?= $reserva->Habitaciones ?></td>
                                            <td>MXN$<?= $reserva->Descuento ?></td>
                                            <td>MXN$<?= $reserva->Cobro_Extra ?></td>
                                            <td>MXN$<?= $reserva->Adelanto ?></td>
                                            <td>MXN$<?= $reserva->Penalidad ?></td>
                                            <td>MXN$<?= $reserva->Ventas_Servicios ?></td>
                                            <td><strong>MXN$<?= $reserva->Total ?></strong></td>
                                            <td><?= $reserva->Tiempo_Rebasado ?></td>
                                        </tr>
                                    <?php endforeach ?>
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
                                <p><strong>MXN$<?php echo $ventasPublico;?></strong></p>
                            </div>
                            <div class="col-md-4">
                                <h6>TOTAL VENTA/SERVICIOS RESERVACION</h6>
                                <p><strong>MXN$<?php echo $ventasServicios;?></strong></p>
                            </div>
                            <div class="col-md-4">
                                <h6>TOTAL</h6>
                                <p><strong>MXN$<?php echo $TotalVentasServiciosProductosDirectosOReservas;?></strong></p>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="tablaServicios" class="table table-bordered table-hover">
                                <thead class="thead-light text-center">
                                    <tr>
                                        <th>#</th>
                                        <th>Pago No.</th>
                                        <th>Tipo</th>
                                        <th>Habitación</th>
                                        <th>Artículo</th>
                                        <th>Cantidad</th>
                                        <th>Precio unitario</th>
                                        <th>Total</th>
                                        <th>Hora</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($ventas as $index => $venta): ?>
                                    <tr class="text-center">
                                        <td><?= $index + 1 ?></td>
                                        <td><?= $venta->Identificador ?></td>
                                        <td><?= $venta->Tipo ?></td>
                                        <td><?= $venta->Habitacion ?></td>
                                        <td><?= $venta->Articulo ?></td>
                                        <td><?= $venta->Cantidad ?></td>
                                        <td>MXN$<?= number_format($venta->Precio_Unitario, 2) ?></td>
                                        <td>MXN$<?= number_format($venta->Total, 2) ?></td>
                                        <td><?= $venta->Hora ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div> <!-- /tab-content -->

            </div>
        </div>
    </div>
</section>


