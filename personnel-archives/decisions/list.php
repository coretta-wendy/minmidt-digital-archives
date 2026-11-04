<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('Le personnel des archives');
require_once __DIR__ . '/../../../includes/header.php';

$user_id = $_SESSION['user_id'];
$search = $_GET['search'] ?? '';

$query = "SELECT * FROM decisions WHERE created_by = ?";
$params = [$user_id];

if (!empty($search)) {
    $query .= " AND (numero LIKE ? OR objet LIKE ?)";
    array_push($params, "%$search%", "%$search%");
}

$query .= " ORDER BY date_signature DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$decisions = $stmt->fetchAll();
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-file-earmark-check"></i> Mes Décisions</h2>
        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nouvelle Décision
        </a>
    </div>
    
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="mb-3">
                <form class="row g-2">
                    <div class="col-md-8">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-search"></i> Rechercher
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Numéro</th>
                            <th>Objet</th>
                            <th>Date</th>
                            <th>Fichier</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($decisions as $decision): ?>
                        <tr>
                            <td><?= htmlspecialchars($decision['numero']) ?></td>
                            <td><?= substr(htmlspecialchars($decision['objet']), 0, 50) ?>...</td>
                            <td><?= date('d/m/Y', strtotime($decision['date_signature'])) ?></td>
                            <td>
                                <?php if (!empty($decision['nom_fichier'])): ?>
                                <a href="download.php?id=<?= $decision['id'] ?>" class="btn btn-sm btn-success">
                                    <i class="bi bi-download"></i>
                                </a>
                                <?php else: ?>
                                <span class="badge bg-secondary">Aucun</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?= $decision['id'] ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete.php?id=<?= $decision['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette décision?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>