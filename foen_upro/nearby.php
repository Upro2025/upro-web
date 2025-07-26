<?php
include 'components/header.php';
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";
include 'supabase.php';

// ฟังก์ชันคำนวณระยะทาง
function haversine($lat1, $lon1, $lat2, $lon2) {
    $R = 6371000; // รัศมีของโลกในเมตร
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c; // ผลลัพธ์เป็นเมตร
}

// รับข้อมูลพิกัดผู้ใช้จาก URL
$userLat = $_GET['lat'] ?? null; // ใช้ null ถ้าไม่มีค่าจาก URL
$userLng = $_GET['lng'] ?? null; // ใช้ null ถ้าไม่มีค่าจาก URL
$query = $_GET['q'] ?? ''; // คำค้นหาจากผู้ใช้
$category = $_GET['category'] ?? ''; // หมวดหมู่ที่เลือก
$filter = $_GET['filter'] ?? ''; // ฟิลเตอร์ (ใกล้เคียงที่สุด, ล่าสุด)

// หากยังไม่ได้รับพิกัดจาก URL ให้เรียก JS ขอพิกัดจากผู้ใช้
if (!$userLat || !$userLng) {
    echo "<script>
        navigator.geolocation.getCurrentPosition(function(pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            const params = new URLSearchParams(window.location.search);
            params.set('lat', lat);
            params.set('lng', lng);
            window.location.search = params.toString();
        });
    </script>";
}

// ดึงข้อมูลร้านจาก Supabase
$shops_url = "$supabase_url/rest/v1/shops?select=*&apikey=$supabase_key";
$response = file_get_contents($shops_url);

// ตรวจสอบการเชื่อมต่อ
if (!$response) {
    echo 'ไม่สามารถเชื่อมต่อกับ Supabase หรือไม่ได้รับข้อมูล.';
    exit;
}

// แปลง JSON เป็นอาเรย์
$shops = json_decode($response, true);

// ตรวจสอบว่าผลลัพธ์เป็นอาเรย์หรือไม่
if ($shops === null || !is_array($shops)) {
    echo 'ไม่สามารถแปลงข้อมูลเป็นอาเรย์ได้';
    exit;
}

// คัดกรองข้อมูลร้าน
$filtered = [];
if ($userLat && $userLng) {
    foreach ($shops as $shop) {
        // กรองตามคำค้นหาที่ผู้ใช้พิมพ์
        $matchKeyword = !$query ||
            stripos($shop['name'], $query) !== false ||
            stripos($shop['category'], $query) !== false;

        // กรองตามระยะทาง (5000 เมตร)
        $matchDistance = true;
        if (isset($shop['latitude'], $shop['longitude']) &&
            is_numeric($shop['latitude']) && is_numeric($shop['longitude'])) {
            $distance = haversine($userLat, $userLng, $shop['latitude'], $shop['longitude']);
            $matchDistance = $distance <= 5000; // กรองร้านที่อยู่ในระยะ 5000 เมตร
        }

        // กรองตามหมวดหมู่ที่เลือก
        $matchCategory = !$category || stripos($shop['category'], $category) !== false;

        // กรองตามฟิลเตอร์ที่เลือก (ใกล้เคียงที่สุด หรือ ล่าสุด)
        $matchFilter = true;
        if ($filter === 'closest' && $matchDistance) {
            $matchFilter = true; // กรองร้านที่อยู่ใกล้ที่สุด
        } elseif ($filter === 'latest') {
            $matchFilter = true; // กรองร้านล่าสุด (ตรงนี้อาจต้องเพิ่มเงื่อนไขใหม่หากต้องการเรียงตามวันที่)
        }

        // เพิ่มร้านที่ตรงกับเงื่อนไขทั้งหมด
        if ($matchKeyword && $matchDistance && $matchCategory && $matchFilter) {
            $filtered[] = $shop;
        }
    }
}

$data = $filtered; // ผลลัพธ์ที่กรองตามเงื่อนไข

