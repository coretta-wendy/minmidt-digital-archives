<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/auth.php'; 

//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/config.php';
//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/auth.php';


checkRole('Administrateur');


//require_once $_SERVER['DOCUMENT_ROOT'] . '/archives-minmidt/includes/header.php';




$roles = ['Administrateur', 'CSDACL', 'Le personnel des archives', 'Le personnel du MINMIDT'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => trim($_POST['nom']),
        'prenom' => trim($_POST['prenom']),
        'password' => password_hash(trim($_POST['password']), PASSWORD_DEFAULT),
        'role' => $_POST['role'],
        'is_active' => isset($_POST['is_active']) ? 1 : 0
    ];

    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, password, role, is_active) 
                          VALUES (:nom, :prenom, :password, :role, :is_active)");
    
    if ($stmt->execute($data)) {
        $_SESSION['success'] = "Utilisateur créé avec succès";
        header("Location: list.php");
        exit;
    } else {
        $_SESSION['error'] = "Erreur lors de la création";
    }
}

require_once '../../includes/header.php';
?>

<div class="row">
    <div class="col-md-3">
        <?php include '../menu.php'; ?>
    </div>
    
    <div class="col-md-9">
        <h2>Créer un Utilisateur</h2>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>


<form method="POST">
    <div class="mb-3">
        <label class="form-label">Nom</label>
        <input type="text" name="nom" class="form-control" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Prénom</label>
        <input type="text" name="prenom" class="form-control" required>
    </div>
    
    <!-- 1 <div class="mb-3">
        <label class="form-label">Mot de passe</label>
        <input type="password" name="password" class="form-control" required minlength="6">
    </div>-->

<div class="mb-3">
    <label for="password" class="form-label">Mot de passe</label>
    <div class="input-group">
        <input type="password" class="form-control" id="password" name="password" required>
        <button class="btn btn-outline-secondary toggle-password" type="button">
            <i class="bi bi-eye-slash"></i> <!-- Icône Bootstrap Icons (œil barré par défaut) -->
        </button>
    </div>
</div>


    
    <div class="mb-3">
        <label class="form-label">Rôle</label>
        <select name="role" class="form-select" required>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role ?>"><?= $role ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="mb-3 form-check">
        <input type="checkbox" name="is_active" class="form-check-input" id="is_active" checked>
        <label class="form-check-label" for="is_active">Compte actif</label>
    </div>
    
    <button type="submit" class="btn btn-primary">Enregistrer</button>
    <a href="list.php" class="btn btn-secondary">Annuler</a>
</form>

<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const passwordInput = this.previousElementSibling;
        const icon = this.querySelector('i');
        
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        } else {
            passwordInput.type = "password";
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        }
    });
});
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">


<?php require_once '../../includes/footer.php'; ?>