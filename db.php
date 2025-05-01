<?php
// ตั้งค่าการเชื่อมต่อฐานข้อมูล
$host = 'localhost';      // หรือ 127.0.0.1
$db   = 'waste_management';  // ชื่อฐานข้อมูลของคุณ
$user = 'root';           // ชื่อผู้ใช้ MySQL
$pass = '';               // รหัสผ่าน (ใส่ถ้ามี)
$charset = 'utf8mb4';

// ตั้งค่าการเชื่อมต่อแบบ PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // แสดง error แบบ exception
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // คืนค่าแบบ associative array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // ปิด emulate เพื่อความปลอดภัย
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options); // สร้างการเชื่อมต่อ
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage()); // แสดง error ถ้าเชื่อมต่อไม่สำเร็จ
}
?>
