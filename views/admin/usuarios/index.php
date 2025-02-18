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
                            <button id="btnAgregarUsuario" class="btn btn-primary" data-toggle="modal" data-target="#usuariosModal">Agregar Nuevo</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="datatable_users" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nombre</th>
                                    <th>Apellidos</th>
                                    <th>Dirección</th>
                                    <th>Email</th>
                                    <th class="no-export">password</th>
                                    <th class="no-export">password2</th>
                                    <th>Fecha Nacimiento</th>
                                    <th>Rol</th>
                                    <th>Estatus</th>
                                    <th>Fecha de Creación</th>
                                    <th>Ultimo Acceso</th>
                                    <th class="text-center">confirmado</th>
                                    <th class="no-export">Passord Nuevo</th>
                                    <th class="no-export">Password Actual</th>
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
</section>

<?php include_once __DIR__ . '/modal.php';?>