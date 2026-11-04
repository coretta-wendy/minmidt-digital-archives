<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
checkRole('Administrateur');

if (!isset($_GET['id'])) {
    redirect('list.php');
}

$id = $_GET['id'];

// Vérifier si la décision existe
$stmt = $pdo->prepare("SELECT id FROM decisions WHERE id = ?");
$stmt->execute([$id]);

if ($stmt->fetch()) {
    // Supprimer la décision
    $stmt = $pdo->prepare("DELETE FROM decisions WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['success'] = "Décision supprimée avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression de la décision";
    }
} else {
    $_SESSION['error'] = "Décision introuvable";
}

redirect('list.php');
?>