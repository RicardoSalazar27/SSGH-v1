<div class="content-header">
  <div class="container">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?php echo $titulo; ?></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Layout</a></li>
          <li class="breadcrumb-item active">Top Navigation</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container">
    
    <!-- Primera fila: Card que ocupa toda la fila con diseño mejorado -->
    <div class="row">
    <div class="col-12">
        <div class="card card-success card-outline">
        <div class="card-header">
            <h5 class="m-0">Datos de la habitación</h5>
        </div>
        <div class="card-body">
            <div class="row">
            <!-- Primera columna -->
            <div class="col-md-3">
                <p><strong class="text-primary">Nombre</strong></p>
                <p><?php echo $habitacion->numero;?></p>
                <p><strong class="text-primary">Detalles</strong></p>
                <p><?php echo $habitacion->id_categoria->servicios_incluidos;?></p>
            </div>
            
            <!-- Segunda columna -->
            <div class="col-md-3">
                <p><strong class="text-primary">Piso</strong></p>
                <p><?php echo $habitacion->id_nivel->nombre;?></p>
                <p><strong class="text-primary">Precio</strong></p>
                <p><?php echo $habitacion->id_categoria->precio_base;?></p>
            </div>

            <!-- Tercera columna -->
            <div class="col-md-3">
                <p><strong class="text-primary">Categoria</strong></p>
                <p><?php echo $habitacion->id_categoria->nombre ?></p>
                <p><strong class="text-primary">Estado</strong></p>
                <span class="badge bg-success"><?php echo $habitacion->id_estado_habitacion->nombre;?></span>
            </div>

            <!-- Cuarta columna -->
            <div class="col-md-3">
                <p><strong class="text-primary">Capacidad</strong></p>
                <p><?php echo $habitacion->id_categoria->capacidad_maxima;?> personas</p>
                <p><strong class="text-primary">Tipo Cama</strong></p>
                <p><?php echo $habitacion->id_categoria->tipo_cama;?></p>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>


    <!-- Segunda fila: Dos cards en columnas separadas -->
    <div class="row">
    <div class="col-lg-6">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h5 class="card-title m-0">Datos del cliente</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <p><strong class="text-dark">Nombre</strong></p>
                    <input type="text" class="form-control" value="iri EL NOMBRE">
                </div>
                <div class="row">
                    <!-- Primera columna-->
                    <div class="col-md-3">
                        <label for="tipo_documento" class="text-dark"><strong>Tipo Documento</strong></label>
                        <input type="text" class="form-control" id="tipo_documento" value="va dpciemo">
                        <label for="nit" class="text-dark"><strong>NIT</strong></label>
                        <input type="text" class="form-control" id="nit" value="va nit o dni">
                    </div>
                    <div class="col-md-3">
                        <label for="documento" class="text-dark"><strong>Documento</strong></label>
                        <input type="text" class="form-control" id="documento" value="va dpciemo">
                        <label for="direccion" class="text-dark"><strong>Direccion</strong></label>
                        <input type="text" class="form-control" id="direccion" value="va nit o dni">
                    </div>
                </div>
                <div class="row">
                    <label for="correo">Correo</label>
                    <input type="email" class="form-control" id="correo" placeholder="correo@ejemplo.com">
                </div>
                <div class="row">
                    <label for="telefono">Telefono</label>
                    <input type="text" class="form-control" id="telefono">
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card card-success card-outline">
        <div class="card-header">
            <h5 class="card-title m-0">Featured</h5>
        </div>
        <div class="card-body">
            <h6 class="card-title">Special title treatment</h6>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
        </div>
    </div>
    </div>


  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
