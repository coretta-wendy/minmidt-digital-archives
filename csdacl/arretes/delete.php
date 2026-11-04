<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('CSDACL');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID d'arrêté manquant";
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);

try {
    // Vérifier que l'arrêté existe et appartient à l'utilisateur
    $stmt = $pdo->prepare("SELECT id FROM arretes WHERE id = ? AND created_by = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = "Arrêté introuvable ou non autorisé";
    } else {
        $stmt = $pdo->prepare("DELETE FROM arretes WHERE id = ?");
        $stmt->execute([$id]);
        $_SESSION['success'] = "Arrêté supprimé avec succès";
        
        // Journalisation
        error_log("Suppression arrêté $id par ".$_SESSION['prenom']." ".$_SESSION['nom']);
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur technique";
    error_log("DELETE Error: ".$e->getMessage());
}

header("Location: list.php");
exit;
?>