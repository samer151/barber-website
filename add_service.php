<?php
session_start();
require_once 'config.php';
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT role FROM utilisateurs WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user || $user['role'] !== 'barber') {
    header("Location: services.php");
    exit;
}




$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_service'])) {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = trim($_POST['prix']);
    $duree = trim($_POST['duree']);

    if (!empty($nom) && !empty($prix) && !empty($duree)) {
        $stmt = $pdo->prepare("INSERT INTO services (nom, description, prix, duree) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nom, $description, $prix, $duree])) {
            header("Location: services.php?service=added");
            exit;
        } else {
            $message = "Erreur lors de l'ajout du service.";
        }
    } else {
        $message = "Veuillez remplir les champs obligatoires.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un Service - Salon de Barbier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style2.css">

</head>
<body>
<button id="barber-button" class="btn btn-primary rounded-circle shadow-lg" title="Toggle Dark/Light Mode">
        <i class="fas fa-cut fs-3"></i>
    </button>
  <div class="overlay"></div>
  <div class="container mt-5">
    <h2 class="header"><i class="fas fa-plus"></i> Ajouter un Service</h2>
    <?php if ($message): ?>
      <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <form method="POST" action="add_service.php">
              <div class="mb-3">
                <label for="nom" class="form-label"><i class="fas fa-tag"></i> Nom</label>
                <input type="text" name="nom" id="nom" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label"><i class="fas fa-info-circle"></i> Description</label>
                <textarea name="description" id="description" class="form-control"></textarea>
              </div>
              <div class="mb-3">
                <label for="prix" class="form-label"><i class="fas fa-euro-sign"></i> Prix (€)</label>
                <input type="number" step="0.01" name="prix" id="prix" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="duree" class="form-label"><i class="fas fa-clock"></i> Durée (minutes)</label>
                <input type="number" name="duree" id="duree" class="form-control" required>
              </div>
              <button type="submit" name="add_service" class="btn btn-primary btn-custom w-100">
                <i class="fas fa-check"></i> Ajouter
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
