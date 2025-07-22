<?php
$current = basename($_SERVER['PHP_SELF']);
include 'components/header.php';
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";
include 'supabase.php';

// ✅ รับค่าพิกัดจาก GET ก่อน
$userLat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$userLng = isset($_GET['lng']) ? floatval($_GET['lng']) : null;

// ✅ ดึงข้อมูลร้านจาก Supabase
$response = $client->request('GET', 'shops', [
    'headers' => [
        'apikey' => $supabase_key,
        'Authorization' => 'Bearer ' . $supabase_key,
        'Accept' => 'application/json',
    ],
    'query' => [
        'select' => '*',
        'order' => 'created_at.desc'
    ]
]);

$data = json_decode($response->getBody(), true);

// ดึงหมวดหมู่จาก Supabase (เฉพาะที่ไม่ว่าง และไม่ซ้ำ)
$cat_url = "$supabase_url/rest/v1/shops?select=category";
$ch_cat = curl_init($cat_url);
curl_setopt($ch_cat, CURLOPT_HTTPHEADER, [
  "apikey: $supabase_key",
  "Authorization: Bearer $supabase_key"
]);
curl_setopt($ch_cat, CURLOPT_RETURNTRANSFER, true);
$cat_response = curl_exec($ch_cat);
curl_close($ch_cat);
$cat_raw = json_decode($cat_response, true);

// กรองหมวดหมู่ซ้ำและว่างออก
$categoryLabels = array_unique(array_filter(array_map(fn($i) => trim($i['category'] ?? ''), $cat_raw)));

// สร้างสีอัตโนมัติแบบสุ่ม (หรือกำหนด mapping เพิ่มก็ได้)
$colors = ['red', 'orange', 'yellow', 'green', 'purple', 'blue', 'teal', 'pink', 'indigo', 'cyan'];

shuffle($colors); // สลับสี

$categories = [];
$i = 0;
foreach ($categoryLabels as $label) {
  $color = $colors[$i % count($colors)];
  $categories[] = [
    'label' => $label,
    'bg' => "$color-100",
    'text' => "$color-600",
    'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16' // 👈 คุณจะใช้ไอคอนเดียวกันหมด หรือลิงก์ mapping เองก็ได้
  ];
  $i++;
}
?>

<script>
  // ตรวจว่า URL มีพิกัดหรือยัง
  function hasGeoParams() {
    const params = new URLSearchParams(window.location.search);
    return params.has("lat") && params.has("lng");
  }

  // ขอพิกัดแล้ว reload พร้อมแนบพิกัด
  function getLocationAndReload() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        const url = new URL(window.location.href);
        url.searchParams.set("lat", lat);
        url.searchParams.set("lng", lng);
        window.location.href = url.toString();
      }, function (error) {
        console.warn("⚠️ ไม่สามารถเข้าถึงตำแหน่ง:", error.message);
      });
    } else {
      alert("เบราว์เซอร์ของคุณไม่รองรับ Geolocation");
    }
  }

  // ถ้ายังไม่มี lat/lng → ขอแล้ว reload
  if (!hasGeoParams()) {
    getLocationAndReload();
  }
</script>


<main class="px-4 sm:px-6 md:px-10 py-6">

<!-- Banner Carousel -->
<div class="relative mt-8 max-w-7xl mx-auto overflow-hidden rounded-xl group" id="carousel">
  <!-- Slides wrapper -->
  <div id="slides" class="flex transition-transform duration-700 ease-in-out w-[300%]">
    <div class="w-full flex-shrink-0">
      <img src="assets/banner1.jpg" alt="แบนเนอร์ 1" class="w-full h-48 sm:h-64 md:h-80 lg:h-[450px] object-cover" />
    </div>
    <div class="w-full flex-shrink-0">
      <img src="assets/banner2.jpg" alt="แบนเนอร์ 2" class="w-full h-48 sm:h-64 md:h-80 lg:h-[450px] object-cover" />
    </div>
    <div class="w-full flex-shrink-0">
      <img src="assets/banner3.png" alt="แบนเนอร์ 3" class="w-full h-48 sm:h-64 md:h-80 lg:h-[450px] object-cover" />
    </div>
  </div>

  <!-- Dots -->
  <div class="absolute bottom-4 w-full flex justify-center gap-2 z-10">
    <button class="dot w-3 h-3 bg-white rounded-full opacity-50 hover:opacity-100 transition" data-index="0"></button>
    <button class="dot w-3 h-3 bg-white rounded-full opacity-50 hover:opacity-100 transition" data-index="1"></button>
    <button class="dot w-3 h-3 bg-white rounded-full opacity-50 hover:opacity-100 transition" data-index="2"></button>
  </div>

  <!-- Navigation arrows -->
  <button id="prev" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-80 p-2 rounded-full z-10 hidden group-hover:block">
    &#10094;
  </button>
  <button id="next" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-50 hover:bg-opacity-80 p-2 rounded-full z-10 hidden group-hover:block">
    &#10095;
  </button>
