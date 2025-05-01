<?php
session_start();

// ล้างข้อมูล session
session_unset();
session_destroy();

// ส่งกลับไปยังหน้า login
header("Location: index.php");
exit;
