<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if (!isset($_GET['id'])) {
    header("Location: paiement.php");
    exit;
}
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT p.*, u.nom FROM paiements p JOIN utilisateurs u ON p.utilisateur_id = u.id WHERE p.id = ?");
$stmt->execute([$id]);
$facture = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$facture) {
    die("Facture non trouvée.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Facture - Salon de Barbier</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Roboto', sans-serif; margin: 20px; }
    .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0,0,0,0.15); font-size: 16px; line-height: 24px; color: #555; }
    .invoice-box table { width: 100%; line-height: inherit; text-align: left; }
    .invoice-box table td { padding: 5px; vertical-align: top; }
    .invoice-box table tr.top table td { padding-bottom: 20px; }
    .invoice-box table tr.information table td { padding-bottom: 40px; }
    .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
  </style>

</head>
<body>
    
  <div class="invoice-box">
    <table>
      <tr class="top">
        <td colspan="2">
          <table>
            <tr>
              <td class="title">
                <h2>Salon de Barbier</h2>
              </td>
              <td>
                Facture #: <?= $facture['id'] ?><br>
                Date: <?= $facture['date_paiement'] ?>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr class="information">
        <td colspan="2">
          <table>
            <tr>
              <td>Client: <?= htmlspecialchars($facture['nom']) ?></td>
              <td>
                Salon de Barbier<br>
                Adresse du salon
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr class="heading">
        <td>Description</td>
        <td>Montant</td>
      </tr>
      <tr class="item">
        <td>Service(s) rendu(s)</td>
        <td><?= htmlspecialchars($facture['montant']) ?> €</td>
      </tr>
      <tr class="total">
        <td></td>
        <td><strong>Total: <?= htmlspecialchars($facture['montant']) ?> €</strong></td>
      </tr>
    </table>
  </div>
</body>
</html>
