<?php
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('CSDACL');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier_excel'])) {
    try {
        // Chargement du fichier Excel
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES['fichier_excel']['tmp_name']);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        
        // Suppression de l'en-tête
        array_shift($rows);
        
        $pdo->beginTransaction();
        $importCount = 0;
        $currentUser = $_SESSION['user_id'];
        
        foreach ($rows as $index => $row) {
            // Ignorer les lignes vides
            if (empty($row[0])) continue;
            
            // Validation des données obligatoires
            if (empty($row[1]) || empty($row[4])) {
                error_log("Ligne ".($index+2)." ignorée - données manquantes");
                continue;
            }
            
            // Préparation des données
            $data = [
                'numero' => $row[0],
                'objet' => $row[1],
                'signataire' => $row[2] ?? null,
                'destinataire' => $row[3] ?? null,
                'date_signature' => $row[4],
                'annee_signature' => date('Y', strtotime($row[4])),
                'created_by' => $currentUser
            ];
            
            // Insertion
            $stmt = $pdo->prepare("INSERT INTO decrets 
                                  (numero, objet, signataire, destinataire, date_signature, annee_signature, created_by) 
                                  VALUES (:numero, :objet, :signataire, :destinataire, :date_signature, :annee_signature, :created_by)");
            
            if ($stmt->execute($data)) {
                $importCount++;
            }
        }
        
        $pdo->commit();
        $_SESSION['success'] = "$importCount décrets importés avec succès";
        error_log("Import de $importCount décrets par ".$_SESSION['prenom']." ".$_SESSION['nom']);
        
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur lors de l'importation: ".$e->getMessage();
        error_log("Import Décret Error: ".$e->getMessage());
    }
}

header("Location: list.php");
exit;
?>