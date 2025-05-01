<?php
require_once 'db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  if (password_verify($password, $user['password'])) {
    $update = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $update->execute([$username, $email, $user_id]);
    $success = "อัปเดตข้อมูลสำเร็จแล้ว";
    $user['username'] = $username;
    $user['email'] = $email;
  } else {
    $error = "รหัสผ่านไม่ถูกต้อง";
  }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>โปรไฟล์ผู้ใช้</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
<?php include 'navbar.php'; ?>

<main class="flex-grow">
  <div class="max-w-2xl mx-auto mt-12 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-green-700">ข้อมูลผู้ใช้งาน</h2>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">✅ <?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-100 text-red-600 p-3 mb-4 rounded">❌ <?= $error ?></div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
      <div>
        <label class="block font-semibold mb-1">ชื่อผู้ใช้</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="w-full border p-3 rounded">
      </div>
      <div>
        <label class="block font-semibold mb-1">อีเมล</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full border p-3 rounded">
      </div>
      <div>
        <label class="block font-semibold mb-1">ยืนยันรหัสผ่านก่อนบันทึก</label>
        <input type="password" name="password" required class="w-full border p-3 rounded">
      </div>
      <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 transition">บันทึกข้อมูล</button>
    </form>
  </div>
</main>

<?php include 'footer.php'; ?>
</body>
</html>