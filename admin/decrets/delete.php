<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
checkRole('Administrateur');

if (!isset($_GET['id'])) {
    redirect('list.php');
}

$id = $_GET['id'];

// Vérifier si la décision existe
$stmt = $pdo->prepare("SELECT id FROM decrets WHERE id = ?");
$stmt->execute([$id]);

if ($stmt->fetch()) {
    // Supprimer la décision
    $stmt = $pdo->prepare("DELETE FROM decrets WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['success'] = "Décret supprimé avec succès";
    } else {
        $_SESSION['error'] = "Erreur lors de la suppression du décret ";
    }
} else {
    $_SESSION['error'] = "Décret introuvable";
}

redirect('list.php');
?>