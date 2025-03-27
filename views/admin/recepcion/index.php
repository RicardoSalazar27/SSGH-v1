<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="nav-icon fas fa-concierge-bell mr-2"></i>
                    <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/index">Inicio</a></li>
                    <li class="breadcrumb-item active">Recepción</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <!-- Pestaña 'Todos' -->
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-todos-tab" data-toggle="pill" href="#custom-tabs-one-todos" role="tab" aria-controls="custom-tabs-one-todos" aria-selected="true">Todos</a>
                            </li>
                            <!-- Pestañas dinámicas por nivel -->
                            <?php foreach ($niveles as $nivel): ?>
                                <li class="nav-item">
                                    <a class="nav-link" id="custom-tabs-one-<?php echo $nivel->numero; ?>-tab" data-toggle="pill" href="#custom-tabs-one-<?php echo $nivel->numero; ?>" role="tab" aria-controls="custom-tabs-one-<?php echo $nivel->numero; ?>" aria-selected="false">
                                        <?php echo $nivel->nombre; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <!-- Contenido de la pestaña 'Todos' -->
                            <div class="tab-pane fade show active" id="custom-tabs-one-todos" role="tabpanel" aria-labelledby="custom-tabs-one-todos-tab">
                                Contenido de Todos
                            </div>
                            <!-- Contenido dinámico por nivel -->
                            <?php foreach ($niveles as $nivel): ?>
                                <div class="tab-pane fade" id="custom-tabs-one-<?php echo $nivel->numero; ?>" role="tabpanel" aria-labelledby="custom-tabs-one-<?php echo $nivel->numero; ?>-tab">
                                    Contenido de <?php echo $nivel->nombre; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<?php include_once __DIR__ . '/modal.php'; ?>
<?php //include_once __DIR__ . '/modalEditar.php'; ?>