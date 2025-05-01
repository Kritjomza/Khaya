<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Sidebar -->
<aside id="sidebar" class="bg-green-700 text-white w-64 min-h-screen fixed top-0 left-0 z-40 shadow-lg transform md:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out">
    <div class="text-center p-6 border-b border-green-600">
        <a href="admin_dashboard.php" class="text-2xl font-bold hover:text-lime-300 block">ธนาคารขยะ (Admin)</a>
    </div>
    <nav class="p-4 space-y-3 text-lg">
        <a href="admin_dashboard.php"
           class="block px-4 py-2 rounded transition-all <?= $current_page == 'admin_dashboard.php' ? 'bg-green-500 font-semibold' : 'hover:bg-green-600'; ?>">
            📊 แดชบอร์ด
        </a>

        <a href="manage_waste.php"
           class="block px-4 py-2 rounded transition-all <?= $current_page == 'manage_waste.php' ? 'bg-green-500 font-semibold' : 'hover:bg-green-600'; ?>">
            🗂 จัดการขยะ
        </a>

        <a href="collect_route.php"
            class="block px-4 py-2 rounded transition-all <?= $current_page == 'collect_waste.php' ? 'bg-green-500 font-semibold' : 'hover:bg-green-600'; ?>">
            🚚 การเก็บขยะ
        </a>


        <a href="logout.php"
           class="block px-4 py-2 rounded transition-all <?= $current_page == 'logout.php' ? 'bg-green-500 font-semibold' : 'hover:bg-green-600'; ?>">
            🚪 ออกจากระบบ
        </a>
    </nav>
</aside>

<!-- Sidebar toggle button (Mobile) -->
<div class="md:hidden fixed top-4 left-4 z-50">
    <button id="sidebar-toggle" class="bg-green-700 text-white p-2 rounded-full shadow-md focus:outline-none">
        <i class="fas fa-bars"></i>
    </button>
</div>

<script>
    const sidebar = document.getElementById('sidebar');
    const toggleButton = document.getElementById('sidebar-toggle');
    toggleButton?.addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
    });
</script>

<!-- FontAwesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js" crossorigin="anonymous"></script>
