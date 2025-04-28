<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="nav-icon fas fa-cart-shopping mr-2"></i>
                    <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/index">Inicio</a></li>
                    <li class="breadcrumb-item active">Vender Productos</li>
                </ol>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h1 class="h4 mb-0">Reservaciones</h1>
                            <a href="/admin/puntodeventa/venta/directa">
                                <button class="btn btn-primary">¿Venta al público?</button>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- AQUI agregamos el wrapper para tabla responsiva -->
                        <div class="table-responsive">
                            <div class="table-responsive">
                            <tbody id="tableBody_ventaReservaciones">
                                <?php if ($habitaciones) { ?>
                                    <?php foreach ($reservaciones as $reserva): ?>
                                        <tr>
                                            <td><?php echo $reserva->ID_reserva; ?></td>
                                            <td><?php echo $reserva->habitaciones; ?></td>
                                            <td><?php echo $reserva->cliente_nombre . ' ' . $reserva->cliente_apellidos; ?></td>
                                            <td><?php echo $reserva->telefono; ?></td>
                                            <td><?php echo $reserva->fecha_salida; ?></td>
                                            <td>
                                                <a href="/admin/puntodeventa/vender/reserva?id=<?php echo $reserva->ID_reserva; ?>" class="btn btn-success btn-sm">
                                                    Vender
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay reservaciones disponibles</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                            </div>
                        </div> <!-- /.table-responsive -->
                    </div> <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>
