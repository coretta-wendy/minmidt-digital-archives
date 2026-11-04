<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('CSDACL');

if (!isset($_GET['id'])) {
    header("HTTP/1.0 400 Bad Request");
    exit;
}

$id = intval($_GET['id']);

try {
    // Vérification des droits
    $stmt = $pdo->prepare("SELECT nom_fichier, document_file FROM decisions WHERE id = ? AND created_by = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $file = $stmt->fetch();
    
    if (!$file || empty($file['document_file'])) {
        header("HTTP/1.0 404 Not Found");
        exit;
    }
    
    // Envoi du fichier
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="'.basename($file['nom_fichier']).'"');
    header('Content-Length: '.strlen($file['document_file']));
    echo $file['document_file'];
    exit;
    
} catch (PDOException $e) {
    error_log("Download Décision Error: ".$e->getMessage());
    header("HTTP/1.0 500 Internal Server Error");
    exit;
}
?>