// ฟังก์ชันแสดงผลคำยอดนิยม
$log_path = __DIR__ . '/search_log.json';
$log = file_exists($log_path) ? json_decode(file_get_contents($log_path), true) : [];
if ($query !== '') {
    $log[$query] = ($log[$query] ?? 0) + 1;
    file_put_contents($log_path, json_encode($log, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
arsort($log);
$topKeywords = array_keys(array_slice($log, 0, 5, true));

// ฟังก์ชันแสดงผล
// ฟังก์ชันแสดงผล
// ดึงข้อมูลร้านจาก Supabase
$cat_url = "$supabase_url/rest/v1/shops?select=category&order=category.asc";
$ch_cat = curl_init($cat_url);
curl_setopt($ch_cat, CURLOPT_HTTPHEADER, [
  "apikey: $supabase_key",
  "Authorization: Bearer $supabase_key"
]);
curl_setopt($ch_cat, CURLOPT_RETURNTRANSFER, true);
$cat_response = curl_exec($ch_cat);
curl_close($ch_cat);
$cat_raw = json_decode($cat_response, true);

// กำหนดค่าของ $categories
$categories = array_unique(array_filter(array_map(fn($i) => trim($i['category'] ?? ''), $cat_raw ?? [])));

// ตรวจสอบว่า $categories เป็นอาเรย์หรือไม่
if (!is_array($categories)) {
    $categories = [];
}

// คัดกรองข้อมูลร้าน
$filtered = [];
if ($userLat && $userLng) {
    foreach ($shops as $shop) {
        // 1. กรองตามคำค้นหา
        $matchKeyword = !$query ||
            stripos($shop['name'], $query) !== false ||
            stripos($shop['category'], $query) !== false;

        // 2. กรองตามหมวดหมู่ที่เลือก
        $matchCategory = !$category || stripos($shop['category'], $category) !== false;

        // 3. คำนวณระยะทาง
        $distance = null;
        $distance_text = '';
        $matchDistance = false;
        if (isset($shop['latitude'], $shop['longitude']) &&
            is_numeric($shop['latitude']) && is_numeric($shop['longitude'])) {
            $distance = haversine($userLat, $userLng, $shop['latitude'], $shop['longitude']);
            $matchDistance = $distance <= 5000;
            $distance_text = ($distance < 1000) ? 
                round($distance, 1) . ' เมตร' :
                round($distance / 1000, 2) . ' กิโลเมตร';
        }

        // 4. กรองตามฟิลเตอร์
        $matchFilter = true;
        if ($filter === 'closest') {
            $matchFilter = $matchDistance;
        } elseif ($filter === 'latest') {
            $matchFilter = true; // คุณอาจจะจัดเรียงตามเวลาทีหลัง
        }

        // 5. เพิ่มร้านที่ผ่านทุกเงื่อนไข
        if ($matchKeyword && $matchCategory && $matchDistance && $matchFilter) {
            $shop['distance'] = $distance_text; // เพิ่มระยะทางที่แสดงเป็นข้อความ
            $filtered[] = $shop;
        }
    }
}
$data = $filtered; // ผลลัพธ์ที่กรองตามเงื่อนไข

?>

<main class="px-4 sm:px-6 md:px-10 py-6">

  <section class="text-center max-w-3xl mx-auto mt-8 px-4">
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#f37021] mb-4">ร้านอาหารใกล้ฉัน</h1>
    <p class="text-gray-600 mb-6">ร้านอาหร ค่าเฟ และอื่นๆไม่เกิน 5กิโลเมตร</p>
  <form method="GET" action="" class="flex flex-col gap-2 justify-center mb-6">
    <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="ค้นหาร้าน..."
           class="px-4 py-2 border border-gray-300 rounded-md w-full focus:ring-2 focus:ring-[#f37021]" />
    <?php if (count($topKeywords)): ?>
      <div class="mt-4 text-sm text-gray-600">
        <div class="flex items-center gap-2 flex-wrap">
          <span class="font-medium whitespace-nowrap">คำยอดนิยม:</span>
          <?php foreach ($topKeywords as $k): ?>
            <a href="?q=<?= urlencode($k) ?>"
               class="px-4 py-2 rounded-full border border-[#f37021] text-[#f37021] bg-white hover:bg-[#f37021] hover:text-white transition whitespace-nowrap">
              <?= htmlspecialchars($k) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <button type="submit" class="bg-[#f37021] text-white px-6 py-2 rounded-md hover:bg-orange-600 transition">ค้นหา</button>

    <div class="flex flex-wrap gap-2 justify-center mt-4">
      <!-- ปุ่ม "ใกล้เคียงที่สุด" จะเป็นสีส้มเมื่อยังไม่ได้เลือกฟิลเตอร์ -->
      <button type="submit" name="filter" value="closest"
        class="px-4 py-2 rounded-full border transition 
        <?= empty($filter) || $filter === 'closest' ? 'bg-[#f37021] text-white border-[#f37021]' : 'bg-white text-[#f37021] border-[#f37021] hover:bg-[#f37021] hover:text-white' ?>">
        ใกล้เคียงที่สุด
      </button>
      
      <!-- ปุ่ม "ล่าสุด" -->
      <button type="submit" name="filter" value="latest"
        class="px-4 py-2 rounded-full border transition
        <?= $filter === 'latest' ? 'bg-[#f37021] text-white border-[#f37021]' : 'bg-white text-[#f37021] border-[#f37021] hover:bg-[#f37021] hover:text-white' ?>">
        ล่าสุด
      </button>

      <!-- แสดงปุ่มตามหมวดหมู่ -->
      <?php foreach ($categories as $cat): ?>
        <button type="submit" name="category" value="<?= htmlspecialchars($cat) ?>"
          class="px-4 py-2 rounded-full border transition
          <?= $category === $cat ? 'bg-[#f37021] text-white border-[#f37021]' : 'bg-white text-[#f37021] border-[#f37021] hover:bg-[#f37021] hover:text-white' ?>">
          <?= htmlspecialchars($cat) ?>
        </button>
      <?php endforeach; ?>
    </div>
  </form>
</section>

<br>
  <!-- Google Ads #1 -->
  <div class="max-w-7xl mx-auto px-4">
    <div class="bg-gray-100 h-32 flex items-center justify-center text-gray-500">[ Google Ads Banner #1 ]</div>
  </div>
<br>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 grid grid-cols-2 sm:grid-cols-4 gap-4">
    <?php if (!empty($data)): ?>
        <?php foreach ($data as $shop): ?>
            <div class="rounded-xl border hover:shadow-md">
                <img src="<?= $shop['image_url'] ?: 'assets/restaurant1.jpg' ?>" class="w-full h-48 object-cover" />
                <div class="p-4">
                    <h3 class="font-semibold text-lg line-clamp-1"><?= htmlspecialchars($shop['name']) ?></h3>
                    <span class="inline-block bg-red-100 text-red-600 text-sm px-2 py-1 mt-1 rounded-md">
                        <?= htmlspecialchars($shop['category']) ?>
                    </span>
                    <div class="mt-2 text-sm text-gray-700 font-medium flex items-baseline gap-1">
                        <span>ราคาเริ่มต้น</span>
                        <span class="text-xl font-bold text-[#f37021]"><?= htmlspecialchars($shop['price']) ?></span>
                        <span>บาท</span>
                    </div>

                    <!-- แสดงระยะทางที่นี่ -->
                    <p class="text-sm text-gray-600 mt-2">ระยะทาง: <?= htmlspecialchars($shop['distance']) ?></p>

                    <p class="text-xs text-gray-400 mt-1">เปิด: <?= htmlspecialchars($shop['time_open']) ?> - <?= htmlspecialchars($shop['time_close']) ?></p>

                    <div class="flex justify-between items-center mt-4">
                        <span class="text-yellow-500">⭐ 4.5</span>
                        <a href="shop.php?id=<?= $shop['id'] ?>" class="bg-[#f37021] text-white px-3 py-1 rounded-md">ดูเพิ่มเติม</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center text-gray-500 col-span-full">ไม่พบร้านใกล้คุณ</p>
    <?php endif; ?>
</div>

<br>
  <!-- Google Ads #1 -->
  <div class="max-w-7xl mx-auto px-4">
    <div class="bg-gray-100 h-32 flex items-center justify-center text-gray-500">[ Google Ads Banner #1 ]</div>
  </div>
<br>

</main>

<?php include 'components/footer.php'; ?>
