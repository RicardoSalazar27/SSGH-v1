<?php

function getPlantilla($reserva, $habitaciones, $cliente , $hotel) {

$nombreLogo = $hotel->img . '.png';
$rutaLogoAbsoluta = __DIR__ . '/../../public/build/img/' . $nombreLogo;
$rutaLogo = 'file://' . $rutaLogoAbsoluta;

$logoFinal = file_exists($rutaLogoAbsoluta) ? $rutaLogo : 'file://' . __DIR__ . '/../../public/build/img/tulogo.png';

  $contenido = '
  <body>
      <div class="receipt">
          <header>
            <div class="logo">
                <img src="'.$logoFinal.'" alt="Logo del Hotel" style="height: 100px; object-fit: contain;">
            </div>
            <div class="hotel-info" style="margin-left: 1rem; text-align: right;">
                <h1>'.$hotel->nombre.'</h1>
                <p>Fecha: '.date('d-m-Y H:i:s').'</p>
            </div>
          </header>

          <section class="huesped-info">
              <span><strong>Huésped:</strong> '.$cliente->nombre.' '.$cliente->apellidos.'</span>&nbsp;&nbsp;
              <span><strong>Correo:</strong> '.$cliente->correo.'</span>&nbsp;&nbsp;
              <span><strong>Telefono:</strong> '.$cliente->telefono.'</span>
          </section>

          <table class="detalle-habitacion details-table">
              <thead>
                  <tr>
                      <th>Habitación(es)</th>
                      <th>F. Entrada</th>
                      <th>F. Salida</th>
                      <th>Piso</th>
                      <th>Categoría</th>
                      <th>Precio * Noche</th>
                      <th>Precio Total</th>
                  </tr>
              </thead>
              <tbody>';
              
  foreach ($habitaciones as $habitacion) {
      $contenido .= '
          <tr>
              <td>'.$habitacion->numero.'</td>
              <td>'.$habitacion->fecha_entrada.'</td>
              <td>'.$habitacion->fecha_salida.'</td>
              <td>'.$habitacion->nivel.'</td>
              <td>'.$habitacion->categoria.'</td>
              <td>MXN$'.$habitacion->precio.'</td>
              <td>MXN$'.$habitacion->precio_total.'</td>
          </tr>';
  }

  $contenido .= '</tbody>
          </table>

          <section class="resumen">
              <p><strong>Costo Neto:</strong> MXN$'.$reserva->precio_total.'</p>
              <p><strong>Costo Extra:</strong> MXN$'.$reserva->cobro_extra.'</p>
              <p><strong>Descuento:</strong> MXN$'.$reserva->descuento_aplicado.'</p>
              <p><strong>Costo Total:</strong> MXN$'.($reserva->precio_total + $reserva->cobro_extra - $reserva->descuento_aplicado).'</p>
              <p><strong>Dinero Adelantado:</strong> MXN$'.$reserva->adelanto.'</p>
              <p><strong>Falta por pagar:</strong> MXN$'.($reserva->precio_total + $reserva->cobro_extra - $reserva->descuento_aplicado - $reserva->adelanto).'</p>
              </section>  

          <section class="indicaciones">
                    <h3>Indicaciones Importantes</h3>
                    <ul>
                      <li>Horario de check-in: A partir de las 14:00 hrs y Horario de check-out: Hasta las 12:00 hrs..</li>
                      <li>No se permiten mascotas en las habitaciones.</li>
                      <li>Por favor conserve este recibo durante su estancia.</li>
                      <li>El pago restante debe realizarse al momento de la salida.</li>
                      <li>Prohibido fumar dentro de las habitaciones.</li>
                    </ul>
           </section>

          <footer>
              <p id="ubicacion">'.$hotel->ubicacion.'</p>
              <p id="telefono">Tel: '.$hotel->telefono.'</p>
              <p id="correo-hotel">Correo: '.$hotel->correo.'</p>
          </footer>
      </div>
  </body>
  </html>';

  return $contenido;
}

?>
