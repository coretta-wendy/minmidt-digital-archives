<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('Le personnel des archives');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de décret manquant";
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

try {
    // Vérification de l'appartenance avant suppression
    $stmt = $pdo->prepare("SELECT numero FROM decrets WHERE id = ? AND created_by = ?");
    $stmt->execute([$id, $user_id]);
    $decret = $stmt->fetch();
    
    if (!$decret) {
        $_SESSION['error'] = "Décret introuvable ou non autorisé";
    } else {
        // Journalisation avant suppression
        error_log("Suppression décret #" . $decret['numero'] . " par " . $_SESSION['prenom'] . " " . $_SESSION['nom']);
        
        // Suppression
        $stmt = $pdo->prepare("DELETE FROM decrets WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Décret #" . $decret['numero'] . " supprimé avec succès";
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur technique lors de la suppression";
    error_log("DELETE Error: " . $e->getMessage());
}

header("Location: list.php");
exit;
?>