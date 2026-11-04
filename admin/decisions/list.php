<?php
require_once '../../includes/config.php';
require_once '../../includes/auth.php';
checkRole('Administrateur');
require_once '../../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include '../menu.php'; ?>
    </div>
    
    <div class="col-md-9">
        <h2>Gestion des Décisions</h2>
        
        <div class="mb-4">
            <a href="create.php" class="btn btn-primary">Ajouter une Décision</a>
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#importModal">Importer Excel</button>
        </div>
        
        <div class="card">
            <div class="card-header">
                Liste des Décisions
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="decisionsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Numéro</th>
                                <th>Objet</th>
                                <th>Signataire</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("SELECT * FROM decisions ORDER BY date_signature DESC");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$row['numero']}</td>
                                        <td>" . substr($row['objet'], 0, 50) . "...</td>
                                        <td>{$row['signataire']}</td>
                                        <td>{$row['date_signature']}</td>
                                        <td>
                                            <a href='edit.php?id={$row['id']}' class='btn btn-sm btn-warning'>Modifier</a>
                                            <a href='delete.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Êtes-vous sûr ?\")'>Supprimer</a>
                                        </td>
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

<!-- Modal Import Excel -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Importer depuis Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="import.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excelFile" class="form-label">Fichier Excel</label>
                        <input class="form-control" type="file" id="excelFile" name="excelFile" accept=".xlsx, .xls" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Importer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../../includes/footer.php'; ?>