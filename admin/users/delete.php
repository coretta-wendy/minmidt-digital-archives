<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';


//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/config.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/auth.php';

checkRole('Administrateur');

//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/header.php';

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID utilisateur manquant";
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);

// Empêcher la suppression de l'admin principal (vous)
if ($id === 2) { // Supposons que l'admin principal a l'ID 1
    $_SESSION['error'] = "Impossible de supprimer le compte administrateur principal";
    header("Location: list.php");
    exit;
}

try {
    // Vérifier d'abord si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$id]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = "Utilisateur introuvable";
    } else {
        // Désactiver plutôt que supprimer (bonne pratique)
        $stmt = $pdo->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Utilisateur désactivé avec succès";
        
        // Journalisation (optionnel)
        $logMessage = "Utilisateur ID $id désactivé par " . $_SESSION['prenom'] . " " . $_SESSION['nom'];
        error_log($logMessage);
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur technique : " . $e->getMessage();
}


header("Location: list.php");
exit;
?>