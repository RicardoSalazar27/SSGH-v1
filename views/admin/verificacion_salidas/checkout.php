<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <i class="nav-icon fas fa-right-from-bracket mr-2"></i>
                    <h1 class="h4 mb-0"><?php echo $titulo; ?></h1>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/index">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="/admin/index">Checkout</a></li>
                    <li class="breadcrumb-item active">Proceso de salida</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <!-- Fila principal -->
        <div class="row">
            <!-- Datos habitación -->
            <div class="col-md-4">
            <div class="card card-outline card-success mb-3">
                <div class="card-header">
                    <h3 class="card-title">Datos de la habitación</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <p class=""><strong>Habitación(es):</strong></p>
                        <p class="text-primary"><?php echo $reservaConHabitacionClienteHospedaje->numeros_habitaciones;?></p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class=""><strong>Tipo:</strong></p>
                        <p class=""><?php echo $reservaConHabitacionClienteHospedaje->categorias;?></p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class=""><strong>Costo:</strong></p>
                        <p class=""><?php echo $reservaConHabitacionClienteHospedaje->precios_base;?></p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p class=""><strong>Capacidad Maxima:</strong></p>
                        <p class=""><?php echo $reservaConHabitacionClienteHospedaje->capacidades_maximas;?></p>
                    </div>
                </div>
            </div>
            </div>
            <!-- Datos cliente -->
            <div class="col-md-4">
                <div class="card card-outline card-success mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Datos del cliente</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Nombre:</strong></p>
                            <p class="text-primary"><?php echo $reservaConHabitacionClienteHospedaje->nombre . ' ' . $reservaConHabitacionClienteHospedaje->apellidos;?></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Documento:</strong></p>
                            <p class=""><?php echo $reservaConHabitacionClienteHospedaje->documento_identidad ?? ' Sin documento';?></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Telefono:</strong></p>
                            <p class=""><?php echo $reservaConHabitacionClienteHospedaje->telefono;?></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Correo:</strong></p>
                            <p class=""><?php echo $reservaConHabitacionClienteHospedaje->correo ?? 'sin correo';?></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Datos hospedaje -->
            <div class="col-md-4">
                <div class="card card-outline card-success mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Datos hospedaje</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Fecha Entrada:</strong></p>
                            <p class="text-primary"><?php echo $reservaConHabitacionClienteHospedaje->fecha_entrada;?></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Fecha Salida:</strong></p>
                            <p class="text-primary"><?php echo $reservaConHabitacionClienteHospedaje->fecha_salida;?></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Noches:</strong></p>
                            <p class=""><?php echo $reservaConHabitacionClienteHospedaje->noches;?></p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p class=""><strong>Tiempo Rebasado:</strong></p>
                            <?php 
                            $t_rebasado = $reservaConHabitacionClienteHospedaje->tiempo_rebasado;
                            $clase = $t_rebasado ? 'text-warning' : 'text-success';
                            $mensaje = $t_rebasado ?? 'SIN TIEMPO REBASADO';
                            ?>
                            <p class="<?php echo $clase; ?>"><?php echo $mensaje; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Costos -->
        <div class="card mt-3">
            <div class="card-header bg-success"><strong>Costo del alojamiento</strong></div>
            <div class="card-body p-0">
                <table class="table table-bordered m-0 text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Costo Calculado</th>
                            <th>Descuento</th>
                            <th>Corbro Extra</th>
                            <th>Dinero adelantado</th>
                            <th>Mora/Penalidad</th>
                            <th>Por pagar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>MXN$ <?php echo $reservaConHabitacionClienteHospedaje->precio_total;?></td>
                            <td>MXN$ <?php echo $reservaConHabitacionClienteHospedaje->descuento_aplicado;?></td>
                            <td>MXN$ <?php echo $reservaConHabitacionClienteHospedaje->cobro_extra;?></td>
                            <td>MXN$ <?php echo $reservaConHabitacionClienteHospedaje->adelanto;?></td>
                            <td><input id="inputPenalidad" type="text" class="form-control form-control-sm" placeholder="—"></td>
                            <td>MXN$ <?php echo $reservaConHabitacionClienteHospedaje->precio_pendiente;?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Servicios al cuarto -->
        <div class="card mt-3">
            <div class="card-header bg-warning"><strong>Servicio al cuarto</strong></div>
            <div class="card-body p-0">
                <table class="table table-bordered m-0 text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>Producto/Servicio</th>
                            <th>Precio unitario</th>
                            <th>Cantidad</th>
                            <th>Estado</th>
                            <th>Subtotal</th>
                            <!-- <th>Acciones</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($ventasReserva)) : ?>
                            <?php foreach ($ventasReserva as $venta) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($venta->producto_nombre) ?></td>
                                    <td>MXN$<?= number_format($venta->producto_precio, 2) ?></td>
                                    <td><?= (int)$venta->producto_cantidad ?></td>
                                    <td class="<?= $venta->producto_estado == 0 ? 'text-danger font-weight-bold' : 'text-success font-weight-bold' ?>">
                                        <?= $venta->producto_estado == 0 ? 'FALTA PAGAR' : 'PAGADO' ?>
                                    </td>
                                    <td>MXN$<?= number_format($venta->producto_monto, 2) ?></td>
                                    <!-- <td>
                                        <form method="POST" action="/ruta/eliminar-producto">
                                            <input type="hidden" name="id_reserva" value="<?= $idReserva ?>">
                                            <input type="hidden" name="nombre_producto" value="<?= htmlspecialchars($venta->producto_nombre) ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td> -->
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">No hay servicios al cuarto registrados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Total a pagar + Método de pago -->
        <div class="card mt-3">
            <div class="card-body">
                <div class="row align-items-center">
                <div class="col-md-4">
                    <h5>
                        <strong>TOTAL A PAGAR:</strong> MXN$
                        <span id="totalPagar"><?php echo number_format($totalPagar, 2); ?></span>
                    </h5>
                </div>
                    <div class="col-md-4">
                        <select id="metodoPago" class="form-control" name="metodo_pago" required>
                            <option value="">Método de pago</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                    <div class="col-md-4 text-right">
                        <button id="btnTerminarReservacion" class="btn btn-success">Finalizar Reservacion</button>
                    </div>
                </div>
                <!-- Sección de efectivo y feria -->
                <div class="row d-none mt-2" id="grupoEfectivo">
                        <div class="col-md-4">
                            <label for="cantidadEfectivo" class="font-weight-bold">Cantidad con la que paga</label>
                            <input type="number" class="form-control" id="cantidadEfectivo" placeholder="Ej. 100">
                        </div>
                        <div class="col-md-4">
                            <label for="feriaCalculada" class="font-weight-bold">Feria</label>
                            <input type="text" class="form-control" id="feriaCalculada" readonly>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</section>
