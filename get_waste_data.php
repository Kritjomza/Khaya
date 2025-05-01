<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

require_once 'db.php';

$start = $_GET['start_date'] ?? null;
$end = $_GET['end_date'] ?? null;

if (!$start || !$end) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing start_date or end_date']);
    exit();
}

try {
    $stmt = $pdo->prepare("
        SELECT c.name AS waste_type,
               s.status,
               SUM(s.weight) AS total_weight
        FROM waste_sales s
        JOIN waste_categories c ON s.category_id = c.id
        WHERE DATE(s.created_at) BETWEEN :start AND :end
        GROUP BY c.name, s.status
    ");
    $stmt->execute([
        ':start' => $start,
        ':end' => $end
    ]);

    $rows = $stmt->fetchAll();
    $data = [];

    foreach ($rows as $row) {
        $wasteType = $row['waste_type'];
        $statusKey = $row['status'] === 'completed' ? 'complete' : 'pending';

        if (!isset($data[$wasteType])) {
            $data[$wasteType] = ['pending' => 0, 'complete' => 0];
        }
        $data[$wasteType][$statusKey] = (float)$row['total_weight'];
    }

    echo json_encode($data);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error', 'details' => $e->getMessage()]);
}
