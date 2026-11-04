<!-- 1<div class="list-group">
    <a href="../dashboard.php" class="list-group-item list-group-item-action ">Accueil Dashboard</a>
    <a href="../users/list.php" class="list-group-item list-group-item-action">Gestion des utilisateurs</a>
    <a href="../arretes/list.php" class="list-group-item list-group-item-action active">Arrêtés</a>
    <a href="../decisions/list.php" class="list-group-item list-group-item-action">Décisions</a>
    <a href="../decrets/list.php" class="list-group-item list-group-item-action">Décrets</a>
</div>-->

<div class="list-group" id="mainMenu">
    <a href="../dashboard.php" class="list-group-item list-group-item-action">Accueil Dashboard</a>
    <a href="../users/list.php" class="list-group-item list-group-item-action">Gestion des utilisateurs</a>
    <a href="../arretes/list.php" class="list-group-item list-group-item-action">Arrêtés</a>
    <a href="../decisions/list.php" class="list-group-item list-group-item-action">Décisions</a>
    <a href="../decrets/list.php" class="list-group-item list-group-item-action">Décrets</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuLinks = document.querySelectorAll('#mainMenu a');
    const currentPath = window.location.pathname;

    menuLinks.forEach(link => {
        // Normalise les chemins pour la comparaison
        const linkPath = new URL(link.href).pathname;
        
        // Vérifie si le lien correspond à la page actuelle
        if (currentPath === linkPath) {
            link.classList.add('active');
        }

        // Gestion du clic
        link.addEventListener('click', function(e) {
            menuLinks.forEach(item => item.classList.remove('active'));
            this.classList.add('active');
            
            // Optionnel: si vous voulez garder l'active après rechargement
            localStorage.setItem('activeMenu', this.href);
        });
    });

    // Restaure l'état actif depuis le stockage local
    const savedActive = localStorage.getItem('activeMenu');
    if (savedActive) {
        menuLinks.forEach(link => {
            if (link.href === savedActive) {
                link.classList.add('active');
            }
        });
    }
});
</script>