    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->


    <!-- Navbar start -->
    <div class="container-fluid fixed-top">
        <div class="container-fluid topbar bg-success  d-lg-block">
            <div class="d-flex justify-content-between">
                <div class="top-info ps-2">
                    <small class="me-3"><i class="fas fa-solid fa-phone-volume me-2 text-secondary"></i> <a href="#" class="text-white">+216 99 847 516</a></small>
                    <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">RobotShopAcademy@gmail.com</a></small>
                    <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">19 Rue El Jahedh ,Nabeul</a></small>
                </div>
            </div>
        </div>

        <div class="container px-0">
            <nav class="navbar navbar-light bg-white navbar-expand-xl">
                   <!-- Logo -->
        <a class="navbar-brand" href="#">
            <img src="{{ asset('img/mylogo.png') }}" alt="logo" class="img-fluid" style="width: 55vw; height: auto;">
        </a>
        <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars text-primary"></span>
        </button>
        <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                <div class="navbar-nav mx-auto">
                        <a href="/" class="nav-item nav-link active">Acceuil</a>
                        <a href="/products" class="nav-item nav-link active">produits</a>
                         <!-- Menu déroulant pour les catégories -->
                        <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle active" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Catégories
                        </a>
                <div class="dropdown-menu m-0 bg-secondary rounded-0" aria-labelledby="categoriesDropdown" id="categoriesMenu">
                            <!-- Les catégories seront injectées ici dynamiquement -->
                            @include('products.categories_js')
                            <span class="dropdown-item">Chargement des catégories...</span>
                </div>
                </div>
                        <a href="/videos" class="nav-item nav-link active">formations</a>
                        <a href="/contact" class="nav-item nav-link active">Contact</a>
        </div>

                    <div class="d-flex m-3 me-0">
                        <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                        <a href="/cart" class="position-relative me-3 text-success">
                            <i class="fa fa-shopping-cart fa-2x"></i>
                            <span class="position-absolute bg-secondary text-dark rounded-circle d-flex align-items-center justify-content-center px-1"
                                  style="top: -5px; left: 15px; height: 20px; min-width: 20px;">0</span>
                        </a>
                    <!-- Icône Mon Compte avec Dropdown -->
                    <a class=" text-success p-sm-2 py-2 px-0 d-inline-block  " href="#" data-bs-toggle="modal" data-bs-target="#signupModal"> s'inscrire</a>
                    </div>
                    <div class="nav-item dropdown position-relative me-4 my-auto">
                        <a href="#" class="nav-link dropdown-toggle text-success" id="accountDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user fa-2x"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" id="auth-menu">
                            <!-- Le menu est généré dynamiquement -->
                        </ul>
                    </div>
@include('partials.monCompte_js')

                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->


     <!-- Modal Search Start -->
     <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content rounded-0 border-0 p-4">
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex align-items-center">
                    <div class="input-group w-75 mx-auto d-flex">
                        <input type="search" id="searchInput" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                        <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.search_js')
    <!-- Modal Search End -->

      <!-- Modal -->
<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content rounded-0 border-0 p-4">
            <div class="modal-header border-0">
                <h3>Register</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </button>
            </div>
            <div class="modal-body">
                <div class="login">
                    <form id="registerForm" action="#" class="row">
                        @csrf
                        <div class="col-12">
                            <input type="text" class="form-control mb-3" id="signupName" name="signupName" placeholder="Name">
                        </div>
                        <div class="col-12">
                            <input type="email" class="form-control mb-3" id="signupEmail" name="signupEmail" placeholder="Email">
                        </div>
                        <div class="col-12">
                            <input type="password" class="form-control mb-3" id="signupPassword" name="signupPassword" placeholder="Password">
                        </div>
                        <div class="col-12">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control mb-3" placeholder="Confirmer le mot de passe">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">SIGN UP</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- sign up -->
@include('partials.register')
<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content rounded-0 border-0 p-4">
            <div class="modal-header border-0">
                <h3>Login</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </button>
            </div>
            <div class="modal-body">

                <form id="LoginForm" action="#" class="row">
                    @csrf
                    <div class="col-12">
                        <input type="text" class="form-control mb-3" id="loginEmail" name="loginEmail" placeholder="Email">
                    </div>
                    <div class="col-12">
                        <input type="password" class="form-control mb-3" id="loginPassword" name="loginPassword" placeholder="Password">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">LOGIN</button>
                    </div>

                </form>
                <div>
                    <a class="nav-item nav-link active  " href="#" data-bs-toggle="modal" data-bs-target="#forgetpwdModal">mot de passe oublié</a>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- login.js -->
@include('partials.login')

<div class="modal fade" id="forgetpwdModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content rounded-0 border-0 p-4">
            <div class="modal-header border-0">
                <h3>rénitialiser mot de passe</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                    @csrf
                    <form id="reset-link-form">
                        @csrf
                        <input type="email" name="email" placeholder="Email" required />
                        <button type="submit">Envoyer le lien de réinitialisation</button>
                    </form>

            </div>
        </div>
    </div>
</div>
@include('auth.forgetPassword_js')

