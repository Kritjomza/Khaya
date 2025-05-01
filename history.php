<?php
require_once 'db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
  header("Location: login.php");
  exit;
}

$user_id = $_SESSION['user_id'];
$status = $_GET['status'] ?? '';

if ($status) {
  $waste_sales = $pdo->prepare("SELECT ws.*, wc.name AS category_name, wsc.name AS subcategory_name
    FROM waste_sales ws
    JOIN waste_categories wc ON ws.category_id = wc.id
    JOIN waste_subcategories wsc ON ws.subcategory_id = wsc.id
    WHERE ws.user_id = ? AND ws.status = ? ORDER BY ws.created_at DESC");
  $waste_sales->execute([$user_id, $status]);
} else {
  $waste_sales = $pdo->prepare("SELECT ws.*, wc.name AS category_name, wsc.name AS subcategory_name
    FROM waste_sales ws
    JOIN waste_categories wc ON ws.category_id = wc.id
    JOIN waste_subcategories wsc ON ws.subcategory_id = wsc.id
    WHERE ws.user_id = ? ORDER BY ws.created_at DESC");
  $waste_sales->execute([$user_id]);
}
$data = $waste_sales->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ประวัติการขาย</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">
<?php include 'navbar.php'; ?>
<main class="flex-grow px-4 py-10 max-w-6xl mx-auto" data-aos="fade-in">
  <h1 class="text-3xl font-bold text-center mb-6">ประวัติการขายขยะของคุณ</h1>

  <form method="GET" class="mb-6 text-center">
    <label class="mr-2 font-semibold">กรองตามสถานะ:</label>
    <select name="status" onchange="this.form.submit()" class="p-2 border rounded">
      <option value="">ทั้งหมด</option>
      <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>รอดำเนินการ</option>
      <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>เก็บแล้ว</option>
    </select>
  </form>

  <?php if (count($data) === 0): ?>
    <p class="text-center text-gray-500">ยังไม่มีรายการขายขยะ</p>
  <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <?php foreach ($data as $item): ?>
        <div class="bg-white rounded-xl shadow p-6 relative">
          <h3 class="text-lg font-semibold text-green-700 mb-2">ประเภท: <?= htmlspecialchars($item['category_name']) ?> - <?= htmlspecialchars($item['subcategory_name']) ?></h3>
          <p><strong>น้ำหนัก:</strong> <?= $item['weight'] ?> กก.</p>
          <p><strong>เบอร์โทร:</strong> <?= htmlspecialchars($item['phone']) ?></p>
          <p><strong>ข้อความ:</strong> <?= nl2br(htmlspecialchars($item['message'])) ?></p>
          <?php if ($item['image']): ?>
            <div class="mt-2"><img src="../<?= $item['image'] ?>" alt="รูปขยะ" class="w-full max-h-40 object-cover rounded" /></div>
          <?php endif; ?>
          <p class="mt-2 text-sm text-gray-500">
            <strong>ตำแหน่ง:</strong>
            <?= number_format($item['latitude'], 5) ?>, <?= number_format($item['longitude'], 5) ?>
          </p>
          <p class="text-sm text-gray-400">เมื่อ: <?= date('d/m/Y H:i', strtotime($item['created_at'])) ?></p>
          <div class="mt-4 flex gap-2">
            <a href="edit_waste.php?id=<?= $item['id'] ?>" class="bg-yellow-400 text-white px-4 py-1 rounded hover:bg-yellow-500 transition">แก้ไข</a>
            <a href="delete_waste.php?id=<?= $item['id'] ?>" onclick="return confirm('ยืนยันลบรายการนี้?')" class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600 transition">ลบ</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>
<?php include 'footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>