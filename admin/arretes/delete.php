<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
checkRole('Administrateur');

if (!isset($_GET['id'])) {
    redirect('list.php');
}

$id = $_GET['id'];

// Vérifier si l'arrêté existe
$stmt = $pdo->prepare("SELECT id FROM arretes WHERE id = ?");
$stmt->execute([$id]);

if ($stmt->fetch()) {
    // Supprimer l'arrêté
    $stmt = $pdo->prepare("DELETE FROM arretes WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['success'] = "Arrêté supprimé avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression de l'arrêté";
    }
} else {
    $_SESSION['error'] = "Arrêté introuvable";
}

redirect('list.php');
?>