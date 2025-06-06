<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <!-- Logo de la empresa -->
      <img src="/build/img/<?php echo $hotel->img;?>.png" alt="Logo" class="img-fluid" style="height: 120px;">
      <!-- Nombre de la empresa debajo del logo (en un bloque) -->
      <div class="mt-2">
        <a href="/login" class="h2"><?php echo $hotel->nombre;?></a>
      </div>
    </div>
    <div class="card-body">
      <p class="login-box-msg">¿Olvidaste tu contraseña? Aquí puedes recuperarla fácilmente.</p>
      <form action="recover-password.html" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Solicitar nueva contraseña</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <p class="mt-3 mb-1">
        <a href="/login">Iniciar Sesión</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->