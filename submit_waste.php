<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$category_id = $_POST['category_id'];
$subcategory_id = $_POST['subcategory_id'];
$weight = $_POST['weight'];
$phone = $_POST['phone'];
$message = $_POST['message'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

$imagePath = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageName = 'img_' . uniqid() . '.' . $ext;
    $imagePath = 'uploads/' . $imageName;
    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/uploads/' . $imageName);
}

$sql = "INSERT INTO waste_sales (user_id, category_id, subcategory_id, weight, phone, message, image, latitude, longitude)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $user_id,
    $category_id,
    $subcategory_id,
    $weight,
    $phone,
    $message,
    $imagePath,
    $latitude,
    $longitude
]);

header('Location: history.php');
exit();
?>
