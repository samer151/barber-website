<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Mise à jour du statut en "confirmé"
    $stmt = $pdo->prepare("UPDATE rendez_vous SET statut = 'confirmé' WHERE id = ?");
    $stmt->execute([$id]);
}
header("Location: rendezvous.php");
exit;
?>
