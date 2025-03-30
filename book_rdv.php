<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';

try {
    $stmtServices = $pdo->query("SELECT * FROM services");
    $services = $stmtServices->fetchAll(PDO::FETCH_ASSOC);
    
    $stmtCoiffeurs = $pdo->query("SELECT * FROM utilisateurs WHERE role = 'barber'");
    $coiffeurs = $stmtCoiffeurs->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $message = "Erreur de connexion à la base de données";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_rdv'])) {
    $service_id = $_POST['service_id'];
    $coiffeur_id = $_POST['coiffeur_id'];
    $date_rdv = $_POST['date_rdv'];
    
    if (empty($service_id) || empty($coiffeur_id) || empty($date_rdv)) {
        $message = "Veuillez remplir tous les champs.";
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM rendez_vous 
                                  WHERE coiffeur_id = ? AND date_rdv = ?");
            $stmt->execute([$coiffeur_id, $date_rdv]);
            
            if ($stmt->rowCount() > 0) {
                $message = "Ce créneau est déjà pris. Veuillez choisir un autre horaire.";
            } else {
                // Créer le rendez-vous
                $stmt = $pdo->prepare("INSERT INTO rendez_vous 
                                    (utilisateur_id, coiffeur_id, service_id, date_rdv, statut) 
                                    VALUES (?, ?, ?, ?, 'en attente')");
                
                if ($stmt->execute([$_SESSION['user_id'], $coiffeur_id, $service_id, $date_rdv])) {
                    header("Location: rendezvous.php?success=1");
                    exit;
                } else {
                    $message = "Erreur lors de la réservation.";
                }
            }
        } catch (PDOException $e) {
            error_log("Booking error: " . $e->getMessage());
            $message = "Erreur lors de la réservation.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Prendre un RDV - Salon de Barbier</title>
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
    <div class="row justify-content-center">
      <div class="col-md-8 col-lg-6">
        <div class="card">
          <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Prendre un Rendez-vous</h3>
          </div>
          <div class="card-body p-4">
            <?php if ($message): ?>
              <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST" action="book_rdv.php">
              <div class="mb-4">
                <label for="service_id" class="form-label"><i class="fas fa-cut me-2"></i>Service</label>
                <select name="service_id" id="service_id" class="form-select" required>
                  <option value="">Choisir un service...</option>
                  <?php foreach ($services as $service): ?>
                    <option value="<?= $service['id'] ?>" <?= isset($_POST['service_id']) && $_POST['service_id'] == $service['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($service['nom']) ?> - <?= $service['prix'] ?>€
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-4">
                <label for="coiffeur_id" class="form-label"><i class="fas fa-user me-2"></i>Coiffeur</label>
                <select name="coiffeur_id" id="coiffeur_id" class="form-select" required>
                  <option value="">Choisir un coiffeur...</option>
                  <?php foreach ($coiffeurs as $coiffeur): ?>
                    <option value="<?= $coiffeur['id'] ?>" <?= isset($_POST['coiffeur_id']) && $_POST['coiffeur_id'] == $coiffeur['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($coiffeur['nom']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-4">
                <label for="date_rdv" class="form-label"><i class="fas fa-clock me-2"></i>Date et Heure</label>
                <input type="datetime-local" 
                       name="date_rdv" 
                       id="date_rdv" 
                       class="form-control" 
                       min="<?= date('Y-m-d\TH:i') ?>" 
                       value="<?= isset($_POST['date_rdv']) ? htmlspecialchars($_POST['date_rdv']) : '' ?>" 
                       required>
                <small class="form-text text-muted">Heures d'ouverture: 09:00 - 19:00</small>
              </div>

              <div class="d-grid gap-2">
                <button type="submit" name="book_rdv" class="btn btn-primary btn-lg">
                  <i class="fas fa-check me-2"></i>Confirmer la réservation
                </button>
                <a href="rendezvous.php" class="btn btn-secondary">
                  <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('date_rdv').addEventListener('change', function() {
      const selectedDate = new Date(this.value);
      const hours = selectedDate.getHours();
      const day = selectedDate.getDay();
      
      if (day === 0 || day === 6) { // Dimanche = 0, Samedi = 6
        this.setCustomValidity('Le salon est fermé le week-end');
      } else if (hours < 9 || hours >= 19) {
        this.setCustomValidity('Heures d\'ouverture: 09:00 - 19:00');
      } else {
        this.setCustomValidity('');
      }
    });
  </script>
</body>
</html>