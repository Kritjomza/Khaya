<?php
//register
require_once 'db.php';
require_once 'navbar.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($password !== $confirm) {
        $errors[] = 'รหัสผ่านไม่ตรงกัน';
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $hash]);
            header("Location: login.php?register=success");
            exit;
        } catch (PDOException $e) {
            $errors[] = 'อีเมลนี้ถูกใช้งานแล้ว';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>สมัครสมาชิก</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
  <style>
    body {
      font-family: 'Prompt', sans-serif;
      background: linear-gradient(to right, #c8e6c9, #f0f4c3);
    }
    .floating {
      animation: float 6s ease-in-out infinite;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
  </style>
</head>
<body class="min-h-screen flex flex-col">
  <!-- Navbar แสดงแล้ว -->
  <main class="flex-grow flex items-center justify-center w-full">
    <div class="bg-white p-10 rounded-3xl shadow-lg w-full max-w-md mt-10 floating" data-aos="zoom-in" data-aos-duration="1200">
      <h2 class="text-3xl font-bold text-green-700 text-center mb-6">สมัครสมาชิก</h2>
      <?php if ($errors): ?>
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
          <?= implode('<br>', $errors) ?>
        </div>
      <?php endif; ?>
      <form method="POST" class="space-y-4">
        <input name="username" required placeholder="ชื่อผู้ใช้" class="w-full p-3 rounded-xl border border-green-300 focus:ring-2 focus:ring-green-400" />
        <input name="email" type="email" required placeholder="อีเมล" class="w-full p-3 rounded-xl border border-green-300 focus:ring-2 focus:ring-green-400" />
        <input name="password" type="password" required placeholder="รหัสผ่าน" class="w-full p-3 rounded-xl border border-green-300 focus:ring-2 focus:ring-green-400" />
        <input name="confirm" type="password" required placeholder="ยืนยันรหัสผ่าน" class="w-full p-3 rounded-xl border border-green-300 focus:ring-2 focus:ring-green-400" />
        <button class="w-full py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all shadow-md">
          สมัครสมาชิก
        </button>
      </form>
      <p class="text-center text-sm mt-4">มีบัญชีอยู่แล้ว? <a href="login.php" class="text-green-700 hover:underline">เข้าสู่ระบบ</a></p>
    </div>
  </main>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script>AOS.init();</script>
</body>
</html>
