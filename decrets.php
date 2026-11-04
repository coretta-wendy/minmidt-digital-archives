<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.php');
}
?>

<h1 class="text-center mb-4">Bienvenue dans les archives des décrets</h1>

<div class="row mb-4">
    <div class="col-md-8 mx-auto">
        <div class="input-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un décret...">
            <button class="btn btn-primary" id="searchButton">Rechercher</button>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-hover" id="decretsTable">
        <thead class="table-dark">
            <tr>
                <th>Type</th>
                <th>Numéro</th>
                <th>Timbre</th>
                <th>Objet</th>
                <th>Destinataire</th>
                <th>Signataire</th>
                <th>Date signature</th>
                <th>Année signature</th>
                <th>Fichier</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT * FROM decrets ORDER BY date_signature DESC");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td>{$row['type']}</td>
                        <td>{$row['numero']}</td>
                        <td>{$row['timbre']}</td>
                        <td>" . substr($row['objet'], 0, 50) . "...</td>
                        <td>{$row['destinataire']}</td>
                        <td>{$row['signataire']}</td>
                        <td>{$row['date_signature']}</td>
                        <td>{$row['annee_signature']}</td>
                        <td>";
                if (!empty($row['document_file'])) {
                    echo "<a href='download.php?type=decret&id={$row['id']}' class='btn btn-sm btn-success'>Télécharger</a>";
                } else {
                    echo "N/A";
                }
                echo "</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    const table = document.getElementById('decretsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    // Recherche en temps réel
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        for (let row of rows) {
            let found = false;
            const cells = row.getElementsByTagName('td');
            
            for (let cell of cells) {
                if (cell.textContent.toLowerCase().includes(searchTerm)) {
                    found = true;
                    break;
                }
            }
            
            row.style.display = found ? '' : 'none';
        }
    });
    
    // Recherche au clic
    searchButton.addEventListener('click', function() {
        searchInput.dispatchEvent(new Event('input'));
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>