<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';



//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/config.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/auth.php';


checkRole('Administrateur');


//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/header.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['excel_file']['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        // Supprimer l'en-tête
        array_shift($rows);
        
        $pdo->beginTransaction();
        
        foreach ($rows as $row) {
            if (empty($row[0])) continue;
            
            $stmt = $pdo->prepare("INSERT INTO users 
                                  (nom, prenom, password, role, is_active) 
                                  VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $row[0], // nom
                $row[1], // prenom
                password_hash($row[2], PASSWORD_DEFAULT), // password
                $row[3], // role
                $row[4] ?? 1 // is_active
            ]);
        }
        
        $pdo->commit();
        $_SESSION['success'] = count($rows) . " utilisateurs importés";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur d'import: " . $e->getMessage();
    }
}

header("Location: list.php");