</div>


<!-- Script -->
<script>
  const slides = document.getElementById("slides");
  const dots = document.querySelectorAll(".dot");
  const carousel = document.getElementById("carousel");
  const prev = document.getElementById("prev");
  const next = document.getElementById("next");

  let current = 0;
  const total = dots.length;
  let interval;

  function updateSlide(index) {
    current = index;
    slides.style.transform = `translateX(-${100 * index}%)`;
    dots.forEach(dot => dot.classList.remove("opacity-100"));
    dots[index].classList.add("opacity-100");
  }

  function startAutoSlide() {
    interval = setInterval(() => {
      current = (current + 1) % total;
      updateSlide(current);
    }, 5000);
  }

  function stopAutoSlide() {
    clearInterval(interval);
  }

  // Event bindings
  dots.forEach(dot => {
    dot.addEventListener("click", () => {
      updateSlide(parseInt(dot.dataset.index));
    });
  });

  prev.addEventListener("click", () => {
    current = (current - 1 + total) % total;
    updateSlide(current);
  });

  next.addEventListener("click", () => {
    current = (current + 1) % total;
    updateSlide(current);
  });

  carousel.addEventListener("mouseenter", stopAutoSlide);
  carousel.addEventListener("mouseleave", startAutoSlide);

  // Init
  updateSlide(0);
  startAutoSlide();
</script>

<br>
<section class="text-center max-w-3xl mx-auto"><br>
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#f37021] mb-4">ค้นหาร้านอาหารที่คุณชอบได้เลย</h1>
    <p class="text-gray-600 mb-6">สนุกกับการค้าหาร้านอาหาร คาเฟ่ และอื่นๆตามไลฟ์ไตล์ของคุณ</p>
    <form method="GET" action="search.php" id="searchForm" class="flex flex-col md:flex-row gap-2 justify-center">
      <input type="text" name="q" placeholder="Search restaurants..." 
             class="px-4 py-2 border border-gray-300 rounded-md w-full md:w-1/2 focus:ring-2 focus:ring-[#f37021] focus:outline-none" />
      <button type="submit"
        class="bg-[#f37021] text-white px-6 py-2 rounded-md flex items-center gap-2 hover:bg-orange-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17.657 16.657L13.414 12.414A6 6 0 1112 13.414l4.243 4.243a1 1 0 001.414-1.414z" />
        </svg>
        <span>Search</span>
      </button>
    </form>
  </section>
<br>

  <!-- POPULAR CATEGORIES -->
  <section class="max-w-7xl mx-auto px-4">
  <h2 class="text-xl sm:text-2xl font-semibold mb-4">ประเภทร้านอาหาร</h2>
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach ($categories as $cat): ?>
      <a href="search.php?category=<?= urlencode($cat['label']) ?>"
         class="rounded-xl border p-6 flex flex-col items-center hover:shadow-md transition no-underline bg-white">
        <div class="bg-<?= $cat['bg'] ?> p-3 rounded-full">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-<?= $cat['text'] ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="<?= $cat['icon'] ?>" />
          </svg>
        </div>
        <p class="mt-2 font-semibold text-sm md:text-base text-center break-words text-<?= $cat['text'] ?>">
          <?= htmlspecialchars($cat['label']) ?>
        </p>
      </a>
    <?php endforeach; ?>
  </div>
</section>


