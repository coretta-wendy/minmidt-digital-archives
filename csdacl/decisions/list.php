<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('CSDACL');
require_once __DIR__ . '/../../../includes/header.php';

// Recherche et pagination
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;

$query = "SELECT * FROM decisions WHERE created_by = ?";
$params = [$_SESSION['user_id']];

if (!empty($search)) {
    $query .= " AND (numero LIKE ? OR objet LIKE ?)";
    array_push($params, "%$search%", "%$search%");
}

$query .= " ORDER BY date_signature DESC LIMIT ".(($page-1)*$perPage).",$perPage";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$decisions = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h2>Gestion des Décisions</h2>
    
    <div class="d-flex justify-content-between mb-4">
        <a href="create.php" class="btn btn-primary">Nouvelle Décision</a>
        <form class="d-inline-block">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
                <button class="btn btn-outline-secondary" type="submit">🔍</button>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Numéro</th>
                    <th>Objet</th>
                    <th>Signataire</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($decisions as $decision): ?>
                <tr>
                    <td><?= htmlspecialchars($decision['numero']) ?></td>
                    <td><?= substr(htmlspecialchars($decision['objet']), 0, 50) ?>...</td>
                    <td><?= htmlspecialchars($decision['signataire']) ?></td>
                    <td><?= date('d/m/Y', strtotime($decision['date_signature'])) ?></td>
                    <td>
                        <a href="edit.php?id=<?= $decision['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                        <a href="delete.php?id=<?= $decision['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php if($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">Précédent</a>
                </li>
            <?php endif; ?>
            
            <li class="page-item active">
                <span class="page-link">Page <?= $page ?></span>
            </li>
            
            <?php if(count($decisions) === $perPage): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">Suivant</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>