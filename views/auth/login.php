<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="/login" class="h1"><b>Tu</b>Nombre</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg"><?php echo $titulo;?></p>
      <div id="mensaje-resultado" class="alert alert-dismissible" style="display: none;"></div>
      <form action="/login" method="post">
        <div class="input-group mb-3">
          <input 
            type="email" 
            class="form-control" 
            placeholder="Tu Email"
            id="email"
            name="email"
          >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input 
            type="password" 
            class="form-control" 
            placeholder="Tu Password"
            id="password"
            name="password"
          >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
            <button id="btnIniciarSesion" type="submit" class="btn btn-primary btn-block">Iniciar Sesion</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- /.social-auth-links -->
      <p class="d-flex justify-content-between mt-1">
        <a href="/olvide">Olvide Mi Contraseña</a>
        <a href="/registro" class="text-center">Crear Cuenta</a>  
      </p>

    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->