<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SGH | <?php echo $titulo;?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Font Awesome (Local) -->
    <link rel="stylesheet" href="/build/adminlte/plugins/fontawesome-free/css/all.min.css">
    
    <!-- AdminLTE CSS (Local) -->
    <link rel="stylesheet" href="/build/adminlte/dist/css/adminlte.min.css">
    
    <!-- DataTables Bootstrap 4 CSS (Local) -->
    <link rel="stylesheet" href="/build/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    
    <!-- Bootstrap 4 (Local) -->
    <link rel="stylesheet" href="/build/adminlte/plugins/bootstrap/css/bootstrap.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Header -->
        <?php include_once __DIR__ .'/templates/header.php'; ?>

        <!-- Sidebar -->
        <?php include_once __DIR__ .'/templates/sidebarmenu.php'; ?>

        <!-- Contenido principal -->
        <div class="content-wrapper">
            <?php echo $contenido; ?>
        </div>

        <!-- Sidebar derecho opcional -->
        <aside class="control-sidebar control-sidebar-dark">
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>

        <!-- Footer -->
        <?php include_once __DIR__ .'/templates/footer.php'; ?>
    </div>

    <!-- jQuery (Local) -->
    <script src="/build/adminlte/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap 4 (Local) -->
    <script src="/build/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables (Local) -->
    <script src="/build/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/build/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

    <!-- AdminLTE App (Local) -->
    <script src="/build/adminlte/dist/js/adminlte.min.js"></script>

    <!-- DataTables Buttons (CDN, no están en AdminLTE) -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- Full Calendar (CDN, no está en AdminLTE) -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <!-- SweetAlert2 (Local) -->
    <script src="/build/adminlte/plugins/sweetalert2/sweetalert2.all.min.js"></script>

    <!-- Personalizados -->
    <script src="/build/js/bundle.min.js"></script>

</body>
</html>
