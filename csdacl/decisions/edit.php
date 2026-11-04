<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('CSDACL');

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID de décision manquant";
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);

// Vérifier l'appartenance
$stmt = $pdo->prepare("SELECT * FROM decisions WHERE id = ? AND created_by = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$decision = $stmt->fetch();

if (!$decision) {
    $_SESSION['error'] = "Décision introuvable ou non autorisée";
    header("Location: list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $id,
        'type' => $_POST['type'],
        'numero' => $_POST['numero'],
        'timbre' => $_POST['timbre'],
        'objet' => $_POST['objet'],
        'destinataire' => $_POST['destinataire'],
        'signataire' => $_POST['signataire'],
        'date_signature' => $_POST['date_signature'],
        'annee_signature' => $_POST['annee_signature']
    ];

    // Gestion du fichier
    if (!empty($_FILES['document_file']['name'])) {
        $file_name = $_FILES['document_file']['name'];
        $file_tmp = $_FILES['document_file']['tmp_name'];
        $file_content = file_get_contents($file_tmp);
        
        $data['nom_fichier'] = $file_name;
        $data['document_file'] = $file_content;
    }

    try {
        $sql = "UPDATE decisions SET 
                type = :type,
                numero = :numero,
                timbre = :timbre,
                objet = :objet,
                destinataire = :destinataire,
                signataire = :signataire,
                date_signature = :date_signature,
                annee_signature = :annee_signature";
        
        if (isset($data['nom_fichier'])) {
            $sql .= ", nom_fichier = :nom_fichier, document_file = :document_file";
        }
        
        $sql .= " WHERE id = :id AND created_by = ".$_SESSION['user_id'];
        
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute($data)) {
            $_SESSION['success'] = "Décision mise à jour";
            header("Location: list.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de la mise à jour";
        error_log("UPDATE Decision Error: ".$e->getMessage());
    }
}

require_once __DIR__ . '/../../../includes/header.php';
?>

<div class="container mt-4">
    <h2>Modifier la Décision #<?= htmlspecialchars($decision['numero']) ?></h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $decision['id'] ?>">
        
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Numéro *</label>
                <input type="text" name="numero" class="form-control" value="<?= htmlspecialchars($decision['numero']) ?>" required>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Date de signature *</label>
                <input type="date" name="date_signature" class="form-control" value="<?= $decision['date_signature'] ?>" required>
            </div>
            
            <div class="col-12">
                <label class="form-label">Objet *</label>
                <textarea name="objet" class="form-control" rows="3" required><?= htmlspecialchars($decision['objet']) ?></textarea>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Signataire</label>
                <input type="text" name="signataire" class="form-control" value="<?= htmlspecialchars($decision['signataire']) ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Destinataire</label>
                <input type="text" name="destinataire" class="form-control" value="<?= htmlspecialchars($decision['destinataire']) ?>">
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Fichier actuel</label>
                <?php if (!empty($decision['nom_fichier'])): ?>
                    <div class="alert alert-info">
                        <?= htmlspecialchars($decision['nom_fichier']) ?>
                        <a href="download.php?id=<?= $decision['id'] ?>" class="btn btn-sm btn-success ms-2">Télécharger</a>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Aucun fichier attaché</p>
                <?php endif; ?>
            </div>
            
            <div class="col-md-6">
                <label class="form-label">Nouveau fichier (remplacera l'actuel)</label>
                <input type="file" name="document_file" class="form-control" accept=".pdf">
            </div>
            
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                <a href="list.php" class="btn btn-secondary">Annuler</a>
            </div>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>