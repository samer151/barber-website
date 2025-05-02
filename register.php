<?php
require_once 'config.php'; 

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe']; 
    $role = $_POST['role'] ?? 'client'; 
    $barber_code = trim($_POST['barber_code'] ?? ''); 
    $errors = [];

    if (empty($nom)) {
        $errors[] = "Le nom est requis";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide";
    }

    if (empty($mot_de_passe) || strlen($mot_de_passe) < 8) {
        $errors[] = "Le mot de passe doit avoir au moins 8 caractères";
    }

    $allowed_roles = ['client', 'barber'];
    if (!in_array($role, $allowed_roles)) {
        $errors[] = "Rôle invalide";
    }

    if ($role === 'barber' && $barber_code !== 'samer.123') {
        $errors[] = "Code barber incorrect";
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->rowCount() > 0) {
                $errors[] = "Cet email est déjà utilisé";
            } else {
                $hash = password_hash($mot_de_passe, PASSWORD_BCRYPT);
                
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");

                if ($stmt->execute([$nom, $email, $hash, $role])) {
                    $message = "Inscription réussie en tant que " . htmlspecialchars($role);
                } else {
                    $message = "Erreur lors de l'inscription";
                }
            }
        } catch (PDOException $e) {
            $message = "Erreur database : " . $e->getMessage();
        }
    } else {
        $message = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<button id="barber-button" class="btn btn-primary rounded-circle shadow-lg" title="Toggle Dark/Light Mode">
    <i class="fas fa-cut fs-3"></i>
</button>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-user-plus"></i> Inscription</h3>
                </div>
                
                <div class="card-body">
                    <?php if (!empty($message)): ?>
                        <div class="alert <?= strpos($message, 'réussie') !== false ? 'alert-success' : 'alert-danger' ?>">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-user"></i> Nom complet</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
                            <input type="password" name="mot_de_passe" class="form-control" minlength="8" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-user-tag"></i> Rôle</label>
                            <select name="role" class="form-select" required>
                                <option value="client">Client</option>
                                <option value="barber">Barbier</option>
                            </select>
                        </div>

                        <div class="mb-3" id="barber-code-input" style="display: none;">
                            <label class="form-label"><i class="fas fa-key"></i> Code Barber</label>
                            <input type="text" name="barber_code" class="form-control" placeholder="Entrez votre code barber">
                        </div>
                        
                        <button type="submit" name="register" class="btn btn-primary w-100">
                            <i class="fas fa-check"></i> S'inscrire
                        </button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <a href="login.php" class="text-decoration-none">
                            <i class="fas fa-sign-in-alt"></i> Déjà inscrit ? Connectez-vous
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const roleSelect = document.querySelector('select[name="role"]');
    const barberCodeInput = document.getElementById('barber-code-input');
    
    roleSelect.addEventListener('change', function() {
        if (this.value === 'barber') {
            barberCodeInput.style.display = 'block';
        } else {
            barberCodeInput.style.display = 'none';
        }
    });
</script>

<script src="theme.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
