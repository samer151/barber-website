<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmtUser = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
$stmtUser->execute([$_SESSION['user_id']]);
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->query(
  "SELECT s.*, u.nom AS nom_utilisateur
  FROM services AS s
  LEFT JOIN utilisateurs AS u
    ON s.user_id = u.id
  ORDER BY s.id"
);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Accueil - Salon de Barbier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style2.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
      <a class="navbar-brand" href="index.php"><i class="fas fa-cut"></i> Salon de Barbier</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"><i class="fas fa-bars" style="color:#fff;"></i></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</a></li>
          <li class="nav-item"><a class="nav-link" href="services.php"><i class="fas fa-concierge-bell"></i> Services</a></li>
          <li class="nav-item"><a class="nav-link" href="rendezvous.php"><i class="fas fa-calendar-alt"></i> Rendez-vous</a></li>
          <li class="nav-item"><a class="nav-link" href="paiement.php"><i class="fas fa-money-check-alt"></i> Paiements</a></li>
          <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="overlay"></div>
  <button id="barber-button" class="btn btn-primary rounded-circle shadow-lg" title="Toggle Dark/Light Mode">
    <i class="fas fa-cut fs-3"></i>
  </button>



  <div class="container mt-5">
    <h2 class="header"><i class="fas fa-concierge-bell"></i> Gestion des Services</h2>
    
    <?php if ($user['role'] === 'barber'): ?>
    <div class="mb-3 text-end">
      <a href="add_service.php" class="btn btn-primary btn-custom"><i class="fas fa-plus"></i> Ajouter un service</a>
    </div>
    <?php endif; ?>

    <div class="row">
      <?php foreach ($services as $service): ?>
        <div class="col-md-4">
          <div class="card mb-4">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($service['nom']) ?></h5>
              <p class="card-text"><?= nl2br(htmlspecialchars($service['description'])) ?></p>
              <p class="card-text"><strong>Prix :</strong> <?= htmlspecialchars($service['prix']) ?> €</p>
              <p class="card-text"><strong>Durée :</strong> <?= htmlspecialchars($service['duree']) ?> minutes</p>
              <p class="card-text"><strong>Ajouté par :</strong> <?= htmlspecialchars($service['nom_utilisateur']) ?></p>
              <?php if ($user['role'] === 'barber'): ?>
              <a href="edit_service.php?id=<?= $service['id'] ?>" class="btn btn-warning btn-custom"><i class="fas fa-edit"></i> Modifier</a>
              <a href="delete_service.php?id=<?= $service['id'] ?>" class="btn btn-danger btn-custom" onclick="return confirm('Confirmer la suppression ?');"><i class="fas fa-trash"></i> Supprimer</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="theme.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
