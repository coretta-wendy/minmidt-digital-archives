<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('Le personnel des archives');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'numero' => $_POST['numero'],
            'objet' => $_POST['objet'],
            'signataire' => $_POST['signataire'] ?? null,
            'date_signature' => $_POST['date_signature'],
            'created_by' => $_SESSION['user_id']
        ];

        // Gestion du fichier
        if (!empty($_FILES['document_file']['name'])) {
            $fileInfo = $_FILES['document_file'];
            if ($fileInfo['type'] !== 'application/pdf') {
                throw new Exception("Seuls les fichiers PDF sont acceptés");
            }
            $data['nom_fichier'] = $fileInfo['name'];
            $data['document_file'] = file_get_contents($fileInfo['tmp_name']);
        }

        $stmt = $pdo->prepare("INSERT INTO arretes 
                              (numero, objet, signataire, date_signature, created_by, nom_fichier, document_file) 
                              VALUES (:numero, :objet, :signataire, :date_signature, :created_by, :nom_fichier, :document_file)");
        
        if ($stmt->execute($data)) {
            $_SESSION['success'] = "Arrêté créé avec succès";
            header("Location: list.php");
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-file-earmark-plus"></i> Nouvel Arrêté</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Numéro *</label>
                            <input type="text" name="numero" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Objet *</label>
                            <textarea name="objet" class="form-control" rows="3" required></textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Signataire</label>
                                <input type="text" name="signataire" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de signature *</label>
                                <input type="date" name="date_signature" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Document PDF</label>
                            <input type="file" name="document_file" class="form-control" accept=".pdf">
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Enregistrer
                            </button>
                            <a href="list.php" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>