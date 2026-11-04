<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('CSDACL');
require_once __DIR__ . '/../../../includes/header.php';

// Paramètres de recherche et pagination
$search = $_GET['search'] ?? '';
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 10;

// Requête de base
$query = "SELECT * FROM decrets WHERE created_by = ?";
$params = [$_SESSION['user_id']];

// Filtre de recherche
if (!empty($search)) {
    $query .= " AND (numero LIKE ? OR objet LIKE ? OR signataire LIKE ?)";
    array_push($params, "%$search%", "%$search%", "%$search%");
}

// Tri et pagination
$query .= " ORDER BY date_signature DESC LIMIT ".(($page-1)*$perPage).",$perPage";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$decrets = $stmt->fetchAll();

// Compte total pour pagination
$totalQuery = "SELECT COUNT(*) FROM decrets WHERE created_by = ?";
$totalStmt = $pdo->prepare($totalQuery);
$totalStmt->execute([$_SESSION['user_id']]);
$total = $totalStmt->fetchColumn();
?>

<div class="container mt-4">
    <h2><i class="bi bi-file-earmark-text"></i> Gestion des Décrets</h2>
    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <a href="create.php" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Nouveau Décret
                </a>
                
                <form class="d-flex" style="width: 300px;">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th width="15%">Numéro</th>
                    <th width="35%">Objet</th>
                    <th width="20%">Signataire</th>
                    <th width="15%">Date</th>
                    <th width="15%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($decrets)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Aucun décret trouvé</td>
                </tr>
                <?php else: ?>
                <?php foreach ($decrets as $decret): ?>
                <tr>
                    <td><?= htmlspecialchars($decret['numero']) ?></td>
                    <td>
                        <div class="text-truncate" style="max-width: 400px;" 
                             title="<?= htmlspecialchars($decret['objet']) ?>">
                            <?= htmlspecialchars($decret['objet']) ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($decret['signataire']) ?></td>
                    <td><?= date('d/m/Y', strtotime($decret['date_signature'])) ?></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="edit.php?id=<?= $decret['id'] ?>" 
                               class="btn btn-outline-primary" title="Modifier">
                               <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete.php?id=<?= $decret['id'] ?>" 
                               class="btn btn-outline-danger" 
                               title="Supprimer"
                               onclick="return confirm('Supprimer ce décret ?')">
                               <i class="bi bi-trash"></i>
                            </a>
                            <?php if (!empty($decret['document_file'])): ?>
                            <a href="download.php?id=<?= $decret['id'] ?>" 
                               class="btn btn-outline-success" title="Télécharger">
                               <i class="bi bi-download"></i>
                            </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($total > $perPage): ?>
    <nav aria-label="Pagination">
        <ul class="pagination justify-content-center">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" 
                   href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">
                   &laquo; Précédent
                </a>
            </li>
            
            <?php for ($i = 1; $i <= ceil($total/$perPage); $i++): ?>
            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" 
                   href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
                   <?= $i ?>
                </a>
            </li>
            <?php endfor; ?>
            
            <li class="page-item <?= $page >= ceil($total/$perPage) ? 'disabled' : '' ?>">
                <a class="page-link" 
                   href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">
                   Suivant &raquo;
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>