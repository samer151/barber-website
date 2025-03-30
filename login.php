<?php
session_start();
require_once 'config.php'; 

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    if (!empty($email) && !empty($mot_de_passe)) {
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            header("Location: index.php");
            exit;
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Salon de Barbier</title>
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
                <?php if ($message): ?>
                    <div class="alert alert-warning"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>
                <div class="card">
                    <div class="card-header text-center text-white py-4">
                        <img src="images.jpeg" alt="Logo Barber" class="custom-logo">
                        <h4><i class="fas fa-cut"></i> Bienvenue au Salon</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label for="email" ><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Votre email" required>
                            </div>
                            <div class="mb-3">
                                <label for="mot_de_passe" ><i class="fas fa-lock"></i> Mot de passe</label>
                                <input type="password" name="mot_de_passe" class="form-control" id="mot_de_passe" placeholder="Votre mot de passe" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-dark w-100">
                                <i class="fas fa-arrow-right"></i> Se connecter
                            </button>
                        </form>
                        <p class="mt-3 text-center">
                            Pas encore inscrit ? <a href="register.php" class="text-decoration-none">Créez un compte</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="theme.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
