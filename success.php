<?php
session_start();
require_once 'config.php';
require_once 'C:/xampp/htdocs/site de coiffure/stripe-php-master/stripe-php-master/init.php';

$session_id = $_GET['session_id'];
$rdv_id = $_GET['rdv_id'];

try {
    $session = \Stripe\Checkout\Session::retrieve($session_id);

    if ($session->payment_status == 'paid') {
        $stmt = $pdo->prepare("INSERT INTO paiements (utilisateur_id, montant, date_paiement, statut) 
                               VALUES (?, ?, NOW(), ?)");
        $stmt->execute([$_SESSION['user_id'], $session->amount_total / 100, 'success']);

        header("Location: confirmation.php?rdv_id=" . $rdv_id);
        exit;
    } else {
        echo "Payment not successful.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
