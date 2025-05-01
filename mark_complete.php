<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['waste_id'])) {
    $stmt = $pdo->prepare("UPDATE waste_sales SET status = 'completed' WHERE id = ?");
    $stmt->execute([$_POST['waste_id']]);
}
header("Location: manage_waste.php");
exit();
