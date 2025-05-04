<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'] ?? null;

  if ($id) {
    $stmt = $pdo->prepare("DELETE FROM waste_sales WHERE id = ? AND user_id = ?");
    $success = $stmt->execute([$id, $_SESSION['user_id']]);

    if ($success) {
      $_SESSION['success'] = "ลบรายการสำเร็จแล้ว";
    } else {
      $_SESSION['error'] = "ไม่สามารถลบรายการได้";
    }
  } else {
    $_SESSION['error'] = "ไม่พบรายการที่ต้องการลบ";
  }

  header("Location: history.php");
  exit;
}
?>
