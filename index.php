<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Waste Separation Info</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
  <style>
    body {
      font-family: 'Noto Sans Thai', sans-serif;
    }
  </style>
</head>
<body class="min-h-screen flex flex-col bg-green-50 text-gray-800">
<!-- Hero Section -->
<section class="text-center py-20 bg-gradient-to-r from-green-200 to-green-100" data-aos="fade-up">
  <h1 class="text-4xl font-bold mb-4">เริ่มแยกขยะเพื่อโลกที่สะอาดขึ้น</h1>
  <p class="text-lg max-w-2xl mx-auto">การแยกขยะไม่ใช่เรื่องยาก และสามารถเริ่มได้ที่บ้านคุณวันนี้ มาดูวิธีแยกขยะที่ถูกต้องกันเถอะ!</p>
</section>

<!-- Content Section -->
<section id="about" class="py-16 px-6 md:px-20 bg-white" data-aos="fade-up">
  <h2 class="text-2xl font-bold mb-6">ประเภทของขยะหลัก</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-green-100 p-6 rounded-xl shadow hover:shadow-md transition" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">ขยะเปียก</h3>
      <p>เศษอาหาร เศษผัก ผลไม้ ย่อยสลายง่าย</p>
    </div>
    <div class="bg-blue-100 p-6 rounded-xl shadow hover:shadow-md transition" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">ขยะรีไซเคิล</h3>
      <p>ขวดพลาสติก กระป๋อง แก้ว กระดาษ</p>
    </div>
    <div class="bg-yellow-100 p-6 rounded-xl shadow hover:shadow-md transition" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">ขยะทั่วไป</h3>
      <p>พลาสติกห่อขนม ซองขนม ถุงพลาสติก</p>
    </div>
    <div class="bg-red-100 p-6 rounded-xl shadow hover:shadow-md transition" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">ขยะอันตราย</h3>
      <p>หลอดไฟ ถ่านไฟฉาย กระป๋องสเปรย์</p>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="text-center py-16 bg-green-200" data-aos="fade-up">
  <h2 class="text-2xl font-bold mb-4">พร้อมจะเริ่มขายขยะหรือยัง?</h2>
  <a href="sell.php" class="inline-block bg-green-700 text-white px-6 py-3 rounded-xl hover:bg-green-800 transition">ไปที่หน้าขายขยะ</a>
</section>

<?php include 'footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>AOS.init();</script>
</body>
</html>