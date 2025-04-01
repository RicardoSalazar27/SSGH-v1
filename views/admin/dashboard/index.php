<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0"><?php echo $titulo;?></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Starter Page</li>
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
              <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
      </div>

      <!-- Segunda mitad de la pantalla -->
      <div class="col-lg-6 col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Sección 2</h3>
          </div>
          <div class="card-body">
            Contenido de la segunda mitad.
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
