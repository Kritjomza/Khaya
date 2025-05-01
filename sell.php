<?php
require_once 'db.php';
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM waste_categories")->fetchAll();
$subcategories_data = $pdo->query("SELECT id, category_id, name FROM waste_subcategories")->fetchAll();
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ขายขยะ</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body { font-family: 'Noto Sans Thai', sans-serif; }
    #map { height: 300px; }
  </style>
</head>
<body class="bg-gradient-to-br from-green-100 to-white min-h-screen">
<?php include 'navbar.php'; ?>

<div class="max-w-4xl mx-auto py-12 px-6" data-aos="fade-up">
  <div class="bg-white p-8 rounded-xl shadow-xl">
    <h2 class="text-2xl font-bold mb-6 text-green-700">แบบฟอร์มขายขยะ</h2>
    <form action="submit_waste.php" method="POST" enctype="multipart/form-data" id="sellForm" class="space-y-6">
      <div>
        <label class="block mb-1 font-semibold">ประเภทขยะหลัก</label>
        <select name="category_id" id="categorySelect" required class="w-full border rounded p-3 focus:ring-2 focus:ring-green-400">
          <option value="">-- เลือกประเภท --</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label class="block mb-1 font-semibold">ประเภทย่อย</label>
        <select name="subcategory_id" id="subcategorySelect" required disabled class="w-full border rounded p-3 focus:ring-2 focus:ring-green-400">
          <option value="">-- กรุณาเลือกประเภทหลักก่อน --</option>
        </select>
      </div>
      <div>
        <label class="block mb-1 font-semibold">น้ำหนัก (กิโลกรัม)</label>
        <input type="number" name="weight" step="0.01" min="0" required class="w-full border rounded p-3 focus:ring-2 focus:ring-green-400">
      </div>
      <div>
        <label class="block mb-1 font-semibold">เบอร์โทรติดต่อ</label>
        <input type="text" name="phone" required class="w-full border rounded p-3 focus:ring-2 focus:ring-green-400">
      </div>
      <div>
        <label class="block mb-1 font-semibold">ข้อความถึงผู้รับซื้อ</label>
        <textarea name="message" class="w-full border rounded p-3 focus:ring-2 focus:ring-green-400" rows="3"></textarea>
      </div>
      <div>
        <label class="block mb-1 font-semibold">แนบรูปภาพ</label>
        <input type="file" name="image" accept="image/*" class="w-full">
      </div>
      <div>
        <label class="block mb-1 font-semibold">ระบุตำแหน่งของคุณบนแผนที่</label>
        <div id="map" class="rounded shadow mb-2"></div>
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
        <input type="text" id="location" readonly class="w-full border rounded p-3 bg-gray-100">
      </div>
      <button type="submit" class="w-full bg-green-600 text-white py-3 rounded hover:bg-green-700 transition animate-pulse">ส่งข้อมูล</button>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>AOS.init();</script>
<script>
const subcategories = <?= json_encode($subcategories_data) ?>;
const categorySelect = document.getElementById('categorySelect');
const subcategorySelect = document.getElementById('subcategorySelect');

categorySelect.addEventListener('change', function () {
  const selectedCategoryId = this.value;
  subcategorySelect.innerHTML = '<option value="">-- เลือกประเภทย่อย --</option>';

  const filtered = subcategories.filter(sub => sub.category_id == selectedCategoryId);
  if (filtered.length > 0) {
    filtered.forEach(sub => {
      const option = document.createElement('option');
      option.value = sub.id;
      option.textContent = sub.name;
      subcategorySelect.appendChild(option);
    });
    subcategorySelect.disabled = false;
    subcategorySelect.classList.add('ring', 'ring-green-300', 'animate-pulse');
  } else {
    subcategorySelect.disabled = true;
    subcategorySelect.innerHTML = '<option value="">-- ไม่มีประเภทย่อย --</option>';
    subcategorySelect.classList.remove('ring', 'animate-pulse');
  }
});

// Leaflet map setup
if (navigator.geolocation) {
  navigator.geolocation.getCurrentPosition(function(position) {
    const userLat = position.coords.latitude;
    const userLng = position.coords.longitude;

    map = L.map('map').setView([userLat, userLng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    marker = L.marker([userLat, userLng], { draggable: true }).addTo(map);
    updateLatLng(userLat, userLng);

    marker.on('dragend', function(event) {
      const position = marker.getLatLng();
      updateLatLng(position.lat, position.lng);
    });

    map.on('click', function(event) {
      marker.setLatLng(event.latlng);
      updateLatLng(event.latlng.lat, event.latlng.lng);
    });

  }, function(error) {
    alert("❗ ไม่สามารถเข้าถึงตำแหน่งของคุณได้: " + error.message);
    fallbackMap(); // fallback แผนที่กรุงเทพฯ
  });
} else {
  alert("❗ เบราว์เซอร์ของคุณไม่รองรับการใช้ตำแหน่ง");
  fallbackMap();
}

// fallback หากผู้ใช้ไม่ให้ permission หรือ browser ไม่รองรับ
function fallbackMap() {
  map = L.map('map').setView([13.736717, 100.523186], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);
  marker = L.marker([13.736717, 100.523186], { draggable: true }).addTo(map);
  updateLatLng(13.736717, 100.523186);

  marker.on('dragend', function(event) {
    const position = marker.getLatLng();
    updateLatLng(position.lat, position.lng);
  });

  map.on('click', function(event) {
    marker.setLatLng(event.latlng);
    updateLatLng(event.latlng.lat, event.latlng.lng);
  });
}

function updateLatLng(lat, lng) {
  document.getElementById('latitude').value = lat;
  document.getElementById('longitude').value = lng;
  document.getElementById('location').value = `Lat: ${lat.toFixed(5)}, Lng: ${lng.toFixed(5)}`;
}
</script>
</body>
</html>