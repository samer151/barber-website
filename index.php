<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$services = [
    ['id' => 1, 'nom' => 'Coupe Homme', 'description' => 'Shampooing + Coupe + Séchage', 'prix' => '15.00', 'duree' => 30],
    ['id' => 2, 'nom' => 'Coupe Femme', 'description' => 'Shampooing + Coupe + Brushing', 'prix' => '25.00', 'duree' => 45],
    ['id' => 3, 'nom' => 'Coloration', 'description' => 'Coloration complète avec soins', 'prix' => '40.00', 'duree' => 90],
];
$servicesChunks = array_chunk($services, 3);
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
  <button id="barber-button" class="btn btn-primary rounded-circle shadow-lg" title="Toggle Dark/Light Mode">
        <i class="fas fa-cut fs-3"></i>
    </button>
  
  <div class="container mt-5">
    <h1 class="header"><i class="fas fa-cut"></i> Bienvenue sur votre espace</h1>
    <div class="row g-4">
      <div class="col-md-3">
        <div class="card nav-card">
          <a href="dashboard.php" class="text-decoration-none">
            <div class="card-body">
              <i class="fas fa-tachometer-alt nav-icon"></i>
              <div class="nav-title">Tableau de Bord</div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card nav-card">
          <a href="services.php" class="text-decoration-none">
            <div class="card-body">
              <i class="fas fa-concierge-bell nav-icon"></i>
              <div class="nav-title">Services</div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card nav-card">
          <a href="rendezvous.php" class="text-decoration-none">
            <div class="card-body">
              <i class="fas fa-calendar-alt nav-icon"></i>
              <div class="nav-title">Rendez-vous</div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card nav-card">
          <a href="paiement.php" class="text-decoration-none">
            <div class="card-body">
              <i class="fas fa-money-check-alt nav-icon"></i>
              <div class="nav-title">Paiements</div>
            </div>
          </a>
        </div>
      </div>
    </div>
    
    <div class="row g-4 mt-4">

      <div class="col-md-4">
        <div class="card nav-card">
          <a href="book_rdv.php" class="text-decoration-none">
            <div class="card-body">
              <i class="fas fa-calendar-plus nav-icon"></i>
              <div class="nav-title">Prendre un RDV</div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card nav-card">
          <a href="facture.php" class="text-decoration-none">
            <div class="card-body">
              <i class="fas fa-file-invoice nav-icon"></i>
              <div class="nav-title">Voir une Facture</div>
            </div>
          </a>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-5">
        <h2 class="header text-white mb-4"><i class="fas fa-concierge-bell"></i> Nos Services</h2>
        <div id="servicesCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($servicesChunks as $index => $chunk): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="row g-4">
                        <?php foreach ($chunk as $service): ?>
                        <div class="col-md-4">
                            <div class="card h-100 service-card">
                                <div class="card-body text-center">
                                    <i class="fas fa-cut service-icon mb-3"></i>
                                    <h5 class="card-title"><?= $service['nom'] ?></h5>
                                    <p class="card-text"><?= $service['description'] ?></p>
                                    <div class="mt-3">
                                        <span class="badge bg-primary"><?= $service['prix'] ?>€</span>
                                        <span class="badge bg-secondary"><?= $service['duree'] ?> min</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php if(count($servicesChunks) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#servicesCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#servicesCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
            <?php endif; ?>
        </div>
    </div>
  </div>

  <footer class="footer mt-5 py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-4 text-center text-md-start">
                <h5 class="text-white mb-3"><i class="fas fa-cut"></i> Salon de Barbier</h5>
                <p class="text-white mb-1"><i class="fas fa-map-marker-alt"></i> 123 Rue du Barbier, Paris</p>
                <p class="text-white"><i class="fas fa-phone"></i> +33 1 23 45 67 89</p>
            </div>
            <div class="col-md-4 text-center my-3 my-md-0">
                <div class="d-flex justify-content-center gap-3">
                    <a href="#" class="text-white"><i class="fab fa-facebook fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-instagram fa-lg"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                </div>
            </div>
            <div class="col-md-4 text-center text-md-end">
                <p class="text-white mb-0">
                    © <?= date('Y') ?> Salon de Barbier<br>
                    Tous droits réservés
                </p>
            </div>
        </div>
    </div>
  </footer>

  
  <script src="theme.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
