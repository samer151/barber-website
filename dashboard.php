<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$stmt1 = $pdo->query("SELECT COUNT(*) AS total_rdv FROM rendez_vous");
$total_rdv = $stmt1->fetch(PDO::FETCH_ASSOC)['total_rdv'];

$stmt2 = $pdo->query("SELECT COUNT(*) AS total_services FROM services");
$total_services = $stmt2->fetch(PDO::FETCH_ASSOC)['total_services'];

$stmt3 = $pdo->query("SELECT SUM(montant) AS total_revenus FROM paiements WHERE statut = 'payé'");
$total_revenus = $stmt3->fetch(PDO::FETCH_ASSOC)['total_revenus'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de Bord - Salon de Barbier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: url('https://example.com/your-barber-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      font-family: 'Roboto', sans-serif;
    }
    .overlay {
      background-color: rgba(0,0,0,0.6);
      position: fixed; top:0; left:0;
      width:100%; height:100%;
      z-index:-1;
    }
    .card { border-radius: 10px; margin-bottom: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.3); }
    .header { margin-bottom: 30px; text-align: center; color: white; }
  </style>
</head>
<body>
  <div class="overlay"></div>
  <div class="container mt-5">
    <h2 class="header"><i class="fas fa-tachometer-alt"></i> Tableau de Bord</h2>
    <div class="row">
      <div class="col-md-4">
        <div class="card text-white bg-primary">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-calendar-alt"></i> Rendez-vous</h5>
            <p class="card-text"><?= $total_rdv ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-warning">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-concierge-bell"></i> Services</h5>
            <p class="card-text"><?= $total_services ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card text-white bg-success">
          <div class="card-body">
            <h5 class="card-title"><i class="fas fa-euro-sign"></i> Revenus</h5>
            <p class="card-text"><?= $total_revenus ? $total_revenus . ' €' : '0 €' ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
