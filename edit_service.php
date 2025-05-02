<?php
session_start();
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

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: services.php");
    exit;
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$stmt->execute([$id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$service) {
    die("Service non trouvé.");
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_service'])) {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = trim($_POST['prix']);
    $duree = trim($_POST['duree']);
    if (!empty($nom) && !empty($prix) && !empty($duree)) {
        $stmt = $pdo->prepare("UPDATE services SET nom = ?, description = ?, prix = ?, duree = ? WHERE id = ?");
        if ($stmt->execute([$nom, $description, $prix, $duree, $id])) {
            header("Location: services.php");
            exit;
        } else {
            $message = "Erreur lors de la mise à jour.";
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
  <title>Modifier le Service - Salon de Barbier</title>
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
    <h2 class="header"><i class="fas fa-edit"></i> Modifier le Service</h2>
    <?php if ($message): ?>
      <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <form method="POST" action="edit_service.php?id=<?= $service['id'] ?>">
              <div class="mb-3">
                <label for="nom" class="form-label"><i class="fas fa-tag"></i> Nom</label>
                <input type="text" name="nom" id="nom" class="form-control" value="<?= htmlspecialchars($service['nom']) ?>" required>
              </div>
              <div class="mb-3">
                <label for="description" class="form-label"><i class="fas fa-info-circle"></i> Description</label>
                <textarea name="description" id="description" class="form-control"><?= htmlspecialchars($service['description']) ?></textarea>
              </div>
              <div class="mb-3">
                <label for="prix" class="form-label"><i class="fas fa-euro-sign"></i> Prix (€)</label>
                <input type="number" step="0.01" name="prix" id="prix" class="form-control" value="<?= htmlspecialchars($service['prix']) ?>" required>
              </div>
              <div class="mb-3">
                <label for="duree" class="form-label"><i class="fas fa-clock"></i> Durée (minutes)</label>
                <input type="number" name="duree" id="duree" class="form-control" value="<?= htmlspecialchars($service['duree']) ?>" required>
              </div>
              <button type="submit" name="edit_service" class="btn btn-warning btn-custom w-100"><i class="fas fa-check"></i> Mettre à jour</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
