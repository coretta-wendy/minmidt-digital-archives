<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('CSDACL');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de décret manquant";
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);

try {
    // Vérification de l'appartenance avant suppression
    $stmt = $pdo->prepare("SELECT id FROM decrets WHERE id = ? AND created_by = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = "Décret introuvable ou non autorisé";
    } else {
        // Journalisation avant suppression
        $logStmt = $pdo->prepare("SELECT numero FROM decrets WHERE id = ?");
        $logStmt->execute([$id]);
        $numero = $logStmt->fetchColumn();
        
        // Suppression
        $stmt = $pdo->prepare("DELETE FROM decrets WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Décret #$numero supprimé avec succès";
        error_log("Suppression décret #$numero par ".$_SESSION['prenom']." ".$_SESSION['nom']);
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur technique lors de la suppression";
    error_log("DELETE Décret Error: ".$e->getMessage());
}

header("Location: list.php");
exit;
?>