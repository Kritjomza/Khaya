<?php
require_once "db.php";

header('Content-Type: application/json');

$start = isset($_GET['start_date']) ? $_GET['start_date'] : null;
$end = isset($_GET['end_date']) ? $_GET['end_date'] : null;

if ($start && $end) {
    // กรองตามวันที่
    $stmt = $pdo->prepare("
        SELECT c.name AS category_name,
            SUM(CASE WHEN s.status = 'pending' THEN s.weight ELSE 0 END) AS pending,
            SUM(CASE WHEN s.status = 'completed' THEN s.weight ELSE 0 END) AS completed
        FROM waste_sales s
        JOIN waste_categories c ON s.category_id = c.id
        WHERE DATE(s.created_at) BETWEEN :start AND :end
        GROUP BY c.id
    ");
    $stmt->execute(['start' => $start, 'end' => $end]);
} else {
    // แสดงทั้งหมด
    $stmt = $pdo->query("
        SELECT c.name AS category_name,
            SUM(CASE WHEN s.status = 'pending' THEN s.weight ELSE 0 END) AS pending,
            SUM(CASE WHEN s.status = 'completed' THEN s.weight ELSE 0 END) AS completed
        FROM waste_sales s
        JOIN waste_categories c ON s.category_id = c.id
        GROUP BY c.id
    ");
}

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$data = [];
foreach ($rows as $row) {
    $data[$row['category_name']] = [
        'pending' => (float)$row['pending'],
        'complete' => (float)$row['completed'],
    ];
}

echo json_encode($data);
