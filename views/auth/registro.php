<div class="register-box">
  <div class="card card-outline card-primary">
  <div class="card-header text-center">
      <!-- Logo de la empresa -->
      <img src="/build/img/<?php echo !empty($hotel->img) ? $hotel->img : 'tulogo'; ?>.png" alt="Logo" class="img-fluid" style="height: 100px;">
      <!-- Nombre de la empresa debajo del logo (en un bloque) -->
      <div class="mt-2">
        <a href="/login" class="h3"><?php echo $hotel->nombre;?></a>
      </div>
    </div>
    <div class="card-body">
      <p class="login-box-msg"><?php echo $titulo; ?></p>
      <div id="mensaje-resultado" class="alert alert-dismissible" style="display: none;"></div>
      <form action="/login" method="post">
        <div class="input-group mb-3">
          <input 
            type="text" 
            class="form-control"
            id="nombre"
            name="nombre" 
            placeholder="Tu Nombre"
          />
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input 
            type="text" 
            class="form-control"
            id="apellidos"
            name="apellidos" 
            placeholder="Tus Apellidos"
          />
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input 
            type="email"
            class="form-control" 
            id="email"
            name="email"    
            placeholder="Email">
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
            id="password"
            name="password"
            placeholder="Tu Contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input 
            type="password" 
            class="form-control"
            id="password2"
            name="password2"
            placeholder="Repite Tu Contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input 
            type="text" 
            class="form-control"
            id="telefono"
            name="telefono" 
            placeholder="Teléfono">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
        <input 
          type="text" 
          class="form-control"
          id="direccion"
          name="direccion" 
          placeholder="Dirección">
        <div class="input-group-append">
          <div class="input-group-text">
            <span class="fas fa-map-marker-alt"></span> <!-- Icono de marcador de mapa -->
          </div>
        </div>
      </div>


        <div class="row">
          <div class="col-12">
            <button id="crear-cuenta" type="submit" class="btn btn-primary btn-block">Crear Cuenta</button>
          </div>
        </div>
      </form>
      <p class="d-flex justify-content-between mt-1">
        <a href="/login">Inicia Sesión</a>
        <a href="/olvide">¿Olvidaste tu contraseña?</a>
      </p>

    </div>
  </div>
</div>
