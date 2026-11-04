<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkRole('Administrateur');

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$userId = (int)$_GET['id'];

// Récupère l'état actuel
$stmt = $pdo->prepare("SELECT is_active FROM users WHERE id = ?");
$stmt->execute([$userId]);
$currentState = $stmt->fetchColumn();

// Bascule l'état
$newState = $currentState ? 0 : 1;

// Met à jour
$updateStmt = $pdo->prepare("UPDATE users SET is_active = ? WHERE id = ?");
if ($updateStmt->execute([$newState, $userId])) {
    $_SESSION['success'] = "Statut utilisateur mis à jour";
} else {
    $_SESSION['error'] = "Erreur lors de la mise à jour";
}

header("Location: list.php");
exit;