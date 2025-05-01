<div class="container d-flex justify-content-center align-items-center vh-100">
  <div class="text-center">
    <!-- Logo del hotel -->
    <img src="/build/img/<?php echo !empty($hotel->img) ? $hotel->img : 'tulogo'; ?>.png" alt="Logo del Hotel" class="img-fluid mb-3" style="max-height: 150px;">
    
    <!-- Nombre del hotel -->
    <h2 class="mb-4"><?php echo $hotel->nombre; ?></h2>

    <!-- Mensaje formal -->
    <div class="alert alert-info">
      <h5 class="mb-2">Recuperación de Contraseña</h5>
      <p class="mb-0">Para restablecer tu contraseña, por favor contacta al administrador del hotel.</p>
    </div>

    <p class="text-muted">Él podrá ayudarte a recuperar el acceso a tu cuenta de manera segura.</p>

    <!-- Botón para volver al login -->
    <a href="/login" class="btn btn-outline-primary mt-3">Volver al Inicio de Sesión</a>
  </div>
</div>
