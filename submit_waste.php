<?php
session_start();
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username']; // ดึง username จาก session

    $category_id = $_POST['category_id'];          // ✅ แก้ให้ตรง name
    $subcategory_id = $_POST['subcategory_id'];    // ✅ แก้ให้ตรง name
    $weight = $_POST['weight'];
    $phone = $_POST['phone'];
    $message = $_POST['message'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    // เตรียมชื่อไฟล์รูปภาพ
    $created_at = date('Y-m-d_H-i-s');
    $filename = $username . '_' . $created_at . '.png';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES['image']['tmp_name'];
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $destination = $uploadDir . $filename;

        if (!move_uploaded_file($tmpName, $destination)) {
            die("เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ");
        }
    } else {
        $filename = null;
    }

    $stmt = $pdo->prepare("
        INSERT INTO waste_sales 
        (user_id, category_id, subcategory_id, weight, phone, message, image, latitude, longitude) 
        VALUES 
        (:user_id, :category_id, :subcategory_id, :weight, :phone, :message, :image, :latitude, :longitude)
    ");
    $stmt->execute([
        'user_id' => $user_id,
        'category_id' => $category_id,
        'subcategory_id' => $subcategory_id,
        'weight' => $weight,
        'phone' => $phone,
        'message' => $message,
        'image' => $filename,
        'latitude' => $latitude,
        'longitude' => $longitude
    ]);

    header("Location: profile.php");
    exit();
}
