<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SGH | <?php echo $hotel->nombre;?></title>
  <link rel="icon" href="/build/img/<?php echo !empty($hotel->img) ? $hotel->img : 'tulogo'; ?>.png" type="image/png">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/build/adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="/build/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/build/adminlte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">

<?php 
        //include_once __DIR__ .'/templates/header.php';
        echo $contenido;
        //include_once __DIR__ .'/templates/footer.php'; 
    ?>
<!-- jQuery -->
<script src="/build/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- SweetAletr2 -->
<script src="/build/adminlte/plugins/sweetalert2/sweetalert2.all.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./build/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./build/adminlte/dist/js/adminlte.min.js"></script>
 <!-- Personalizados -->
 <script src="/build/js/bundle.min.js"></script>
</body>
</html>