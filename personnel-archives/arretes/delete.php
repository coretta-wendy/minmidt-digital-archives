<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('Le personnel des archives');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID d'arrêté manquant";
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

try {
    // Vérifier que l'arrêté appartient bien à l'utilisateur
    $stmt = $pdo->prepare("SELECT id FROM arretes WHERE id = ? AND created_by = ?");
    $stmt->execute([$id, $user_id]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = "Arrêté introuvable ou non autorisé";
    } else {
        // Suppression effective
        $stmt = $pdo->prepare("DELETE FROM arretes WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Arrêté supprimé avec succès";
        
        // Journalisation
        error_log("Suppression arrêté $id par " . $_SESSION['prenom'] . " " . $_SESSION['nom']);
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur technique lors de la suppression";
    error_log("DELETE Error: " . $e->getMessage());
}

header("Location: list.php");
exit;
?>