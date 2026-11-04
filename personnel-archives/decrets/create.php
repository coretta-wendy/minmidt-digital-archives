<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('Le personnel des archives');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Validation des données
        $requiredFields = ['numero', 'objet', 'date_signature'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Le champ '$field' est obligatoire");
            }
        }

        // Préparation des données
        $data = [
            'numero' => trim($_POST['numero']),
            'objet' => trim($_POST['objet']),
            'signataire' => trim($_POST['signataire'] ?? ''),
            'destinataire' => trim($_POST['destinataire'] ?? ''),
            'date_signature' => $_POST['date_signature'],
            'annee_signature' => date('Y', strtotime($_POST['date_signature'])),
            'created_by' => $_SESSION['user_id']
        ];

        // Gestion du fichier PDF
        if (!empty($_FILES['document_file']['name'])) {
            $fileInfo = $_FILES['document_file'];
            
            // Vérification du type de fichier
            if ($fileInfo['type'] !== 'application/pdf') {
                throw new Exception("Seuls les fichiers PDF sont acceptés (format reçu: " . $fileInfo['type'] . ")");
            }

            // Vérification de la taille (max 5MB)
            if ($fileInfo['size'] > 5 * 1024 * 1024) {
                throw new Exception("Le fichier ne doit pas dépasser 5MB");
            }

            $data['nom_fichier'] = basename($fileInfo['name']);
            $data['document_file'] = file_get_contents($fileInfo['tmp_name']);
        }

        // Insertion en base
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO decrets ($columns) VALUES ($placeholders)";
        $stmt = $pdo->prepare($sql);
        
        if (!$stmt->execute($data)) {
            throw new Exception("Erreur lors de l'enregistrement");
        }

        $pdo->commit();
        $_SESSION['success'] = "Décret enregistré avec succès";
        header("Location: list.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
    }
}

require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="bi bi-file-earmark-plus"></i> Nouveau Décret</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle"></i> <?= $_SESSION['error'] ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <form method="post" enctype="multipart/form-data" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="numero" class="form-label">Numéro *</label>
                                <input type="text" id="numero" name="numero" class="form-control" required
                                       value="<?= htmlspecialchars($_POST['numero'] ?? '') ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="date_signature" class="form-label">Date de signature *</label>
                                <input type="date" id="date_signature" name="date_signature" class="form-control" required
                                       value="<?= htmlspecialchars($_POST['date_signature'] ?? date('Y-m-d')) ?>">
                            </div>
                            
                            <div class="col-12">
                                <label for="objet" class="form-label">Objet *</label>
                                <textarea id="objet" name="objet" class="form-control" rows="4" required><?= htmlspecialchars($_POST['objet'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="signataire" class="form-label">Signataire</label>
                                <input type="text" id="signataire" name="signataire" class="form-control"
                                       value="<?= htmlspecialchars($_POST['signataire'] ?? '') ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="destinataire" class="form-label">Destinataire</label>
                                <input type="text" id="destinataire" name="destinataire" class="form-control"
                                       value="<?= htmlspecialchars($_POST['destinataire'] ?? '') ?>">
                            </div>
                            
                            <div class="col-12">
                                <label for="document_file" class="form-label">Document PDF</label>
                                <input type="file" id="document_file" name="document_file" class="form-control" accept=".pdf">
                                <div class="form-text">Taille max : 5MB - Format PDF uniquement</div>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-save"></i> Enregistrer
                                </button>
                                <a href="list.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Annuler
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>