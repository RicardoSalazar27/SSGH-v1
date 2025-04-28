<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="nav-icon fas fa-users-gear mr-2"></i>
                    <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/index">Inicio</a></li>
                    <li class="breadcrumb-item active">Usuarios</li>
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
                            <h1 class="h4 mb-0">Usuarios</h1>
                            <button id="btnAgregarUsuario" class="btn btn-primary" data-toggle="modal" data-target="#usuarioNuevoModal">Agregar Nuevo</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="datatable_users" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Foto</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Dirección</th>
                                    <th>Email</th>
                                    <th>Telefono</th>
                                    <th class="no-export">Password</th>
                                    <th>Rol</th>
                                    <th class="text-center">Estatus</th>
                                    <th class="no-export">Acciones</th> 
                                </tr>
                            </thead>
                            <tbody id="tableBody_users"></tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once __DIR__ . '/modalNuevo.php';?>
<?php include_once __DIR__ . '/modalEditar.php';?>