<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
checkRole('Administrateur');

if (!isset($_GET['id'])) {
    redirect('list.php');
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM decrets WHERE id = ?");
$stmt->execute([$id]);
$decret = $stmt->fetch();

if (!$decret) {
    $_SESSION['error'] = "Décret introuvable";
    redirect('list.php');
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

    // Gestion du fichier PDF si un nouveau est uploadé
    if (!empty($_FILES['document_file']['name'])) {
        $file_name = $_FILES['document_file']['name'];
        $file_tmp = $_FILES['document_file']['tmp_name'];
        $file_content = file_get_contents($file_tmp);
        
        $data['nom_fichier'] = $file_name;
        $data['document_file'] = $file_content;
    }

    $sql = "UPDATE decrets SET 
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
    
    $sql .= " WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute($data)) {
        $_SESSION['success'] = "Décret mis à jour avec succès";
        redirect('list.php');
    } else {
        $_SESSION['error'] = "Erreur lors de la mise à jour du décret";
    }
}

require_once '../../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include '../menu.php'; ?>
    </div>
    
    <div class="col-md-9">
        <h2>Modifier le Décret #<?= $decret['id']; ?></h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Type</label>
                    <input type="text" class="form-control" id="type" name="type" value="<?= htmlspecialchars($decret['type']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="numero" class="form-label">Numéro</label>
                    <input type="text" class="form-control" id="numero" name="numero" value="<?= htmlspecialchars($decret['numero']); ?>" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="timbre" class="form-label">Timbre</label>
                    <input type="text" class="form-control" id="timbre" name="timbre" value="<?= htmlspecialchars($decret['timbre']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="date_signature" class="form-label">Date de signature</label>
                    <input type="date" class="form-control" id="date_signature" name="date_signature" value="<?= $decret['date_signature']; ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="objet" class="form-label">Objet</label>
                <textarea class="form-control" id="objet" name="objet" rows="3"><?= htmlspecialchars($decret['objet']); ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="destinataire" class="form-label">Destinataire</label>
                    <input type="text" class="form-control" id="destinataire" name="destinataire" value="<?= htmlspecialchars($decret['destinataire']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="signataire" class="form-label">Signataire</label>
                    <input type="text" class="form-control" id="signataire" name="signataire" value="<?= htmlspecialchars($decret['signataire']); ?>">
                </div>
            </div>
            
            <div class="mb-3">
                <label for="annee_signature" class="form-label">Année de signature</label>
                <input type="number" class="form-control" id="annee_signature" name="annee_signature" min="2000" max="<?= date('Y'); ?>" value="<?= $decret['annee_signature']; ?>">
            </div>
            
            <div class="mb-3">
                <label for="document_file" class="form-label">Fichier PDF</label>
                <input class="form-control" type="file" id="document_file" name="document_file" accept=".pdf">
                <?php if (!empty($decret['nom_fichier'])): ?>
                    <div class="mt-2">
                        Fichier actuel: <?= $decret['nom_fichier']; ?>
                        <a href="download.php?id=<?= $decret['id']; ?>" class="btn btn-sm btn-success ms-2">Télécharger</a>
                    </div>
                <?php endif; ?>
            </div>
            
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="list.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>