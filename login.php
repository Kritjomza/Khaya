<?php
//login
require_once 'db.php';
require_once 'navbar.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        header("Location: " . ($user['role'] === 'admin' ? 'admin_dashboard.php' : 'index.php'));
        exit;
    } else {
        $error = 'อีเมลหรือรหัสผ่านไม่ถูกต้อง';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>เข้าสู่ระบบ</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Prompt&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
  <style>
    body {
      font-family: 'Prompt', sans-serif;
      background: linear-gradient(to right, #a8e6cf, #dcedc1);
    }
    .floating {
      animation: float 5s ease-in-out infinite;
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
    <div class="bg-white p-10 rounded-3xl shadow-lg w-full max-w-md mt-10 floating" data-aos="fade-up" data-aos-duration="1200">
      <h2 class="text-3xl font-bold text-green-700 text-center mb-6">เข้าสู่ระบบ</h2>
      <?php if ($error): ?>
        <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm"><?= $error ?></div>
      <?php endif; ?>
      <form method="POST" class="space-y-5">
        <input name="email" type="email" required placeholder="อีเมล" class="w-full p-3 rounded-xl border border-green-300 focus:ring-2 focus:ring-green-400" />
        <input name="password" type="password" required placeholder="รหัสผ่าน" class="w-full p-3 rounded-xl border border-green-300 focus:ring-2 focus:ring-green-400" />
        <button class="w-full py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition-all shadow-md">
          เข้าสู่ระบบ
        </button>
      </form>
      <p class="text-center text-sm mt-4">ยังไม่มีบัญชี? <a href="register.php" class="text-green-700 hover:underline">สมัครสมาชิก</a></p>
    </div>
  </main>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
  <script>AOS.init();</script>
</body>
</html>
