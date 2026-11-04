<?php
require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../includes/auth.php';
checkRole('Le personnel des archives');
require_once __DIR__ . '/../../../includes/header.php';

$user_id = $_SESSION['user_id'];
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Requête principale
$query = "SELECT SQL_CALC_FOUND_ROWS * FROM decrets WHERE created_by = ?";
$params = [$user_id];

if (!empty($search)) {
    $query .= " AND (numero LIKE ? OR objet LIKE ? OR signataire LIKE ?)";
    array_push($params, "%$search%", "%$search%", "%$search%");
}

$query .= " ORDER BY date_signature DESC LIMIT $offset, $perPage";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$decrets = $stmt->fetchAll();

// Calcul du total
$total = $pdo->query("SELECT FOUND_ROWS()")->fetchColumn();
$totalPages = ceil($total / $perPage);
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-file-earmark-ruled"></i> Mes Décrets</h2>
        <div>
            <a href="create.php" class="btn btn-primary me-2">
                <i class="bi bi-plus-circle"></i> Nouveau Décret
            </a>
            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#helpModal">
                <i class="bi bi-question-circle"></i> Aide
            </button>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="get" class="row g-2">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher par numéro, objet ou signataire..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Rechercher
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des décrets -->
    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (empty($decrets)): ?>
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Aucun décret trouvé.
                    <?php if (!empty($search)): ?>
                        <a href="list.php" class="alert-link">Réinitialiser la recherche</a>
                    <?php else: ?>
                        <a href="create.php" class="alert-link">Créer votre premier décret</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="15%">Numéro</th>
                                <th width="40%">Objet</th>
                                <th width="15%">Signataire</th>
                                <th width="15%">Date</th>
                                <th width="15%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($decrets as $decret): ?>
                            <tr>
                                <td><?= htmlspecialchars($decret['numero']) ?></td>
                                <td>
                                    <div class="text-truncate" style="max-width: 400px;" title="<?= htmlspecialchars($decret['objet']) ?>">
                                        <?= htmlspecialchars($decret['objet']) ?>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($decret['signataire']) ?></td>
                                <td><?= date('d/m/Y', strtotime($decret['date_signature'])) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="edit.php?id=<?= $decret['id'] ?>" class="btn btn-outline-primary" title="Modifier">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="delete.php?id=<?= $decret['id'] ?>" class="btn btn-outline-danger" title="Supprimer" onclick="return confirm('Supprimer ce décret ?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                        <?php if (!empty($decret['document_file'])): ?>
                                        <a href="download.php?id=<?= $decret['id'] ?>" class="btn btn-outline-success" title="Télécharger">
                                            <i class="bi bi-download"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav aria-label="Pagination">
                    <ul class="pagination justify-content-center mt-4">
                        <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page-1 ?>&search=<?= urlencode($search) ?>">
                                <i class="bi bi-chevron-left"></i> Précédent
                            </a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?= $page+1 ?>&search=<?= urlencode($search) ?>">
                                Suivant <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal d'aide -->
<div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="helpModalLabel"><i class="bi bi-question-circle"></i> Aide - Gestion des Décrets</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Fonctionnalités disponibles :</h6>
                <ul>
                    <li><strong>Création</strong> : Enregistrez de nouveaux décrets avec pièces jointes PDF</li>
                    <li><strong>Modification</strong> : Mettez à jour les informations des décrets existants</li>
                    <li><strong>Recherche</strong> : Trouvez rapidement un décret par numéro, objet ou signataire</li>
                    <li><strong>Téléchargement</strong> : Récupérez les documents PDF associés</li>
                </ul>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Seuls les décrets que vous avez créés sont visibles.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../../includes/footer.php'; ?>