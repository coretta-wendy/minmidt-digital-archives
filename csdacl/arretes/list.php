<?php
//1require_once __DIR__ . '/../../../includes/config.php';
//2require_once __DIR__ . '/../../../includes/auth.php';
require_once '../../includes/config.php';
require_once '../../includes/auth.php';

checkRole('CSDACL');
//3require_once __DIR__ . '/../../../includes/header.php';


require_once '../../includes/header.php';



$stmt = $pdo->query("SELECT * FROM arretes ORDER BY date_signature DESC");
$arretes = $stmt->fetchAll();



?>


<div class="col-md-9">
        <h2>Gestion des Arrêtés</h2>
        
        <div class="mb-4">
            <a href="create.php" class="btn btn-primary">Ajouter un Arrêté</a>
            <!--<button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#importModal">Importer Excel</button> c'est unique ment ca qui a marcher: href="#importmodal"-->
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#importModal">Importer Excel</button>
        </div>
        
        <div class="card">
            <div class="card-header">
                Liste des Arrêtés
            </div>
            <div class="card-body">




                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Numéro</th>
                                        <th>Objet</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($arretes as $arrete): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($arrete['numero']) ?></td>
                                        <td><?= substr(htmlspecialchars($arrete['objet']), 0, 50) ?>...</td>
                                        <td><?= $arrete['date_signature'] ?></td>
                                        <td>
                                            <a href="edit.php?id=<//?= $arrete['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                                            <a href="delete.php?id=<?= $arrete['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer?')">Supprimer</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                     </div>
            </div>
        </div>



        <!-- 1Modal Import Excel -->
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





<?php 

//4require_once __DIR__ . '/../../../includes/footer.php'; 
require_once '../../includes/footer.php';

?>