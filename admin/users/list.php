<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/header.php';

//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/config.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/auth.php';



checkRole('Administrateur');


//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/header.php';


$search = $_GET['search'] ?? '';
$query = "SELECT * FROM users";
$params = [];

if (!empty($search)) {
    $query .= " WHERE nom LIKE ? OR prenom LIKE ?";
    $params = ["%$search%", "%$search%"];
}

$query .= " ORDER BY nom ASC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-md-3">
        <?php include '../menu.php'; ?>
    </div>
    
    <div class="col-md-9">
        <h2>Gestion des Utilisateurs</h2>
        
        <div class="mb-4">
            <a href="create.php" class="btn btn-primary">Nouvel Utilisateur</a>
            
            <form class="d-inline float-end">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-outline-secondary" type="submit">🔍</button>
                </div>
            </form>
        </div>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                        <td><?= $user['role'] ?></td>
                        <td><?= $user['is_active'] ? '🟢 Actif' : '🔴 Inactif' ?></td>
                        
                        <!-- 1<td>
                            <a href="edit.php?id=< // ?= $user['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                            <//?php if ($user['id'] != 1): ?>
                                <a href="delete.php?id=<//?= $user['id'] ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Confirmer la désactivation?')">
                                   <//?= $user['is_active'] ? 'Désactiver' : 'Activer' ?>
                                </a>
                            <//?php endif; ?>
                        </td>-->
                                <!--2--><td>
                            <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                            <?php if ($user['id'] != 1): ?>
                                <a href="toggle_activation.php?id=<?= $user['id'] ?>" 
                                    class="btn btn-sm <?= $user['is_active'] ? 'btn-danger' : 'btn-success' ?>"
                                    onclick="return confirm('Confirmer <?= $user['is_active'] ? 'la désactivation' : 'l\'activation' ?>?')">
                                    <?= $user['is_active'] ? 'Désactiver' : 'Activer' ?>
                                </a>
                            <?php endif; ?>
                        </td>


                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php require_once '../../includes/footer.php'; ?>