<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$stmt = $pdo->query("SELECT p.*, u.nom FROM paiements p JOIN utilisateurs u ON p.utilisateur_id = u.id ORDER BY p.date_paiement DESC");
$paiements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Historique des Paiements - Salon de Barbier</title>
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
  <button id="barber-button" class="btn btn-primary rounded-circle shadow-lg" title="Toggle Dark/Light Mode">
        <i class="fas fa-cut fs-3"></i>
    </button>
  <div class="overlay"></div>

  <div class="container mt-5">
    <h2 class="header"><i class="fas fa-money-check-alt"></i> Historique des Paiements</h2>
    <div class="table-responsive">
      <table class="table table-dark table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Montant (€)</th>
            <th>Date de Paiement</th>
            <th>Statut</th>
            <th>Facture</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($paiements as $paiement): ?>
            <tr>
              <td><?= $paiement['id'] ?></td>
              <td><?= htmlspecialchars($paiement['nom']) ?></td>
              <td><?= htmlspecialchars($paiement['montant']) ?></td>
              <td><?= htmlspecialchars($paiement['date_paiement']) ?></td>
              <td><?= htmlspecialchars($paiement['statut']) ?></td>
              <td><a href="facture.php?id=<?= $paiement['id'] ?>" class="btn btn-info btn-sm"><i class="fas fa-file-invoice"></i> Voir</a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="theme.js"></script>

</body>
</html>
