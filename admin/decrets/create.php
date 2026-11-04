<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
checkRole('Administrateur'); 

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

    $columns = implode(', ', array_keys($data));
    $placeholders = ':' . implode(', :', array_keys($data));
    
    $sql = "INSERT INTO decrets ($columns) VALUES ($placeholders)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($data)) {
        $_SESSION['success'] = "Décret créé avec succès";
        redirect('list.php');
    } else {
        $_SESSION['error'] = "Erreur lors de la création du décret";
    }
}

require_once '../../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include '../menu.php'; ?>
    </div>
    
    <div class="col-md-9">
        <h2>Créer un Décret</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
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
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>