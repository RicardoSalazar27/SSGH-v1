<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="nav-icon fas fa-calendar-alt mr-2"></i>
                    <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/index">Inicio</a></li>
                    <li class="breadcrumb-item active">Reservaciones</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container">
        <!-- Boton para agregar reservación -->
        <div class="d-flex justify-content-between">
            <h1 class="h4 mb-0"><?php echo ucfirst($fecha); ?></h1>
            <button id="btnAgregarReservacion" class="btn btn-primary" aria-label="Agregar nueva reservación">Nueva Reservación</button>
        </div>
        <div id='calendar'></div>
    </div>
</section>

<!-- Modal -->
<?php include_once __DIR__ . '/modal.php'; ?>
<?php include_once __DIR__ . '/modalEditar.php'; ?>
