<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once "db.php";

// ดึงรายการ waste_sales ที่ pending พร้อมตำแหน่ง
$stmt = $pdo->query("SELECT s.id, u.username, c.name AS category, s.weight, s.latitude, s.longitude
    FROM waste_sales s
    JOIN users u ON s.user_id = u.id
    JOIN waste_categories c ON s.category_id = c.id
    WHERE s.status = 'pending'");
$wastes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การเก็บขยะ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        #map { height: 500px; }
    </style>
</head>
<body class="bg-gradient-to-br from-lime-50 to-lime-100 min-h-screen text-gray-800 font-sans">
<?php include 'navbar_admin.php'; ?>

<div class="md:ml-64 p-4 sm:p-6  mx-auto mt-16 md:mt-0">
    <h1 class="text-3xl font-bold text-green-800 mb-4">🚚 การเก็บขยะ</h1>
    <form id="routeForm">
        <table class="w-full text-sm text-left bg-white shadow rounded-lg overflow-hidden">
            <thead class="bg-green-100 text-green-800">
                <tr>
                    <th class="p-3">เลือก</th>
                    <th class="p-3">ผู้ขาย</th>
                    <th class="p-3">ประเภท</th>
                    <th class="p-3">น้ำหนัก (กก.)</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($wastes as $w): ?>
                <tr class="border-b">
                    <td class="p-3">
                        <input type="checkbox" name="points[]" value="<?= $w['longitude'] ?>,<?= $w['latitude'] ?>" class="point">
                    </td>
                    <td class="p-3"><?= htmlspecialchars($w['username']) ?></td>
                    <td class="p-3"><?= htmlspecialchars($w['category']) ?></td>
                    <td class="p-3"><?= $w['weight'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="mt-4 bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow">คำนวณเส้นทาง</button>
    </form>

    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-2">แผนที่เส้นทาง</h2>
        <div id="map" class="rounded-xl border border-green-200 mb-4"></div>
        <div id="mapLinkContainer"></div>
    </div>
</div>

<script>
    const form = document.getElementById("routeForm");
    const map = L.map('map').setView([13.7563, 100.5018], 11);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let currentRouteLayer = null;

    form.addEventListener("submit", function(e) {
        e.preventDefault();
        const checked = document.querySelectorAll(".point:checked");
        if (checked.length < 1) return alert("เลือกอย่างน้อย 1 จุดเพื่อคำนวณเส้นทาง");

        navigator.geolocation.getCurrentPosition(async function(position) {
            const origin = `${position.coords.longitude},${position.coords.latitude}`;
            const coordArray = [origin, ...Array.from(checked).map(el => el.value)];
            const coords = coordArray.join(';');

            const res = await fetch(`https://router.project-osrm.org/trip/v1/driving/${coords}?source=first&roundtrip=false&geometries=geojson`);
            const json = await res.json();

            if (!json.trips) return alert("ไม่สามารถคำนวณเส้นทางได้");

            const route = json.trips[0];
            const geo = route.geometry;

            if (currentRouteLayer) map.removeLayer(currentRouteLayer);
            currentRouteLayer = L.geoJSON(geo, { color: "green" }).addTo(map);
            map.fitBounds(currentRouteLayer.getBounds());

            // สร้างลิงก์เปิดใน Google Maps
            const points = route.waypoints.map(wp => wp.location.reverse().join(","));
            const gmapURL = `https://www.google.com/maps/dir/${points.join("/")}`;
            document.getElementById("mapLinkContainer").innerHTML = `
                <a href="${gmapURL}" target="_blank" class="inline-block mt-4 text-green-700 underline">🌐 เปิดเส้นทางใน Google Maps</a>
            `;
        }, function(error) {
            alert("ไม่สามารถดึงตำแหน่งของคุณได้: " + error.message);
        });
    });
</script>
</body>
</html>