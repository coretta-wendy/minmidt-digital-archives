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

// Nettoyer le buffer pour éviter des erreurs d'en-tête
ob_clean();

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT nom_fichier, document_file FROM arretes WHERE id = ?");
$stmt->execute([$id]);
$file = $stmt->fetch();

if ($file && !empty($file['document_file'])) {
    // Forcer le téléchargement avec un type MIME générique si l'extension est inconnue
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file['nom_fichier']) . '"');
    header('Content-Length: ' . strlen($file['document_file']));
    header('Pragma: no-cache');
    
    // Envoyer le contenu brut du BLOB
    echo $file['document_file'];
    exit;
} else {
    $_SESSION['error'] = "Fichier introuvable ou vide";
    redirect('list.php');
}
?>