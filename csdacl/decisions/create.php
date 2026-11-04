<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('CSDACL');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'type' => $_POST['type'],
        'numero' => $_POST['numero'],
        'timbre' => $_POST['timbre'],
        'objet' => $_POST['objet'],
        'destinataire' => $_POST['destinataire'],
        'signataire' => $_POST['signataire'],
        'date_signature' => $_POST['date_signature'],
        'annee_signature' => $_POST['annee_signature'],
        'created_by' => $_SESSION['user_id']
    ];

    // Gestion du fichier PDF
    if (!empty($_FILES['document_file']['name'])) {
        $file_name = $_FILES['document_file']['name'];
        $file_tmp = $_FILES['document_file']['tmp_name'];
        $file_content = file_get_contents($file_tmp);
        
        $data['nom_fichier'] = $file_name;
        $data['document_file'] = $file_content;
    }

    try {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':'.implode(', :', array_keys($data));
        
        $sql = "INSERT INTO decisions ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute($data)) {
            $_SESSION['success'] = "Décision enregistrée avec succès";
            header("Location: list.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur technique lors de l'enregistrement";
        error_log("CREATE Decision Error: ".$e->getMessage());
    }
}

require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-4">
    <h2>Créer une Nouvelle Décision</h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
            <div class="col-md-6">
                <label for="numero" class="form-label">Numéro *</label>
                <input type="text" id="numero" name="numero" class="form-control" required>
            </div>
            
            <div class="col-md-6">
                <label for="date_signature" class="form-label">Date de signature *</label>
                <input type="date" id="date_signature" name="date_signature" class="form-control" required>
            </div>
            
            <div class="col-12">
                <label for="objet" class="form-label">Objet *</label>
                <textarea id="objet" name="objet" class="form-control" rows="3" required></textarea>
            </div>
            
            <div class="col-md-6">
                <label for="signataire" class="form-label">Signataire</label>
                <input type="text" id="signataire" name="signataire" class="form-control">
            </div>
            
            <div class="col-md-6">
                <label for="destinataire" class="form-label">Destinataire</label>
                <input type="text" id="destinataire" name="destinataire" class="form-control">
            </div>
            
            <div class="col-md-6">
                <label for="document_file" class="form-label">Fichier PDF</label>
                <input type="file" id="document_file" name="document_file" class="form-control" accept=".pdf">
            </div>
            
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="list.php" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>