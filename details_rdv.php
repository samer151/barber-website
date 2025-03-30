<?php
session_start();
require_once 'config.php';

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Make sure an appointment ID is provided
if (!isset($_GET['id'])) {
    header("Location: rendezvous.php");
    exit;
}

$rdv_id = intval($_GET['id']);

// Retrieve details of the selected appointment with JOINs for client, barber and service details
$stmt = $pdo->prepare("SELECT r.*, 
                              u.nom AS client, 
                              c.nom AS coiffeur, 
                              s.nom AS service, 
                              s.prix AS prix_service
                       FROM rendez_vous r 
                       JOIN utilisateurs u ON r.utilisateur_id = u.id 
                       JOIN utilisateurs c ON r.coiffeur_id = c.id 
                       JOIN services s ON r.service_id = s.id 
                       WHERE r.id = ?");
$stmt->execute([$rdv_id]);
$rdv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rdv) {
    echo "Rendez-vous non trouvé.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Détails du Rendez-vous - Salon de Barbier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style2.css">
</head>
<body>
  <div class="overlay"></div>
  
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
  
  <div class="container py-5">
    <h2 class="header mb-4"><i class="fas fa-info-circle"></i> Détails du Rendez-vous</h2>
    <div class="card">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0">Rendez-vous N°<?= htmlspecialchars($rdv['id']) ?></h4>
      </div>
      <div class="card-body">
        <p><strong>Client :</strong> <?= htmlspecialchars($rdv['client']) ?> (ID: <?= $rdv['utilisateur_id'] ?>)</p>
        <p><strong>Coiffeur :</strong> <?= htmlspecialchars($rdv['coiffeur']) ?> (ID: <?= $rdv['coiffeur_id'] ?>)</p>
        <p><strong>Service :</strong> <?= htmlspecialchars($rdv['service']) ?> (ID: <?= $rdv['service_id'] ?>) - <?= htmlspecialchars($rdv['prix_service']) ?>€</p>
        <p><strong>Date & Heure :</strong> <?= date('d/m/Y H:i', strtotime($rdv['date_rdv'])) ?></p>
        <p>
          <strong>Statut :</strong> 
          <span class="badge rounded-pill 
                <?= $rdv['statut'] == 'confirmé' ? 'bg-success' : 
                    ($rdv['statut'] == 'annulé' ? 'bg-danger' : 'bg-warning') ?>">
            <?= htmlspecialchars($rdv['statut']) ?>
          </span>
        </p>

        <?php if ($rdv['statut'] == 'confirmé'): ?>
          <div class="mt-4">
            <h5>Paiement</h5>
            <p>Montant à payer : <?= htmlspecialchars($rdv['prix_service']) ?>€</p>
            <a href="checkout.php?rdv_id=<?= $rdv['id'] ?>" class="btn btn-success">
              <i class="fas fa-credit-card"></i> Payer le Rendez-vous
            </a>
          </div>
        <?php else: ?>
          <div class="mt-4">
            <p class="text-muted">Le paiement n'est pas disponible pour ce rendez-vous (Statut : <?= htmlspecialchars($rdv['statut']) ?>).</p>
          </div>
        <?php endif; ?>
        
        <div class="mt-4">
          <a href="rendezvous.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
          </a>
        </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
