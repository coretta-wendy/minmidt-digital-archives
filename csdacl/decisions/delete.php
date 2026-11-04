<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('CSDACL');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de décision manquant";
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);

try {
    // Vérifier l'appartenance avant suppression
    $stmt = $pdo->prepare("SELECT id FROM decisions WHERE id = ? AND created_by = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = "Décision introuvable ou non autorisée";
    } else {
        $stmt = $pdo->prepare("DELETE FROM decisions WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Décision supprimée avec succès";
        
        // Journalisation
        error_log("Décision $id supprimée par ".$_SESSION['prenom']." ".$_SESSION['nom']);
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur lors de la suppression";
    error_log("DELETE Decision Error: ".$e->getMessage());
}

header("Location: list.php");
exit;
?>