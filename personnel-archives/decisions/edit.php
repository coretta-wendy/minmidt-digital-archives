<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('Le personnel des archives');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de décision manquant";
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Récupération de la décision
$stmt = $pdo->prepare("SELECT * FROM decisions WHERE id = ? AND created_by = ?");
$stmt->execute([$id, $user_id]);
$decision = $stmt->fetch();

if (!$decision) {
    $_SESSION['error'] = "Décision introuvable ou non autorisée";
    header("Location: list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = [
            'id' => $id,
            'numero' => $_POST['numero'],
            'objet' => $_POST['objet'],
            'signataire' => $_POST['signataire'] ?? null,
            'date_signature' => $_POST['date_signature']
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

        $sql = "UPDATE decisions SET 
                numero = :numero,
                objet = :objet,
                signataire = :signataire,
                date_signature = :date_signature";
        
        if (isset($data['nom_fichier'])) {
            $sql .= ", nom_fichier = :nom_fichier, document_file = :document_file";
        }
        
        $sql .= " WHERE id = :id AND created_by = $user_id";
        
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute($data)) {
            $_SESSION['success'] = "Décision mise à jour avec succès";
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
                    <h3 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Modifier Décision #<?= htmlspecialchars($decision['numero']) ?>
                    </h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Numéro *</label>
                            <input type="text" name="numero" class="form-control" 
                                   value="<?= htmlspecialchars($decision['numero']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Objet *</label>
                            <textarea name="objet" class="form-control" rows="3" required><?= htmlspecialchars($decision['objet']) ?></textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Signataire</label>
                                <input type="text" name="signataire" class="form-control"
                                       value="<?= htmlspecialchars($decision['signataire']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date de signature *</label>
                                <input type="date" name="date_signature" class="form-control"
                                       value="<?= $decision['date_signature'] ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Document actuel</label>
                            <?php if (!empty($decision['nom_fichier'])): ?>
                            <div class="alert alert-info d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="bi bi-file-earmark-pdf"></i> 
                                    <?= htmlspecialchars($decision['nom_fichier']) ?>
                                </span>
                                <a href="download.php?id=<?= $decision['id'] ?>" class="btn btn-sm btn-success">
                                    <i class="bi bi-download"></i> Télécharger
                                </a>
                            </div>
                            <?php else: ?>
                            <p class="text-muted">Aucun document attaché</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nouveau document (remplacera l'actuel)</label>
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