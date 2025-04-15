<!-- Modal d'Alerte -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-4">
        <div class="modal-header align-items-center" id="alertModalHeader">
          <div class="d-flex align-items-center">
            <div id="alertModalIcon" class="me-3 fs-3"></div>
            <h5 class="modal-title fw-semibold" id="alertModalLabel">Alerte</h5>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body text-dark fs-6" id="alertModalMessage">
          <!-- Message ici -->
        </div>
      </div>
    </div>
  </div>

@include('layouts.alerts_js')
