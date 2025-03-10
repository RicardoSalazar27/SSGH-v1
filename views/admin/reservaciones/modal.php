<div id="modalReservacion" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reservación</h5>
                <button type="button" class="btn-close" data-close="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Selecciona la fecha y confirma tu reservación.</p>
                <input type="date" id="start" class="form-control" min="<?php echo date('Y-m-d'); ?>"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-close="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="confirmReservation">Confirmar</button>
            </div>
        </div>
    </div>
</div>
