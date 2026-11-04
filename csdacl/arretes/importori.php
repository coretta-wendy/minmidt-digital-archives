<?php
//1require_once __DIR__ . '/../../../vendor/autoload.php';
//2require_once __DIR__ . '/../../../includes/config.php';
//3require_once __DIR__ . '/../../../includes/auth.php';

checkRole('CSDACL');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier_excel'])) {
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['fichier_excel']['tmp_name']);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        // Supprimer l'en-tête
        array_shift($rows);
        
        $pdo->beginTransaction();
        $importCount = 0;
        
        foreach ($rows as $row) {
            if (empty($row[0])) continue;
            
            $data = [
                'numero' => $row[0],
                'objet' => $row[1],
                'destinataire' => $row[2] ?? null,
                'signataire' => $row[3] ?? null,
                'date_signature' => $row[4],
                'created_by' => $_SESSION['user_id']
            ];
            
            $stmt = $pdo->prepare("INSERT INTO arretes 
                                  (numero, objet, destinataire, signataire, date_signature, created_by) 
                                  VALUES (:numero, :objet, :destinataire, :signataire, :date_signature, :created_by)");
            
            if ($stmt->execute($data)) {
                $importCount++;
            }
        }
        
        $pdo->commit();
        $_SESSION['success'] = "$importCount arrêtés importés avec succès";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur d'importation: ".$e->getMessage();
        error_log("Import Error: ".$e->getMessage());
    }
}

header("Location: list.php");
exit;
?>