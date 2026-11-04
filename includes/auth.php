<?php
require_once 'config.php';

// Vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Vérifier le rôle de l'utilisateur
function checkRole($requiredRole) {
    if (!isLoggedIn() || $_SESSION['role'] !== $requiredRole) {
        redirect('login.php');
    }
}

// Authentification
if (isset($_POST['login'])) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE nom = ? AND prenom = ? AND is_active = 1");
    $stmt->execute([$nom, $prenom]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['role'] = $user['role'];
        redirect('index.php');
    } else {
        $_SESSION['error'] = "Identifiants incorrects ou compte désactivé";
        redirect('login.php');
    }
}
?>