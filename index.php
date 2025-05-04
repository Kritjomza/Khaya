<?php include 'navbar.php'; ?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ธนาคารขยะ - แยกขยะสร้างคุณค่า</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  <style>
  body { font-family: 'Noto Sans Thai', sans-serif; }
  .swiper-slide img {
    object-fit: cover;
    width: 100%;
    height: 100%;
  }
  </style>
</head>
<body class="min-h-screen flex flex-col bg-green-50 text-gray-800">

<!-- Hero: Image Slider -->
<div class="relative bg-gradient-to-r from-green-200 to-green-100 py-4 sm:py-10">
  <div class="swiper mySwiper max-w-[100%] md:max-w-[1440px] aspect-[16/9] mx-auto shadow-xl rounded-xl overflow-hidden">
    <div class="swiper-wrapper">
      <div class="swiper-slide"><img src="assets/img/1.png" alt="slide1" class="w-full h-full object-cover"></div>
      <div class="swiper-slide"><img src="assets/img/2.png" alt="slide2" class="w-full h-full object-cover"></div>
      <div class="swiper-slide"><img src="assets/img/1.png" alt="slide3" class="w-full h-full object-cover"></div>
    </div>
    <!-- Navigation -->
    <div class="swiper-button-next text-white"></div>
    <div class="swiper-button-prev text-white"></div>
  </div>
  <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
    <h1 class="text-white text-2xl sm:text-4xl md:text-5xl font-bold drop-shadow-xl text-center px-4">
      เริ่มแยกขยะวันนี้ เพื่ออนาคตของเรา
    </h1>
  </div>
</div>


<!-- ข้อมูลประเภทขยะ -->
<section class="py-16 px-6 md:px-20 bg-white" data-aos="fade-up">
  <h2 class="text-3xl font-bold text-center mb-10 text-green-700">ประเภทของขยะที่ควรรู้</h2>
  <div class="flex flex-wrap justify-center gap-6">
    
    <!-- ขยะเปียก -->
    <div class="w-72 bg-lime-100 p-6 rounded-xl shadow hover:shadow-2xl transition duration-300 transform hover:scale-105" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">เศษเหล็กและโลหะ</h3>
      <p>เศษเหล็กทั่วไป อลูมิเนียมอื่น ๆ อลูมิเนียมอื่น ๆ</p>
    </div>

    <!-- ขวดพลาสติก -->
    <div class="w-72 bg-blue-100 p-6 rounded-xl shadow hover:shadow-2xl transition duration-300 transform hover:scale-105" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">ขวดพลาสติก</h3>
      <p>ขวดน้ำดื่ม PET, ขวดน้ำยาซักล้าง HDPE</p>
    </div>

    <!-- ถุงและพลาสติก -->
    <div class="w-72 bg-sky-100 p-6 rounded-xl shadow hover:shadow-2xl transition duration-300 transform hover:scale-105" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">ถุงและพลาสติก</h3>
      <p>ถุงหูหิ้ว ถุงขนม ฝาขวดพลาสติก</p>
    </div>

    <!-- กระป๋อง -->
    <div class="w-72 bg-yellow-100 p-6 rounded-xl shadow hover:shadow-2xl transition duration-300 transform hover:scale-105" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">กระป๋อง</h3>
      <p>กระป๋องน้ำอัดลม (อลูมิเนียม), กระป๋องอาหาร</p>
    </div>

    <!-- กระดาษ -->
    <div class="w-72 bg-stone-100 p-6 rounded-xl shadow hover:shadow-2xl transition duration-300 transform hover:scale-105" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">กระดาษ</h3>
      <p>กระดาษขาว กล่องลัง หนังสือพิมพ์เก่า</p>
    </div>

    <!-- ขวดแก้ว -->
    <div class="w-72 bg-indigo-100 p-6 rounded-xl shadow hover:shadow-2xl transition duration-300 transform hover:scale-105" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">ขวดแก้ว</h3>
      <p>ขวดใส ขวดเขียว ขวดน้ำปลา ขวดเบียร์</p>
    </div>

    <!-- ขยะอันตราย -->
    <div class="w-72 bg-red-100 p-6 rounded-xl shadow hover:shadow-2xl transition duration-300 transform hover:scale-105" data-aos="zoom-in">
      <h3 class="text-xl font-semibold mb-2">ขยะอันตราย</h3>
      <p>หลอดไฟ ถ่านไฟฉาย แบตเตอรี่ สเปรย์</p>
    </div>

  </div>
</section>


<!-- เกี่ยวกับเรา -->
<section class="py-20 px-6 md:px-40 bg-green-100 text-center" data-aos="fade-up">
  <h2 class="text-3xl font-bold text-green-800 mb-6">เกี่ยวกับธนาคารขยะ</h2>
  <p class="text-lg leading-relaxed">
    ระบบธนาคารขยะของเราถูกออกแบบมาเพื่อส่งเสริมการแยกขยะในชุมชน
    โดยผู้ใช้งานสามารถนำขยะที่คัดแยกแล้วมาขายและสะสมแต้มเพื่อแลกของรางวัล
    ทั้งนี้ยังช่วยลดปริมาณขยะในสิ่งแวดล้อมและสร้างรายได้ให้กับชุมชนอีกด้วย
  </p>
</section>

<!-- CTA -->
<section class="text-center py-16 bg-green-700 text-white" data-aos="fade-up">
  <h2 class="text-2xl font-bold mb-4">พร้อมจะเริ่มขายขยะหรือยัง?</h2>
  <a href="sell.php" class="inline-block bg-white text-green-700 font-semibold px-6 py-3 rounded-xl hover:bg-green-100 transition">ไปที่หน้าขายขยะ</a>
</section>

<?php include 'footer.php'; ?>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
  AOS.init();
  const swiper = new Swiper('.mySwiper', {
    loop: true,
    autoplay: { delay: 4000 },
    effect: 'fade',
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });
</script>
</body>
</html>