<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
checkRole('Administrateur');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excelFile'])) {
    require_once '../../vendor/autoload.php'; // Inclure PhpSpreadsheet
    
    $file = $_FILES['excelFile']['tmp_name'];
    
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        // Supprimer l'en-tête
        array_shift($rows);
        
        $pdo->beginTransaction();
        
        foreach ($rows as $row) {
            if (empty($row[0])) continue; // Ignorer les lignes vides
            
            $data = [
                'type' => $row[0] ?? null,
                'numero' => $row[1] ?? null,
                'timbre' => $row[2] ?? null,
                'objet' => $row[3] ?? null,
                'destinataire' => $row[4] ?? null,
                'signataire' => $row[5] ?? null,
                'date_signature' => $row[6] ?? null,
                'annee_signature' => $row[7] ?? null,
                'created_by' => $_SESSION['user_id']
            ];
            
            $columns = implode(', ', array_keys($data));
            $placeholders = ':' . implode(', :', array_keys($data));
            
            $sql = "INSERT INTO decrets ($columns) VALUES ($placeholders)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($data);
        }
        
        $pdo->commit();
        $_SESSION['success'] = "Importation réussie : " . count($rows) . " décrets importés";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur lors de l'importation : " . $e->getMessage();
    }
}

redirect('list.php');
?>