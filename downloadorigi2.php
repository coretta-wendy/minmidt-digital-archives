<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}


if (!isset($_GET['id'])) {
    redirect('list.php');
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT nom_fichier, document_file FROM arretes WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if ($file && !empty($file['document_file'])) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $file['nom_fichier'] . '"');
    echo $file['document_file'];
    exit;
} else {
    $_SESSION['error'] = "Fichier introuvable";
    redirect('list.php');
}
?>
