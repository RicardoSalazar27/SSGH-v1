<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="index3.html" class="brand-link" style="text-decoration: none;">
        <img src="/build/img/<?php echo $hotel->img;?>.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?php echo $hotel->nombre;?></span>
    </a>

    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="/build/img/<?php echo $usuario->img;?>.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block" style="text-decoration: none;"><?php echo $usuario->nombre . ' ' . $usuario->apellido; ?></a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <?php if($_SESSION['rol_id'] == "1" || $_SESSION['rol_id'] == "2") {?>
                    <!-- Inicio -->
                    <li class="nav-item">
                        <a href="/admin/index" class="nav-link">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Inicio</p>
                        </a>
                    </li>

                    <!-- Reserva -->
                    <li class="nav-item">
                        <a href="/admin/reservaciones" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Reserva</p>
                        </a>
                    </li>
                <?php };?>

                
                <?php if($_SESSION['rol_id'] == "1" || $_SESSION['rol_id'] == "2" || $_SESSION['rol_id'] == "3"){?>
                <!-- Recepción -->
                <li class="nav-item">
                    <a href="/admin/recepcion" class="nav-link">
                        <i class="nav-icon fas fa-concierge-bell"></i>
                        <p>Recepción</p>
                    </a>
                </li>
                <?php };?>

                <?php if ($_SESSION['rol_id'] == "1" || $_SESSION['rol_id'] == "2"){ ?>
                    <!-- Punto de Venta -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cash-register"></i>
                            <p>
                                Punto de Venta
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="./index.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Vender Productos</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./index2.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Catálogo de Productos</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Verificación de Salidas -->
                    <li class="nav-item">
                        <a href="/admin/salidas" class="nav-link">
                            <i class="nav-icon fas fa-check-circle"></i>
                            <p>Verificación de Salidas</p>
                        </a>
                    </li>

                    <!-- Clientes -->
                    <li class="nav-item">
                        <a href="/admin/clientes" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Clientes</p>
                        </a>
                    </li>
                <?php };?>

                <?php if($_SESSION['rol_id'] == "1") {?>
                    <!-- Reportes -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>
                                Reportes
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="./index.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Reporte Diario</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./index2.html" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Reporte Mensual</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Usuarios -->
                    <li class="nav-item">
                        <a href="/admin/usuarios" class="nav-link">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>

                    <!-- Configuración -->
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Configuración
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/admin/configuracion/informacion" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Información Hotel</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/configuracion/habitaciones" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Habitaciones</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/configuracion/categorias" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Categorías</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/admin/configuracion/niveles" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Niveles</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php };?>
            </ul>
        </nav>
    </div>
</aside>