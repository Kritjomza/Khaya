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
<?php if (isset($_SESSION['success'])): ?>
  <div class="bg-green-100 text-green-800 px-4 py-2 mb-4 rounded text-center"><?= $_SESSION['success'] ?></div>
  <?php unset($_SESSION['success']); ?>
<?php elseif (isset($_SESSION['error'])): ?>
  <div class="bg-red-100 text-red-800 px-4 py-2 mb-4 rounded text-center"><?= $_SESSION['error'] ?></div>
  <?php unset($_SESSION['error']); ?>
<?php endif; ?>

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
            <a href="#" onclick="openModal(<?= $item['id'] ?>, '<?= $item['weight'] ?>', '<?= htmlspecialchars($item['phone']) ?>', `<?= htmlspecialchars($item['message']) ?>`)" class="bg-yellow-400 text-white px-4 py-1 rounded hover:bg-yellow-500 transition">แก้ไข</a>

            <a href="#" onclick="openDeleteModal(<?= $item['id'] ?>)" class="bg-red-500 text-white px-4 py-1 rounded hover:bg-red-600 transition">ลบ</a>


          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-xl relative">
    <h2 class="text-xl font-bold mb-4">แก้ไขรายการขาย</h2>
    <form id="editForm" method="POST" enctype="multipart/form-data" action="edit_waste.php">
      <input type="hidden" name="id" id="editId">
      <div class="mb-2">
        <label>น้ำหนัก (กก.):</label>
        <input type="number" step="0.01" name="weight" id="editWeight" class="w-full border p-2 rounded">
      </div>
      <div class="mb-2">
        <label>เบอร์โทร:</label>
        <input type="text" name="phone" id="editPhone" class="w-full border p-2 rounded">
      </div>
      <div class="mb-2">
        <label>ข้อความ:</label>
        <textarea name="message" id="editMessage" class="w-full border p-2 rounded"></textarea>
      </div>
      <div class="flex justify-end mt-4 gap-2">
        <button type="button" onclick="closeModal()" class="bg-gray-400 text-white px-4 py-1 rounded">ยกเลิก</button>
        <button type="submit" class="bg-green-500 text-white px-4 py-1 rounded">บันทึก</button>
      </div>
    </form>
  </div>
</div>
<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-sm text-center">
    <h2 class="text-xl font-bold text-red-600 mb-4">ยืนยันการลบ</h2>
    <p>คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?</p>
    <form method="POST" action="delete_waste.php" class="mt-6 flex justify-center gap-4">
      <input type="hidden" name="id" id="deleteId">
      <button type="button" onclick="closeDeleteModal()" class="bg-gray-400 text-white px-4 py-1 rounded">ยกเลิก</button>
      <button type="submit" class="bg-red-500 text-white px-4 py-1 rounded">ลบ</button>
    </form>
  </div>
</div>


</main>
<?php include 'footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init();</script>
<script>
  function openModal(id, weight, phone, message) {
    document.getElementById('editId').value = id;
    document.getElementById('editWeight').value = weight;
    document.getElementById('editPhone').value = phone;
    document.getElementById('editMessage').value = message;
    document.getElementById('editModal').classList.remove('hidden');
  }
  function closeModal() {
    document.getElementById('editModal').classList.add('hidden');
  }
</script>
<script>
function openDeleteModal(id) {
  document.getElementById('deleteId').value = id;
  document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
  document.getElementById('deleteModal').classList.add('hidden');
}
</script>


</body>
</html>