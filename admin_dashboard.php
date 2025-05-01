<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once "db.php";

// ฟังก์ชันดึงข้อมูลขยะแบบรวม
function getWasteSummary($pdo, $type = 'daily') {
    $interval = $type === 'monthly' ? '1 MONTH' : '1 DAY';

    $stmt = $pdo->prepare("
        SELECT c.name AS category_name,
            SUM(CASE WHEN s.status = 'pending' THEN s.weight ELSE 0 END) AS pending,
            SUM(CASE WHEN s.status = 'completed' THEN s.weight ELSE 0 END) AS completed
        FROM waste_sales s
        JOIN waste_categories c ON s.category_id = c.id
        WHERE s.created_at >= NOW() - INTERVAL $interval
        GROUP BY c.id
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll();

    $data = [];
    foreach ($rows as $row) {
        $data[$row['category_name']] = [
            'pending' => (float)$row['pending'],
            'complete' => (float)$row['completed'],
        ];
    }
    return $data;
}


// เรียกใช้ข้อมูลครั้งแรกแบบรายวัน
$initialData = getWasteSummary($pdo, 'daily');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>แดชบอร์ดผู้ดูแล</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
  <style>
    body {
      font-family: 'Prompt', sans-serif;
      background: #e9fbe5;
    }
  </style>
</head>
<!-- <body class="bg-gradient-to-br from-indigo-100 to-purple-200 min-h-screen font-sans"> -->

<!-- BODY -->
<body class="bg-gradient-to-br from-lime-50 to-lime-100 min-h-screen text-gray-800 font-sans">

<?php include 'navbar_admin.php'; ?>

<!-- MAIN -->
<div class="md:ml-64 p-4 sm:p-6  mx-auto mt-16 md:mt-0">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mb-6">
        <h2 class="text-2xl sm:text-3xl font-bold text-green-800">แดชบอร์ด</h2>
        <div class="flex gap-2 items-center">
            <input type="date" id="start_date" class="border px-3 py-2 rounded-md shadow-sm focus:ring-green-500 focus:outline-none">
                <span class="text-gray-500">ถึง</span>
                <input type="date" id="end_date" class="border px-3 py-2 rounded-md shadow-sm focus:ring-green-500 focus:outline-none">
                <button id="filterBtn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">กรอง</button>
            <button id="showAllBtn" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">แสดงทั้งหมด</button>

        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <div class="bg-white shadow rounded-xl p-4 sm:p-6 text-center border border-lime-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-600">รวมทั้งหมด</h3>
            <p class="text-2xl sm:text-3xl font-bold text-purple-700" id="totalWaste">0 กก.</p>
        </div>
        <div class="bg-white shadow rounded-xl p-4 sm:p-6 text-center border border-yellow-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-600">Pending</h3>
            <p class="text-2xl sm:text-3xl font-bold text-yellow-500" id="pendingWaste">0 กก.</p>
        </div>
        <div class="bg-white shadow rounded-xl p-4 sm:p-6 text-center border border-green-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-600">Complete</h3>
            <p class="text-2xl sm:text-3xl font-bold text-green-600" id="completeWaste">0 กก.</p>
        </div>
        <div class="bg-white shadow rounded-xl p-4 sm:p-6 text-center border border-indigo-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-600">รายการทั้งหมด</h3>
            <p class="text-2xl sm:text-3xl font-bold text-indigo-500" id="totalOrders">0</p>
        </div>
    </div>

    <!-- Chart -->
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow border border-green-100 overflow-x-auto">
        <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-4">กราฟแสดงจำนวนขยะแต่ละประเภท</h3>
        <canvas id="wasteChart" height="400" class="w-full max-w-full"></canvas>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init();</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.getElementById('showAllBtn').addEventListener('click', async () => {
        const res = await fetch('get_waste_data.php');
        const data = await res.json();
        updateChart(data);
    });

    let chart;
    const ctx = document.getElementById('wasteChart').getContext('2d');

    function updateChart(data) {
        const labels = Object.keys(data);
        const pendingData = labels.map(label => data[label].pending);
        const completeData = labels.map(label => data[label].complete);

        if (chart) {
            chart.data.labels = labels;
            chart.data.datasets[0].data = pendingData;
            chart.data.datasets[1].data = completeData;
            chart.update();
        } else {
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pending',
                            data: pendingData,
                            backgroundColor: 'rgba(251, 191, 36, 0.7)',
                        },
                        {
                            label: 'Complete',
                            data: completeData,
                            backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'จำนวน (กิโลกรัม)' }
                        }
                    }
                }
            });
        }

        const totalPending = pendingData.reduce((a, b) => a + b, 0);
        const totalComplete = completeData.reduce((a, b) => a + b, 0);
        document.getElementById('pendingWaste').textContent = totalPending.toFixed(2) + ' กก.';
        document.getElementById('completeWaste').textContent = totalComplete.toFixed(2) + ' กก.';
        document.getElementById('totalWaste').textContent = (totalPending + totalComplete).toFixed(2) + ' กก.';
        document.getElementById('totalOrders').textContent = labels.length;
    }

    document.getElementById('filterBtn').addEventListener('click', async () => {
        const start = document.getElementById('start_date').value;
        const end = document.getElementById('end_date').value;

        if (!start || !end) {
            alert("กรุณาเลือกวันที่เริ่มต้นและสิ้นสุด");
            return;
        }

        const res = await fetch(`get_waste_data.php?start_date=${start}&end_date=${end}`);
        const data = await res.json();
        updateChart(data);
    });

</script>

</body>
</html>
