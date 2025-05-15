<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="nav-icon fas fa-file-import mr-2"></i>
                    <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/index">Inicio</a></li>
                    <li class="breadcrumb-item active">Recibos</li>
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
                            <h1 class="h4 mb-0">Descargar Recibos de Reservaciones</h1>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_recibos" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No. Reservación</th>
                                        <th>Huésped</th>
                                        <th>Habitaciones</th>
                                        <th>Teléfono</th>
                                        <th>Fecha Entrada</th>
                                        <th>Fecha Salida</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reservas as $reserva): ?>
                                        <tr>
                                            <td><?= $reserva->ID_reserva ?></td>
                                            <td><?= $reserva->cliente ?></td>
                                            <td><?= $reserva->habitaciones ?></td>
                                            <td><?= $reserva->telefono ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($reserva->fecha_entrada)) ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($reserva->fecha_salida)) ?></td>
                                            <td>
                                                <a href="/admin/recibos/pdf?id=<?= $reserva->ID_reserva ?>" class="btn btn-danger btn-sm" target="_blank" title="Generar PDF">
                                                    Descargar <i class="fas fa-file-pdf"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>