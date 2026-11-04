<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('CSDACL');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de décret manquant";
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);

// Récupération du décret
$stmt = $pdo->prepare("SELECT * FROM decrets WHERE id = ? AND created_by = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$decret = $stmt->fetch();

if (!$decret) {
    $_SESSION['error'] = "Décret introuvable ou non autorisé";
    header("Location: list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo->beginTransaction();

        // Validation
        if (empty($_POST['numero']) || empty($_POST['objet']) || empty($_POST['date_signature'])) {
            throw new Exception("Tous les champs obligatoires doivent être remplis");
        }

        // Préparation des données
        $data = [
            'id' => $id,
            'type' => $_POST['type'] ?? 'Décret',
            'numero' => trim($_POST['numero']),
            'objet' => trim($_POST['objet']),
            'destinataire' => trim($_POST['destinataire'] ?? ''),
            'signataire' => trim($_POST['signataire'] ?? ''),
            'date_signature' => $_POST['date_signature'],
            'annee_signature' => date('Y', strtotime($_POST['date_signature']))
        ];

        // Gestion du fichier
        $newFileUploaded = !empty($_FILES['document_file']['name']);
        if ($newFileUploaded) {
            $fileInfo = $_FILES['document_file'];
            
            if ($fileInfo['type'] !== 'application/pdf') {
                throw new Exception("Seuls les fichiers PDF sont acceptés");
            }

            $data['nom_fichier'] = basename($fileInfo['name']);
            $data['document_file'] = file_get_contents($fileInfo['tmp_name']);
        }

        // Construction de la requête
        $sql = "UPDATE decrets SET 
                type = :type,
                numero = :numero,
                objet = :objet,
                destinataire = :destinataire,
                signataire = :signataire,
                date_signature = :date_signature,
                annee_signature = :annee_signature";
        
        if ($newFileUploaded) {
            $sql .= ", nom_fichier = :nom_fichier, document_file = :document_file";
        }
        
        $sql .= " WHERE id = :id AND created_by = ".$_SESSION['user_id'];
        
        $stmt = $pdo->prepare($sql);
        
        if (!$stmt->execute($data)) {
            throw new Exception("Erreur lors de la mise à jour");
        }

        $pdo->commit();
        $_SESSION['success'] = "Décret mis à jour avec succès";
        header("Location: list.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
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
                        <i class="bi bi-pencil-square"></i> Modifier Décret #<?= htmlspecialchars($decret['numero']) ?>
                    </h3>
                </div>
                
                <div class="card-body">
                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?= $decret['id'] ?>">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Numéro *</label>
                                <input type="text" name="numero" class="form-control" required
                                       value="<?= htmlspecialchars($decret['numero']) ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Date de signature *</label>
                                <input type="date" name="date_signature" class="form-control" required
                                       value="<?= $decret['date_signature'] ?>">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Objet *</label>
                                <textarea name="objet" class="form-control" rows="4" required><?= htmlspecialchars($decret['objet']) ?></textarea>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Signataire</label>
                                <input type="text" name="signataire" class="form-control"
                                       value="<?= htmlspecialchars($decret['signataire']) ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Destinataire</label>
                                <input type="text" name="destinataire" class="form-control"
                                       value="<?= htmlspecialchars($decret['destinataire']) ?>">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Document actuel</label>
                                <?php if (!empty($decret['nom_fichier'])): ?>
                                <div class="alert alert-info d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="bi bi-file-earmark-pdf"></i> 
                                        <?= htmlspecialchars($decret['nom_fichier']) ?>
                                    </span>
                                    <a href="download.php?id=<?= $decret['id'] ?>" 
                                       class="btn btn-sm btn-success">
                                       <i class="bi bi-download"></i> Télécharger
                                    </a>
                                </div>
                                <?php else: ?>
                                <p class="text-muted">Aucun document attaché</p>
                                <?php endif; ?>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Nouveau document (remplacera l'actuel)</label>
                                <input type="file" name="document_file" class="form-control" accept=".pdf">
                                <div class="form-text">Laisser vide pour conserver le document actuel</div>
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