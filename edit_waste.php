<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = $_POST['id'] ?? null;
  $weight = $_POST['weight'] ?? null;
  $phone = $_POST['phone'] ?? '';
  $message = $_POST['message'] ?? '';

  // Validate
  if ($id && $weight !== null) {
    $stmt = $pdo->prepare("UPDATE waste_sales SET weight = ?, phone = ?, message = ? WHERE id = ? AND user_id = ?");
    $success = $stmt->execute([
      $weight,
      $phone,
      $message,
      $id,
      $_SESSION['user_id']
    ]);

    if ($success) {
      $_SESSION['success'] = "อัปเดตรายการสำเร็จแล้ว";
    } else {
      $_SESSION['error'] = "ไม่สามารถอัปเดตรายการได้";
    }
  } else {
    $_SESSION['error'] = "ข้อมูลไม่ครบถ้วน";
  }

  // กลับไปหน้าหลัก
  header("Location: history.php");
  exit;
}
?>
