<?php
require_once __DIR__. '/../includes/config.php';
require_once __DIR__.'/../includes/auth.php';
//checkRole('Administrateur');
require_once __DIR__.'/../includes/header.php';

//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/config.php';

// même logique pour les autres includes :
//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/auth.php';
checkRole('Administrateur');

//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/header.php';

?>
<?php
//echo $_SERVER['DOCUMENT_ROOT'];
//echo '../includes/config.php';
?>


<div class="row">
    <div class="col-md-3">
        <div class="list-group">
            <a href="dashboard.php" class="list-group-item list-group-item-action active">Accueil Dashboard</a>
            <a href="users/list.php" class="list-group-item list-group-item-action">Gestion des utilisateurs</a>
            <a href="arretes/list.php" class="list-group-item list-group-item-action">Arrêtés</a>
            <a href="decisions/list.php" class="list-group-item list-group-item-action">Décisions</a>
            <a href="decrets/list.php" class="list-group-item list-group-item-action">Décrets</a>
        </div>
    </div>
    
    <div class="col-md-9">
        <h2>Tableau de bord Administrateur</h2>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Utilisateurs</h5>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) FROM users");
                        $count = $stmt->fetchColumn();
                        ?>
                        <p class="card-text display-4"><?php echo $count; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Arrêtés</h5>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) FROM arretes");
                        $count = $stmt->fetchColumn();
                        ?>
                        <p class="card-text display-4"><?php echo $count; ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Décisions</h5>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) FROM decisions");
                        $count = $stmt->fetchColumn();
                        ?>
                        <p class="card-text display-4"><?php echo $count; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Décrets</h5>
                        <?php
                        $stmt = $pdo->query("SELECT COUNT(*) FROM decrets");
                        $count = $stmt->fetchColumn();
                        ?>
                        <p class="card-text display-4"><?php echo $count; ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Derniers utilisateurs créés
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Rôle</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->query("SELECT nom, prenom, role FROM users ORDER BY id DESC LIMIT 5");
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>
                                            <td>{$row['nom']}</td>
                                            <td>{$row['prenom']}</td>
                                            <td>{$row['role']}</td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Derniers documents ajoutés
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Numéro</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Requête combinée pour les 3 types de documents
                                $query = "(SELECT 'Arrêté' as type, numero, date_signature FROM arretes ORDER BY id DESC LIMIT 2)
                                          UNION ALL
                                          (SELECT 'Décision' as type, numero, date_signature FROM decisions ORDER BY id DESC LIMIT 2)
                                          UNION ALL
                                          (SELECT 'Décret' as type, numero, date_signature FROM decrets ORDER BY id DESC LIMIT 2)
                                          ORDER BY date_signature DESC LIMIT 5";
                                
                                $stmt = $pdo->query($query);
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>
                                            <td>{$row['type']}</td>
                                            <td>{$row['numero']}</td>
                                            <td>{$row['date_signature']}</td>
                                          </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
//require_once '../../includes/footer.php'; 

//require_once '/ARCHIVES-MINMIDT/includes/footer.php'; 
require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/footer.php';
?>