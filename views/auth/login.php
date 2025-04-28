<div class="login-box">
  <!-- Logo de tu empresa -->
  <div class="text-center mb-2">
    <img src="/build/img/SASEDIGITAL.png" alt="Logo Empresa" class="img-fluid" style="height: 120px;">
  </div>

  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <!-- Logo del Hotel -->
      <img src="/build/img/<?php echo $hotel->img;?>.png" alt="Logo Hotel" class="img-fluid" style="height: 120px;">
      <div class="mt-2">
        <a href="/login" class="h2"><?php echo $hotel->nombre;?></a>
      </div>
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
          <div class="col-12">
            <button id="btnIniciarSesion" type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
          </div>
        </div>
      </form>
      <p class="d-flex justify-content-between mt-1">
        <a href="/olvide">Olvidé Mi Contraseña</a>
        <a href="/registro" class="text-center">Crear Cuenta</a>  
      </p>
    </div>
  </div>

  <!-- Texto de copyright -->
  <div class="text-center mt-4">
    <small class="text-muted">&copy; <?php echo date('Y'); ?> SASEDIGITAL. Todos los derechos reservados.</small><br>
    <a class="text-muted"><small>Política de Privacidad</small></a>
  </div>
</div>
