<section class="content-header">
    <div class="container">
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
                    <li class="breadcrumb-item"><a href="/admin/puntodeventa/vender">Punto De Venta</a></li>
                    <li class="breadcrumb-item active">Procesar Venta</li>
                </ol>
            </div>
        </div>
    </div>
</section>


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
    <!-- Columna 1 -->
    <div class="col-md-2">
        <p><strong class="text-primary">Reserva No.</strong></p>
        <p><?php echo $reservacion->ID_reserva;?></p>
    </div>
    <div class="col-md-2">
        <p><strong class="text-primary">Habitacion(és).</strong></p>
        <p><?php echo $reservacion->habitaciones;?></p>
    </div>

    <!-- Columna 2 -->
    <div class="col-md-2">
        <p><strong class="text-primary">Precio</strong></p>
        <p><?php echo $reservacion->precio_total;?></p>
    </div>

    <!-- Columna 3 -->
    <div class="col-md-2">
        <p><strong class="text-primary">Huésped</strong></p>
        <p><?php echo $reservacion->cliente_nombre . ' ' . $reservacion->cliente_apellidos;?></p>
    </div>

    <!-- Columna 4 -->
    <div class="col-md-2">
        <p><strong class="text-primary">Teléfono</strong></p>
        <p><?php echo $reservacion->telefono;?></p>
    </div>

    <!-- Columna 5 nueva -->
    <div class="col-md-2">
        <p><strong class="text-primary">Fecha Salida</strong></p>
        <p><?php echo $reservacion->fecha_salida;?></p>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>

   <!-- Sección con input y botones dentro de una card -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-dark text-white mb-2">
                <div class="card-body" style="overflow: visible;">
                    <div class="row g-2 align-items-center">
                    <!-- Input de búsqueda de producto -->
                    <div class="col-md-6 col-lg-4 position-relative">
                        <input type="text" class="form-control" id="inputBuscarProducto" placeholder="Escriba código o nombre del producto">
                        <ul id="listaSugerencias" class="list-group position-absolute d-none w-100" style="z-index: 1000;"></ul>
                    </div>
                    <!-- Botón agregar -->
                    <div class="col-auto">
                        <button class="btn btn-primary">Agregar</button>
                    </div>
                    <!-- Botón terminar venta alineado a la derecha -->
                    <div class="col text-end">
                        <button class="btn btn-warning">Terminar venta</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Card que contiene la tabla de productos -->
    <div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
        <div class="card-header bg-gradient-success text-white">
            <h6 class="mb-0">Productos agregados</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table table-bordered table-hover text-center mb-0">
                <thead class="">
                <tr>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Precio Total</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
                </thead>
                <tbody id="tablaVentaProductos">
                <!-- Aquí van los productos agregados dinámicamente -->
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>
    </div>
    <!-- Fin de la nueva sección -->

  </div><!-- /.container -->
</div>
