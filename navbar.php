<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<nav class="bg-green-700 text-white shadow px-4 py-4 flex justify-between items-center relative">
  <!-- โลโก้ -->
  <div class="text-xl font-bold">
    <a href="index.php" class="hover:text-green-200">ธนาคารขยะชุมชน</a>
  </div>

  <!-- Hamburger button -->
  <button id="nav-toggle" class="md:hidden text-3xl focus:outline-none">
    ☰
  </button>

  <!-- Desktop Menu -->
  <div class="hidden md:flex space-x-4 items-center">
    <a href="index.php" class="hover:underline">การแยกขยะ</a>
    <a href="sell.php" class="hover:underline">ขายขยะ</a>

    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="login.php" class="bg-white text-green-700 px-4 py-1 rounded hover:bg-green-100">เข้าสู่ระบบ</a>
      <a href="register.php" class="bg-white text-green-700 px-4 py-1 rounded hover:bg-green-100">สมัครสมาชิก</a>
    <?php else: ?>
      <div class="relative group">
        <button id="account-toggle" class="bg-white text-green-700 px-4 py-1 rounded hover:bg-green-100">จัดการบัญชี ▾</button>
        <div id="account-menu" class="hidden absolute right-0 mt-2 w-48 bg-white text-green-800 rounded shadow-lg z-50">
          <a href="profile.php" class="block px-4 py-2 hover:bg-green-100">โปรไฟล์</a>
          <?php if ($_SESSION['role'] === 'user'): ?>
            <a href="history.php" class="block px-4 py-2 hover:bg-green-100">ประวัติการขาย</a>
          <?php elseif ($_SESSION['role'] === 'admin'): ?>
            <a href="admin_dashboard.php" class="block px-4 py-2 hover:bg-green-100">แดชบอร์ดผู้ดูแล</a>
          <?php endif; ?>
          <a href="logout.php" class="block px-4 py-2 hover:bg-green-100 border-t">ออกจากระบบ</a>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Mobile Slide Drawer -->
  <div id="mobile-menu" class="fixed top-0 right-0 w-64 h-full bg-green-700 text-white shadow-lg z-50 transform translate-x-full transition-transform duration-300 flex flex-col p-6 space-y-4 md:hidden">
    <button id="close-mobile" class="self-end text-2xl">✖</button>
    <a href="index.php#about" class="hover:underline">การแยกขยะ</a>
    <a href="sell.php" class="hover:underline">ขายขยะ</a>

    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="login.php" class="bg-white text-green-700 px-4 py-2 rounded hover:bg-green-100">เข้าสู่ระบบ</a>
      <a href="register.php" class="bg-white text-green-700 px-4 py-2 rounded hover:bg-green-100">สมัครสมาชิก</a>
    <?php else: ?>
      <a href="profile.php" class="block px-2 py-2 hover:bg-green-600 rounded">โปรไฟล์</a>
      <?php if ($_SESSION['role'] === 'user'): ?>
        <a href="history.php" class="block px-2 py-2 hover:bg-green-600 rounded">ประวัติการขาย</a>
      <?php elseif ($_SESSION['role'] === 'admin'): ?>
        <a href="admin_dashboard.php" class="block px-2 py-2 hover:bg-green-600 rounded">แดชบอร์ดผู้ดูแล</a>
      <?php endif; ?>
      <a href="logout.php" class="block px-2 py-2 hover:bg-green-600 border-t pt-2 mt-2">ออกจากระบบ</a>
    <?php endif; ?>
  </div>
</nav>

<script>
  const navToggle = document.getElementById('nav-toggle');
  const mobileMenu = document.getElementById('mobile-menu');
  const closeMobile = document.getElementById('close-mobile');
  const accountToggle = document.getElementById('account-toggle');
  const accountMenu = document.getElementById('account-menu');

  navToggle?.addEventListener('click', () => {
    mobileMenu.classList.remove('translate-x-full');
  });

  closeMobile?.addEventListener('click', () => {
    mobileMenu.classList.add('translate-x-full');
  });

  accountToggle?.addEventListener('click', () => {
    accountMenu.classList.toggle('hidden');
  });

  window.addEventListener('click', function(e) {
    if (!accountToggle?.contains(e.target) && !accountMenu?.contains(e.target)) {
      accountMenu?.classList.add('hidden');
    }
  });
</script>
