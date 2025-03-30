<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$stmt = $pdo->query("SELECT r.*, u.nom AS client, c.nom AS coiffeur, s.nom AS service 
                     FROM rendez_vous r 
                     JOIN utilisateurs u ON r.utilisateur_id = u.id 
                     JOIN utilisateurs c ON r.coiffeur_id = c.id 
                     JOIN services s ON r.service_id = s.id
                     ORDER BY r.date_rdv DESC");
$rendezvous = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Rendez-vous - Salon de Barbier</title>
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
  <div class="container mt-5">
    <h2 class="header"><i class="fas fa-calendar-alt"></i> Gestion des Rendez-vous</h2>
    
    <div class="mb-3 text-end">
        <a href="book_rdv.php" class="btn btn-primary btn-custom">
            <i class="fas fa-plus me-2"></i>Prendre un RDV
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>#RDV</th>
                    <th>Client</th>
                    <th>Coiffeur</th>
                    <th>Service</th>
                    <th>Date RDV</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rendezvous as $rdv): ?>
                <tr>
                    <td class="fw-bold"><?= $rdv['id'] ?></td>
                    
                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-muted small">ID: <?= $rdv['utilisateur_id'] ?></span>
                            <?= htmlspecialchars($rdv['client']) ?>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-muted small">ID: <?= $rdv['coiffeur_id'] ?></span>
                            <?= htmlspecialchars($rdv['coiffeur']) ?>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex flex-column">
                            <span class="text-muted small">ID: <?= $rdv['service_id'] ?></span>
                            <?= htmlspecialchars($rdv['service']) ?>
                        </div>
                    </td>

                    <td>
                        <?= date('d/m/Y H:i', strtotime($rdv['date_rdv'])) ?>
                    </td>

                    <td>
                        <span class="badge rounded-pill 
                            <?= $rdv['statut'] == 'confirmé' ? 'bg-success' : 
                                  ($rdv['statut'] == 'annulé' ? 'bg-danger' : 'bg-warning') ?>">
                            <?= htmlspecialchars($rdv['statut']) ?>
                        </span>
                    </td>

                    <td>
                        <div class="d-flex gap-2">
                            <?php if ($rdv['statut'] == 'en attente'): ?>
                            <a href="confirm_rdv.php?id=<?= $rdv['id'] ?>" 
                               class="btn btn-sm btn-success"
                               data-bs-toggle="tooltip" 
                               title="Confirmer le RDV">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="cancel_rdv.php?id=<?= $rdv['id'] ?>" 
                               class="btn btn-sm btn-danger"
                               data-bs-toggle="tooltip"
                               title="Annuler le RDV"
                               onclick="return confirm('Êtes-vous sûr de vouloir annuler ce RDV ?');">
                                <i class="fas fa-times"></i>
                            </a>
                            <?php endif; ?>
                            <a href="details_rdv.php?id=<?= $rdv['id'] ?>" 
                               class="btn btn-sm btn-info"
                               data-bs-toggle="tooltip"
                               title="Voir les détails">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
</script>
</body>
</html>
