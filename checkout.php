<?php
session_start();
require_once 'config.php'; // Ensure your database connection is correctly included
require_once 'C:/xampp/htdocs/site de coiffure/stripe-php-master/stripe-php-master/init.php'; // Path to Stripe init.php

\Stripe\Stripe::setApiKey('sk_test_51R5ooGGEljye7790a3v7NP6RaGrjq5solIavCYojHh1MB6zkVZHBKKTxGRWhqEMI2aUi6XhFM0MU56RJKJVgoW5e00luC3qgwR'); // Replace with your actual Stripe secret key

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Check if a rendez-vous ID is provided via GET
if (!isset($_GET['rdv_id'])) {
    echo "Rendez-vous ID missing.";
    exit;
}

$rdv_id = intval($_GET['rdv_id']);

// Retrieve the rendez-vous details (including service price)
$stmt = $pdo->prepare("SELECT r.*, s.nom AS service, s.prix AS prix_service 
                       FROM rendez_vous r 
                       JOIN services s ON r.service_id = s.id 
                       WHERE r.id = ?");
$stmt->execute([$rdv_id]);
$rdv = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$rdv) {
    echo "Rendez-vous not found.";
    exit;
}

// Convert the service price (in euros) to cents (Stripe works with the smallest currency unit)
$amount = $rdv['prix_service'] * 100;

// Get the current user's ID (assuming you store the user ID in session)
$utilisateur_id = $_SESSION['user_id'];

// Current date (for `date_paiement`)
$date_paiement = date('Y-m-d H:i:s'); // Current timestamp

try {
    // Create a Stripe Checkout session
    $session = \Stripe\Checkout\Session::create([
         'payment_method_types' => ['card'],
         'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Paiement pour le rendez-vous: ' . $rdv['service'],
                ],
                'unit_amount' => $amount,
            ],
            'quantity' => 1,
         ]],
         'mode' => 'payment',
         'success_url' => 'https://yourdomain.com/success.php?session_id={CHECKOUT_SESSION_ID}&rdv_id=' . $rdv_id,
         'cancel_url'  => 'https://yourdomain.com/cancel.php',
    ]);

    // Insert the payment record into the 'paiements' table
    $stmt_payment = $pdo->prepare("INSERT INTO paiements (utilisateur_id, montant, date_paiement, statut) 
                                  VALUES (?, ?, ?, ?)");
    $stmt_payment->execute([$utilisateur_id, $amount / 100, $date_paiement, 'pending']); // 'pending' status for now

    // Redirect to Stripe Checkout page
    header("Location: " . $session->url);
    exit;
} catch (Exception $e) {
    echo "Erreur lors de la création de la session de paiement : " . $e->getMessage();
}
?>
