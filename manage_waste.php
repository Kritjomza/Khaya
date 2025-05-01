<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
require_once 'db.php';

// ดึงประเภทขยะ
$categories = $pdo->query("SELECT * FROM waste_categories")->fetchAll();
$subcategories = $pdo->query("SELECT * FROM waste_subcategories")->fetchAll();

// รับค่าจาก dropdown
$filter_category = $_GET['category'] ?? '';
$filter_sub = $_GET['subcategory'] ?? '';

// Query ข้อมูล waste_sales พร้อม join ตารางอื่น
$sql = "
    SELECT s.*, u.username, c.name AS category_name, sc.name AS subcategory_name
    FROM waste_sales s
    JOIN users u ON s.user_id = u.id
    JOIN waste_categories c ON s.category_id = c.id
    JOIN waste_subcategories sc ON s.subcategory_id = sc.id
    WHERE 1=1
";

$params = [];

if ($filter_category !== '') {
    $sql .= " AND s.category_id = ?";
    $params[] = $filter_category;
}
if ($filter_sub !== '') {
    $sql .= " AND s.subcategory_id = ?";
    $params[] = $filter_sub;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$wastes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการขยะ - Waste Bank</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-lime-50 text-gray-800 font-sans">
<?php include 'navbar_admin.php'; ?>

<div class="md:ml-64 p-4 sm:p-6  mx-auto mt-16 md:mt-0">

    <h2 class="text-2xl sm:text-3xl font-bold text-green-800 mb-6">จัดการข้อมูลขยะ</h2>

    <!-- Dropdown Filters -->
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <select name="category" class="w-full p-3 border border-green-300 rounded shadow-sm">
            <option value="">-- เลือกประเภทขยะ --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $filter_category ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="subcategory" class="w-full p-3 border border-green-300 rounded shadow-sm">
            <option value="">-- เลือกประเภทย่อย --</option>
            <?php foreach ($subcategories as $sub): ?>
                <?php if (!$filter_category || $sub['category_id'] == $filter_category): ?>
                    <option value="<?= $sub['id'] ?>" <?= $sub['id'] == $filter_sub ? 'selected' : '' ?>>
                        <?= htmlspecialchars($sub['name']) ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>

        <div class="col-span-1 sm:col-span-2 text-right">
            <button type="submit" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded shadow">
                🔍 กรองข้อมูล
            </button>
        </div>
    </form>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="min-w-max bg-white border rounded-xl shadow text-sm sm:text-base">
            <thead class="bg-green-200 text-left">
                <tr>
                    <th class="p-3 whitespace-nowrap">ผู้ส่ง</th>
                    <th class="p-3 whitespace-nowrap">ประเภท</th>
                    <th class="p-3 whitespace-nowrap">ประเภทย่อย</th>
                    <th class="p-3 whitespace-nowrap">น้ำหนัก</th>
                    <th class="p-3 whitespace-nowrap">เบอร์</th>
                    <th class="p-3 whitespace-nowrap">ข้อความ</th>
                    <th class="p-3 whitespace-nowrap">รูปภาพ</th>
                    <th class="p-3 whitespace-nowrap">ตำแหน่ง</th>
                    <th class="p-3 whitespace-nowrap">สถานะ</th>
                    <th class="p-3 whitespace-nowrap">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wastes as $waste): ?>
                    <tr class="border-t hover:bg-green-50">
                        <td class="p-3"><?= htmlspecialchars($waste['username']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($waste['category_name']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($waste['subcategory_name']) ?></td>
                        <td class="p-3"><?= $waste['weight'] ?> กก.</td>
                        <td class="p-3"><?= htmlspecialchars($waste['phone']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($waste['message']) ?></td>
                        <td class="p-3">
                            <?php if (!empty($waste['image'])): ?>
                                <img src="uploads/<?php echo htmlspecialchars($waste['image']); ?>" class="w-16 h-16 object-cover rounded-md" alt="waste image">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>

                        <td class="p-3">
                            <a href="https://www.google.com/maps?q=<?= $waste['latitude'] ?>,<?= $waste['longitude'] ?>"
                               target="_blank" class="text-blue-600 underline">แผนที่</a>
                        </td>
                        <td class="p-3">
                            <span class="inline-block px-3 py-1 rounded-full text-sm <?= $waste['status'] === 'completed' ? 'bg-green-300 text-green-800' : 'bg-yellow-200 text-yellow-700' ?>">
                                <?= $waste['status'] ?>
                            </span>
                        </td>
                        <td class="p-3">
                            <?php if ($waste['status'] === 'pending'): ?>
                                <form method="POST" action="mark_complete.php">
                                    <input type="hidden" name="waste_id" value="<?= $waste['id'] ?>">
                                    <button type="submit" class="text-sm bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded">
                                        ✅ Mark
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-400 text-sm">✓ เสร็จแล้ว</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (count($wastes) === 0): ?>
                    <tr><td colspan="10" class="text-center py-6 text-gray-500">ไม่มีข้อมูลที่ตรงกับเงื่อนไข</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

