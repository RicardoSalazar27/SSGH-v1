<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <did class="col-sm-6">
        <div class="d-flex align-items-center">
          <i class="nav-icon fas fa-home mr-2"></i>
          <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
        </div>
      </did>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item active">Inicio</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->

<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
          <div class="inner">
            <h3><?php echo $totalHabitaciones;?></h3>
            <p>Total de habitaciones</p>
          </div>
          <div class="icon">
            <i class="fas fa-bed"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
          <div class="inner">
            <h3><?php echo $totalHabitacionesDisponibles;?></h3>
            <p>Habitaciones disponibles</p>
          </div>
          <div class="icon">
            <i class="fas fa-bed"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-warning">
          <div class="inner">
            <h3><?php echo $totalHabitacionesOcupadas;?></h3>
            <p>Habitaciones Ocupadas</p>
          </div>
          <div class="icon">
            <i class="fas fa-bed"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
      <!-- ./col -->
      <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-danger">
          <div class="inner">
            <h3><?php echo $totalHabitacionesReservadasHoy; ?></h3>
            <p>Habitaciones Reservadas Hoy</p>
          </div>
          <div class="icon">
            <i class="fas fa-bed"></i>
          </div>
          <!-- <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
        </div>
      </div>
      <!-- ./col -->
    </div>
    <!-- /.row -->

    <!-- Nueva fila después de los tabs -->
    <div class="row">
      <!-- Primera mitad de la pantalla -->
      <div class="col-lg-6 col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Estaditicas</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="chart">
            <select id="periodoSelect" class="form-control">
    <option value="enero-junio">Enero - Junio</option>
    <option value="julio-diciembre">Julio - Diciembre</option>
</select>
              <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Segunda mitad de la pantalla -->
      <div class="col-lg-6 col-12">
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title">Reservaciones para Hoy</h3>
          </div>
          <div class="card-body">
            <!-- Contenedor con scroll -->
            <div style="max-height: 300px; overflow-y: auto;">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Cliente</th>
                    <th>Habitaciones</th>
                    <th>Fecha de Entrada</th>
                    <th>Fecha de Salida</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($reservas as $reserva): ?>
                    <tr>
                      <td><?= $reserva->cliente_nombre . ' ' . $reserva->cliente_apellidos ?></td>
                      <td><?= $reserva->habitaciones ?></td>
                      <td><?= date('d/m/Y', strtotime($reserva->fecha_entrada)) ?></td>
                      <td><?= date('d/m/Y', strtotime($reserva->fecha_salida)) ?></td>
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
