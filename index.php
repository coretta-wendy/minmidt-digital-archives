<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}
?>




<section class="hero-section position-relative text-white" style="height: 80vh; max-height: 600px;">
    <!-- Image de fond avec overlay noir - version sans marges -->
    <div class="position-absolute w-100 h-100 start-0 top-0">
        <img src="assets/img/archives-heros.jpg" alt="Archives MINMIDT"
             class="img-fluid h-100 w-100 object-fit-cover" style="object-position: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    </div>
    
    <!-- Contenu centré -->
    <div class="position-relative h-100 d-flex flex-column justify-content-center align-items-center text-center px-3 mx-auto" style="max-width: 1200px;">
        <div class="mb-4 px-2">
            <h1 class="display-4 fw-bold mb-3">Bienvenue dans les archives du MINMIDT</h1>
            <p class="lead fs-3 mb-4">Nous sommes très heureux de vous revoir !</p>
            <a href="#about" class="btn btn-primary btn-lg px-4 py-2 mt-3">À propos</a>
        </div>
    </div>
</section>

<style>
    .hero-section {
        margin-top: -25px; /* Coller parfaitement au header */
        width: 99.5vw;
        left: 50%;
        right: 50%;
        margin-left: -50vw;
        margin-right: -4vw;
        border-radius: 0 !important;
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>




<!-- 2<section class="hero-section position-relative text-white py-5 mb-4" style="height: 100vh; max-height: 600px; overflow: hidden;">
    //Image de fond avec overlay noir 
    <div class="position-absolute w-100 h-100">
        <img src="assets/img/archives-hero.jpg" alt="Archives MINMIDT" 
             class="img-fluid h-100 w-100 object-fit-cover">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
    </div>
    
    //Contenu centré par-dessus l'image 
    <div class="position-relative h-100 d-flex flex-column justify-content-center align-items-center text-center px-3">
        <div class="mb-4" style="max-width: 800px;">
            <h1 class="display-4 fw-bold mb-3">Bienvenue dans l'application d'archivage du MINMIDT</h1>
            <p class="lead fs-3 mb-4">Nous sommes très heureux de vous revoir</p>
            <a href="#about" class="btn btn-primary btn-lg px-4 py-2 mt-3">À propos</a>
        </div>
    </div>
</section>

<style>
    .object-fit-cover {
        object-fit: cover;
    }
    .hero-section {
        border-radius: 0.5rem;
    }
</style>-->


        <!-- 1 <section class="hero-section text-center py-5 bg-light rounded mb-4">
            <img src="assets/img/archives-hero.jpg" alt="Archives MINMIDT" class="img-fluid mb-4" style="max-height: 300px;">
            <h1>Bienvenue dans l'application d'archivage du MINMIDT</h1>
            <p class="lead">Nous sommes très heureux de vous revoir</p>
            <a href="#about" class="btn btn-primary mt-3">À propos</a>
        </section>-->

        <section class="documents-section mb-5">
            <h2 class="text-center mb-4">Accéder aux documents</h2>
            <div class="row"> 
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center document-card"><a href="arretes.php" class="btn btn-primary">
                        <div class="card-body">
                            <h3 class="card-title">Arrêtés</h3>
                            <p class="card-text">Consulter les arrêtés ministériels</p>
                        </div></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center document-card"><a href="decisions.php" class="btn btn-primary">
                        <div class="card-body">
                            <h3 class="card-title">Décisions</h3>
                            <p class="card-text">Consulter les décisions ministérielles</p>
                        </div></a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 text-center document-card"><a href="decrets.php" class="btn btn-primary">
                        <div class="card-body">
                            <h3 class="card-title">Décrets</h3>
                            <p class="card-text">Consulter les décrets ministériels</p>
                        </div></a>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="about-section py-5 bg-light rounded">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <img src="assets/img/about-archives.jpg" alt="À propos" class="img-fluid rounded">
                </div>
                <div class="col-md-8">
                    <h2>À propos</h2>
                    <p>Cette application vous permet de rechercher les Arrêtés, Décisions, Décrets du MINMIDT de manière simple et efficace. Notre plateforme offre un accès sécurisé aux documents officiels et facilite leur gestion.</p>
                    <p>Que vous soyez administrateur, membre du CSDACL, du personnel des archives ou du MINMIDT, vous trouverez ici tous les outils nécessaires pour travailler efficacement.</p>
                </div>
            </div>
        </section>

<?php require_once 'includes/footer.php'; ?>