<br>
  <!-- Google Ads #1 -->
  <div class="max-w-7xl mx-auto px-4">
    <div class="bg-gray-100 h-32 flex items-center justify-center text-gray-500">[ Google Ads Banner #1 ]</div>
  </div>
<br>
  <!-- Nearby Shops -->
<section class="max-w-7xl mx-auto px-4">
  <div class="flex justify-between items-center mb-4">
    <h2 class="text-xl sm:text-2xl font-semibold">ร้านใกล้คุณ</h2>
    <a href="nearby.php" class="text-sm text-[#f37021] hover:underline">ดูเพิ่มเติม</a>
  </div>
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <?php
    function haversineDistance($lat1, $lng1, $lat2, $lng2) {
      $earthRadius = 6371000; // meters
      $dLat = deg2rad($lat2 - $lat1);
      $dLng = deg2rad($lng2 - $lng1);
      $a = sin($dLat / 2) * sin($dLat / 2) +
           cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
           sin($dLng / 2) * sin($dLng / 2);
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
      return $earthRadius * $c;
    }

    $hasNearby = false;

    if (!is_null($userLat) && !is_null($userLng) && !empty($data)) {
  foreach ($data as $shop) {
    if (isset($shop['latitude']) && isset($shop['longitude'])) {
      $distance = haversineDistance($userLat, $userLng, $shop['latitude'], $shop['longitude']);
      if ($distance <= 5000) {
        $hasNearby = true;
        ?>
        <div class="rounded-xl overflow-hidden border hover:shadow-lg transition transform hover:-translate-y-1">
            <img src="<?= $shop['image_url'] ?: 'assets/restaurant1.jpg' ?>" class="w-full h-48 object-cover" alt="<?= htmlspecialchars($shop['name']) ?>">
            <div class="p-4">
              <h3 class="font-semibold text-lg line-clamp-1"><?= htmlspecialchars($shop['name']) ?></h3>
              <span class="inline-block bg-red-100 text-red-600 text-sm px-2 py-1 mt-1 rounded-md"><?= htmlspecialchars($shop['category']) ?></span>
              <?php if (!empty($shop['price'])): ?>
              <div class="mt-2 text-sm text-gray-700 font-medium flex items-baseline gap-1">
                <span>ราคาเริ่มต้น</span>
                <span class="text-xl font-bold text-[#f37021]"><?= htmlspecialchars($shop['price']) ?></span>
                <span>บาท</span>
              </div>
              <?php endif; ?>
              <div class="flex justify-between items-center mt-4">
                <span class="text-yellow-500">⭐ 4.5</span>
                <a href="shop.php?id=<?= $shop['id'] ?>" class="bg-[#f37021] text-white px-3 py-1 rounded-md hover:bg-orange-600">View Details</a>
              </div>
            </div>
          </div>
        <?php
      }
    }
  }
}


    if (!$hasNearby) {
      echo '<p class="text-gray-500 col-span-4 text-center">⚠️ ไม่สามารถระบุตำแหน่งของคุณได้ หรือไม่พบร้านในรัศมี 1 กิโลเมตร</p>';
    }
    ?>
  </div>
</section>
<br>

  <!-- Google Ads #2 -->
  <div class="max-w-7xl mx-auto px-4">
    <div class="bg-gray-100 h-32 flex items-center justify-center text-gray-500">[ Google Ads Banner #2 ]</div>
  </div>


  <!-- FEATURED RESTAURANTS -->
<section class="mt-12">
  <h2 class="text-xl max-w-7xl mx-auto sm:text-2xl font-semibold mb-4 px-4 sm:px-6">ร้านอาหารทั้งหมด</h2>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 grid grid-cols-2 sm:grid-cols-4 gap-4">
    <?php if (!empty($data)): ?>
      <?php foreach ($data as $shop): ?>
        <div class="rounded-xl overflow-hidden border hover:shadow-lg transition transform hover:-translate-y-1">
          <img src="<?= $shop['image_url'] ?: 'assets/restaurant1.jpg' ?>" class="w-full h-48 object-cover" alt="<?= htmlspecialchars($shop['name']) ?>">
          <div class="p-4">
            <h3 class="font-semibold text-lg line-clamp-1"><?= htmlspecialchars($shop['name']) ?></h3>
            <span class="inline-block bg-red-100 text-red-600 text-sm px-2 py-1 mt-1 rounded-md"><?= htmlspecialchars($shop['category']) ?></span>
            <?php if (!empty($shop['price'])): ?>
            <div class="mt-2 text-sm text-gray-700 font-medium flex items-baseline gap-1">
              <span>ราคาเริ่มต้น</span>
              <span class="text-xl font-bold text-[#f37021]"><?= htmlspecialchars($shop['price']) ?></span>
              <span>บาท</span>
            </div>
            <?php endif; ?>
            <div class="flex justify-between items-center mt-4">
              <span class="text-yellow-500">⭐ 4.5</span>
              <a href="shop.php?id=<?= $shop['id'] ?>" class="bg-[#f37021] text-white px-3 py-1 rounded-md hover:bg-orange-600">View Details</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="text-center text-gray-500 col-span-3">ไม่พบร้านอาหาร</p>
    <?php endif; ?>
  </div>
</section>

</main>

<?php include 'components/footer.php'; ?>
