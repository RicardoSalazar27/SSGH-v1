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
                    <li class="breadcrumb-item active">Clientes</li>
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
                            <h1 class="h4 mb-0">Catalogo de Clientes</h1>
                            <!-- <button id="btnAgregarCliente" class="btn btn-primary" data-toggle="modal" data-target="#clientesModal">Agregar Nuevo</button> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="datatable_clientes" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Telefono</th>
                                    <th>Correo</th>
                                    <th>Direccion</th>
                                    <th>DNI</th>
                                    <th class="no-export">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody_clientes"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once __DIR__ . '/modalEditar.php';?>