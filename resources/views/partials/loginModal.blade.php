<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h3 class="w-100 text-center">Connexion</h3>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
        <div class="modal-body">
          <form id="LoginForm" class="row">
            @csrf
            <div class="col-12 mb-3">
              <input type="email" class="form-control" id="loginEmail" name="loginEmail" placeholder="Adresse e-mail" required>
            </div>
            <div class="col-12 mb-4">
              <input type="password" class="form-control" id="loginPassword" name="loginPassword" placeholder="Mot de passe" required>
            </div>
            <div class="col-12 mb-3">
              <button type="submit" class="btn btn-success">Se connecter</button>
            </div>
            <div class="text-center">
              <a href="#" data-bs-toggle="modal" data-bs-target="#forgetpwdModal" data-bs-dismiss="modal">Mot de passe oublié ?</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

<!-- login.js -->
@include('partials.login')
