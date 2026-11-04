<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('Le personnel des archives');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de décision manquant";
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

try {
    // Vérification de l'appartenance
    $stmt = $pdo->prepare("SELECT id FROM decisions WHERE id = ? AND created_by = ?");
    $stmt->execute([$id, $user_id]);
    
    if (!$stmt->fetch()) {
        $_SESSION['error'] = "Décision introuvable ou non autorisée";
    } else {
        // Suppression
        $stmt = $pdo->prepare("DELETE FROM decisions WHERE id = ?");
        $stmt->execute([$id]);
        
        $_SESSION['success'] = "Décision supprimée avec succès";
        error_log("Suppression décision $id par " . $_SESSION['prenom'] . " " . $_SESSION['nom']);
    }
} catch (PDOException $e) {
    $_SESSION['error'] = "Erreur technique lors de la suppression";
    error_log("DELETE Error: " . $e->getMessage());
}

header("Location: list.php");
exit;
?>