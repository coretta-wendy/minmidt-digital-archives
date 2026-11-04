<?php
//1require_once __DIR__ . '/../../../includes/config.php';
//2require_once __DIR__ . '/../../../includes/auth.php';

require_once '../../includes/config.php';
require_once '../../includes/auth.php';

checkRole('CSDACL');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'numero' => $_POST['numero'],
        'objet' => $_POST['objet'],
        'date_signature' => $_POST['date_signature'],
        'created_by' => $_SESSION['user_id']
    ];

    $stmt = $pdo->prepare("INSERT INTO arretes (numero, objet, date_signature, created_by) 
                          VALUES (:numero, :objet, :date_signature, :created_by)");
    
    if ($stmt->execute($data)) {
        $_SESSION['success'] = "Arrêté créé avec succès";
        header("Location: list.php");
        exit;
    }
}

//3require_once __DIR__ . '/../../../includes/header.php';
require_once '../../includes/header.php';
?>

<!--<form method="POST">
    
</form>-->

                    <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Type</label>
                    <input type="text" class="form-control" id="type" name="type">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="numero" class="form-label">Numéro</label>
                    <input type="text" class="form-control" id="numero" name="numero" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="timbre" class="form-label">Timbre</label>
                    <input type="text" class="form-control" id="timbre" name="timbre">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="date_signature" class="form-label">Date de signature</label>
                    <input type="date" class="form-control" id="date_signature" name="date_signature">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="objet" class="form-label">Objet</label>
                <textarea class="form-control" id="objet" name="objet" rows="3"></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="destinataire" class="form-label">Destinataire</label>
                    <input type="text" class="form-control" id="destinataire" name="destinataire">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="signataire" class="form-label">Signataire</label>
                    <input type="text" class="form-control" id="signataire" name="signataire">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="annee_signature" class="form-label">Année de signature</label>
                <input type="number" class="form-control" id="annee_signature" name="annee_signature" min="2000" max="<?= date('Y'); ?>">
            </div>
            
            <div class="mb-3">
                <label for="document_file" class="form-label">Fichier PDF</label>
                <input class="form-control" type="file" id="document_file" name="document_file" accept=".pdf">
            </div>
            
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="list.php" class="btn btn-secondary">Annuler</a>
        </form>

<?php 
//4require_once __DIR__ . '/../../../includes/footer.php'; 

require_once '../../includes/footer.php';
?>