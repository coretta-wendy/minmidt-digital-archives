<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
checkRole('Le personnel des archives');
require_once __DIR__ . '/../../includes/header.php';

// Statistiques des documents de l'utilisateur courant
$user_id = $_SESSION['user_id'];
$stats = [
    'arretes' => $pdo->query("SELECT COUNT(*) FROM arretes WHERE created_by = $user_id")->fetchColumn(),
    'decisions' => $pdo->query("SELECT COUNT(*) FROM decisions WHERE created_by = $user_id")->fetchColumn(),
    'decrets' => $pdo->query("SELECT COUNT(*) FROM decrets WHERE created_by = $user_id")->fetchColumn()
];
?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="dashboard.php" class="list-group-item list-group-item-action active">
                <i class="bi bi-speedometer2"></i> Tableau de bord
            </a>
            <a href="arretes/list.php" class="list-group-item list-group-item-action">
                <i class="bi bi-file-earmark-text"></i> Mes Arrêtés
            </a>
            <a href="decisions/list.php" class="list-group-item list-group-item-action">
                <i class="bi bi-file-earmark-check"></i> Mes Décisions
            </a>
            <a href="decrets/list.php" class="list-group-item list-group-item-action">
                <i class="bi bi-file-earmark-ruled"></i> Mes Décrets
            </a>
        </div>
    </div>
    
    <div class="col-md-9">
        <h2><i class="bi bi-archive"></i> Tableau de bord - Personnel des Archives</h2>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body text-center">
                        <h1><?= $stats['arretes'] ?></h1>
                        <p class="card-text">Arrêtés enregistrés</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body text-center">
                        <h1><?= $stats['decisions'] ?></h1>
                        <p class="card-text">Décisions enregistrées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body text-center">
                        <h1><?= $stats['decrets'] ?></h1>
                        <p class="card-text">Décrets enregistrés</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-clock-history"></i> Derniers documents ajoutés
            </div>
            <div class="card-body">
                <?php
                $recentDocs = $pdo->query("
                    (SELECT 'Arrêté' as type, id, numero, objet, date_signature FROM arretes WHERE created_by = $user_id ORDER BY id DESC LIMIT 2)
                    UNION ALL
                    (SELECT 'Décision' as type, id, numero, objet, date_signature FROM decisions WHERE created_by = $user_id ORDER BY id DESC LIMIT 2)
                    UNION ALL
                    (SELECT 'Décret' as type, id, numero, objet, date_signature FROM decrets WHERE created_by = $user_id ORDER BY id DESC LIMIT 2)
                    ORDER BY date_signature DESC LIMIT 5
                ")->fetchAll();
                ?>
                
                <ul class="list-group">
                    <?php foreach ($recentDocs as $doc): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-<?= 
                                $doc['type'] == 'Arrêté' ? 'primary' : 
                                ($doc['type'] == 'Décision' ? 'success' : 'warning') 
                            ?> me-2">
                                <?= $doc['type'] ?>
                            </span>
                            <?= htmlspecialchars($doc['numero']) ?> - <?= substr(htmlspecialchars($doc['objet']), 0, 50) ?>...
                        </div>
                        <small class="text-muted"><?= date('d/m/Y', strtotime($doc['date_signature'])) ?></small>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>