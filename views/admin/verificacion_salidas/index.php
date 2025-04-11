<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="nav-icon fas fa-users mr-2"></i>
                    <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/index">Inicio</a></li>
                    <li class="breadcrumb-item active">Checkin</li>
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
                            <table id="datatable_reservacionesHoy" class="table table-striped text-center">
                                <thead>
                                    <tr>
                                        <th>Reserva No.</th>
                                        <th>Huésped</th>
                                        <th>Habitación(es)</th>
                                        <th>Estado Habitación</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody_reservacionesHoy">
                                    <?php foreach ($reservasHoy as $reserva): ?>
                                        <tr>
                                            <td><?php echo $reserva->ID_reserva; ?></td>
                                            <td><?php echo $reserva->cliente_nombre; ?></td>
                                            <td><?php echo $reserva->habitacion_numero; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $reserva->color_estado_habitacion; ?>">
                                                    <i class="fas fa-<?php echo $reserva->icono_estado_habitacion; ?>"></i>
                                                    <?php echo $reserva->nombre_estado_habitacion; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="/admin/salidas/checkin?id=<?php echo $reserva->ID_reserva; ?>" class="btn btn-success btn-sm">
                                                    Detalles
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div> <!-- /.table-responsive -->
                    </div> <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>
