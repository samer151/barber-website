<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("UPDATE rendez_vous SET statut = 'annulé' WHERE id = ?");
    $stmt->execute([$id]);
}
header("Location: rendezvous.php");
exit;
?>
