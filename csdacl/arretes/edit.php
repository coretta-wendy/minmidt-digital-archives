<?php
//1require_once __DIR__ . '/../../../includes/config.php';
//2require_once __DIR__ . '/../../../includes/auth.php';


require_once '../../includes/config.php';
require_once '../../includes/auth.php';
checkRole('CSDACL');

if (!isset($_GET['id'])) {
    header("Location: list.php");
    exit;
}

$id = intval($_GET['id']);

// Vérifier l'appartenance du document
$stmt = $pdo->prepare("SELECT * FROM arretes WHERE id = ? AND created_by = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$arrete = $stmt->fetch();

if (!$arrete) {
    $_SESSION['error'] = "Arrêté introuvable ou non autorisé";
    header("Location: list.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $id,
        'type' => $_POST['type'],
        'numero' => $_POST['numero'],
        'objet' => $_POST['objet'],
        'destinataire' => $_POST['destinataire'],
        'signataire' => $_POST['signataire'],
        'date_signature' => $_POST['date_signature']
    ];

    try {
        $sql = "UPDATE arretes SET 
                type = :type,
                numero = :numero, 
                objet = :objet,
                destinataire = :destinataire,
                signataire = :signataire,
                date_signature = :date_signature
                WHERE id = :id AND created_by = ".$_SESSION['user_id'];
        
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute($data)) {
            $_SESSION['success'] = "Arrêté mis à jour";
            header("Location: list.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur de mise à jour";
    }
}

//3require_once __DIR__ . '/../../../includes/header.php';
require_once '../../includes/header.php';
?>

<div class="container mt-4">
    <h2>Modifier l'Arrêté #<?= $arrete['numero'] ?></h2>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Numéro</label>
            <input type="text" name="numero" class="form-control" value="<?= htmlspecialchars($arrete['numero']) ?>" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Objet</label>
            <textarea name="objet" class="form-control" rows="3" required><?= htmlspecialchars($arrete['objet']) ?></textarea>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Destinataire</label>
                <input type="text" name="destinataire" class="form-control" value="<?= htmlspecialchars($arrete['destinataire']) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Signataire</label>
                <input type="text" name="signataire" class="form-control" value="<?= htmlspecialchars($arrete['signataire']) ?>">
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Date de signature</label>
            <input type="date" name="date_signature" class="form-control" value="<?= $arrete['date_signature'] ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="list.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>

<?php 
//4require_once __DIR__ . '/../../../includes/footer.php'; 

require_once '../../includes/footer.php';
?>