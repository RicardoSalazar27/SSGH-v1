<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SGH | <?php echo $titulo;?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="/build/resources/plugins/fontawesome-free/css/all.min.css">
    <!-- Font Awesome (versión 6.0.0-beta3) para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- AdminLTE CSS principal -->
    <link rel="stylesheet" href="/build/resources/dist/css/adminlte.min.css">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
    <!-- Boostrap 5 CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

    <!-- Personalizados -->
    <script src="/build/js/bundle.min.js"></script>

    <!-- jQuery -->
    <script src="/build/resources/plugins/jquery/jquery.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="/build/resources/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

    <!-- AdminLTE App para la versión 3.1.0 -->
    <script src="/build/resources/dist/js/adminlte.min.js"></script>

    <!-- DataTables Buttons JS -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <!-- Full Calendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <!-- SweetAlert2-->
    <script src="/build/resources/plugins/sweetalert2/sweetalert2.all.min.js"></script>
</body>
</html>