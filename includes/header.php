<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archives MINMIDT</title>
    <!-- Bootstrap CSS local -->
    <!-- <link rel="stylesheet" href= "/assets/css/bootstrap.min.css">-->
    <!--<link rel="stylesheet" href="assets/css/style.css">-->


<link href=" http://<?=$_SERVER['HTTP_HOST'] ?>/archives-minmidt/assets/css/bootstrap.min.css" rel="stylesheet">
<link href=" http://<?=$_SERVER['HTTP_HOST'] ?>/archives-minmidt/assets/css/style.css" rel="stylesheet">

    <!-- Ou utilisez un CDN en fallback -->
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">-->


</head>
<body>
    <header class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="http://<?=$_SERVER['HTTP_HOST'] ?>/archives-minmidt/assets/img/minmidt-logo.png" alt="Logo MINMIDT" height="40">
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto" >
                    <li class="nav-item">
                        <a class="nav-link" href="http://<?=$_SERVER['HTTP_HOST'] ?>/archives-minmidt/index.php" style="color: white;">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://<?=$_SERVER['HTTP_HOST'] ?>/archives-minmidt/arretes.php" style="color: white;">Arrêtés</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://<?=$_SERVER['HTTP_HOST'] ?>/archives-minmidt/decisions.php" style="color: white;">Décisions</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://<?=$_SERVER['HTTP_HOST'] ?>/archives-minmidt/decrets.php" style="color: white;">Décrets</a>
                    </li>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php if ($_SESSION['role'] === 'Administrateur'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="http://<?=$_SERVER['HTTP_HOST'] ?>/archives-minmidt/admin/dashboard.php" style="color: white;">Dashboard Admin</a>
                            </li>
                        <?php elseif ($_SESSION['role'] === 'CSDACL'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="csdacl/dashboard.php" style="color: white;">Dashboard CSDACL</a>
                            </li>
                        <?php elseif ($_SESSION['role'] === 'Le personnel des archives'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="personnel-archives/dashboard.php" style="color: white;">Dashboard PA</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                
                <?php if (isLoggedIn()): ?>
                    <div class="d-flex">
                        <span class="navbar-text me-3">
                            <?php echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']; ?>
                        </span>
                        <a href="http://<?=$_SERVER['HTTP_HOST'] ?>/archives-minmidt/logout.php" class="btn btn-outline-light" style="color: white;">Déconnexion</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
   <!-- 1--> <main class="container my-4">


         <script src="http://<?=$_SERVER['HTTP_HOST'] ?>/archives-minmidt/assets/js/bootstrap.bundle.min.js"></script> 

</body><!-- 2-->