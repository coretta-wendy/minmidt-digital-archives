<?php
//1require_once __DIR__ . '/../../includes/config.php';
//2require_once __DIR__ . '/../../includes/auth.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/config.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/auth.php';



checkRole('CSDACL');
//3require_once __DIR__ . '/../../includes/header.php';


require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/header.php';



// Récupération des statistiques
$stats = [
    'arretes' => $pdo->query("SELECT COUNT(*) FROM arretes")->fetchColumn(),
    'decisions' => $pdo->query("SELECT COUNT(*) FROM decisions")->fetchColumn(),
    'decrets' => $pdo->query("SELECT COUNT(*) FROM decrets")->fetchColumn()
];

// Récupération du personnel des archives et leurs contributions
$personnel = $pdo->query("
    SELECT u.id, u.nom, u.prenom, 
           (SELECT COUNT(*) FROM arretes a WHERE a.created_by = u.id) as arretes_count,
           (SELECT COUNT(*) FROM decisions d WHERE d.created_by = u.id) as decisions_count,
           (SELECT COUNT(*) FROM decrets dc WHERE dc.created_by = u.id) as decrets_count
    FROM users u 
    WHERE u.role = 'Le personnel des archives'
    ORDER BY u.nom
")->fetchAll();

// Déterminer quelle vue afficher
$view = $_GET['view'] ?? 'stats';
?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="?view=stats" class="list-group-item list-group-item-action <?= $view === 'stats' ? 'active' : '' ?>">
                <i class="bi bi-bar-chart"></i> Menu Statistiques
            </a>
            <a href="?view=personnel" class="list-group-item list-group-item-action <?= $view === 'personnel' ? 'active' : '' ?>">
                <i class="bi bi-people"></i> Personnel des Archives
            </a>
            <a href="arretes/list.php" class="list-group-item list-group-item-action">
                <i class="bi bi-file-earmark-text"></i> Arrêtés
            </a>
            <a href="decisions/list.php" class="list-group-item list-group-item-action">
                <i class="bi bi-file-earmark-check"></i> Décisions
            </a>
            <a href="decrets/list.php" class="list-group-item list-group-item-action">
                <i class="bi bi-file-earmark-ruled"></i> Décrets
            </a>
        </div>
    </div>
    
    <div class="col-md-9">
        <?php if ($view === 'stats'): ?>
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-bar-chart"></i> Statistiques des Documents</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <canvas id="documentsChart" height="200"></canvas>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <div class="card bg-success text-white mb-3">
                                    <div class="card-body">
                                        <h1><?= $stats['arretes'] ?></h1>
                                        <h6>Arrêtés</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="card bg-info text-white mb-3">
                                    <div class="card-body">
                                        <h1><?= $stats['decisions'] ?></h1>
                                        <h6>Décisions</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="card bg-warning text-dark mb-3">
                                    <div class="card-body">
                                        <h1><?= $stats['decrets'] ?></h1>
                                        <h6>Décrets</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Dernière mise à jour : <?= date('d/m/Y H:i') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php elseif ($view === 'personnel'): ?>
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-people"></i> Contributions du Personnel</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Arrêtés</th>
                                <th>Décisions</th>
                                <th>Décrets</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($personnel as $agent): ?>
                            <tr>
                                <td><?= htmlspecialchars($agent['prenom'].' '.$agent['nom']) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-success"><?= $agent['arretes_count'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info"><?= $agent['decisions_count'] ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning text-dark"><?= $agent['decrets_count'] ?></span>
                                </td>
                                <td class="text-center fw-bold">
                                    <?= $agent['arretes_count'] + $agent['decisions_count'] + $agent['decrets_count'] ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Intégration de Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique des documents
    const ctx = document.getElementById('documentsChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Arrêtés', 'Décisions', 'Décrets'],
            datasets: [{
                data: [<?= $stats['arretes'] ?>, <?= $stats['decisions'] ?>, <?= $stats['decrets'] ?>],
                backgroundColor: [
                    '#28a745',
                    '#17a2b8',
                    '#ffc107'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>