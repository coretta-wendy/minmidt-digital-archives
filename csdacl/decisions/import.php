<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
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
                'signataire' => $row[2] ?? null,
                'destinataire' => $row[3] ?? null,
                'date_signature' => $row[4],
                'created_by' => $_SESSION['user_id']
            ];
            
            $stmt = $pdo->prepare("INSERT INTO decisions 
                                  (numero, objet, signataire, destinataire, date_signature, created_by) 
                                  VALUES (:numero, :objet, :signataire, :destinataire, :date_signature, :created_by)");
            
            if ($stmt->execute($data)) {
                $importCount++;
            }
        }
        
        $pdo->commit();
        $_SESSION['success'] = "$importCount décisions importées avec succès";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur d'importation: ".$e->getMessage();
        error_log("Import Decision Error: ".$e->getMessage());
    }
}

header("Location: list.php");
exit;
?>