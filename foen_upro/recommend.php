<?php include 'components/header.php'; ?>

<?php
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";
include 'supabase.php';

$filtered = [];
$lat = $lng = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $price     = $_POST['price'] ?? '';
  $type      = $_POST['type'] ?? '';
  $eat_time  = $_POST['eat_time'] ?? '';
  $lat       = $_POST['lat'] ?? '';
  $lng       = $_POST['lng'] ?? '';
  $viewed_at = date('c');

  // 🔁 POST เข้า views
  $payload = [
    'price_rec' => floatval($price),
    'type_rec'  => $type,
    'eat_time'  => $eat_time,
    'lat_rec'   => floatval($lat),
    'lng_rec'   => floatval($lng),
    'viewed_at' => $viewed_at
  ];
  $options = [
    'http' => [
      'method'  => 'POST',
      'header'  => "Content-type: application/json\r\n"
                . "Authorization: Bearer $supabase_key\r\n"
                . "apikey: $supabase_key\r\n"
                . "Prefer: return=representation\r\n",
      'content' => json_encode($payload)
    ]
  ];
  $context = stream_context_create($options);
  $response = @file_get_contents("$supabase_url/rest/v1/views", false, $context);

  if ($response === FALSE) {
    echo "<p class='text-red-500 text-center'>⚠️ เกิดข้อผิดพลาดในการบันทึกข้อมูล</p>";
  }

  // ✅ ดึงข้อมูลร้าน
  $shops_url = "$supabase_url/rest/v1/shops?select=*&apikey=$supabase_key";
  $shops_json = @file_get_contents($shops_url);
  $shops = json_decode($shops_json, true) ?? [];

  function haversine($lat1, $lon1, $lat2, $lon2) {
    $R = 6371000;
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c;
  }

  function match_time($eat_time, $open, $close) {
    $ranges = [
      'breakfast' => ['05:00:00', '11:00:00'],
      'lunch' => ['11:00:00', '14:00:00'],
      'dinner' => ['16:00:00', '20:00:00'],
      'nigth' => ['19:00:00', '03:00:00'] // ข้ามเที่ยงคืน
    ];
    
    if (!isset($ranges[$eat_time])) return true;
    if (!$open || !$close) return true;

    [$start, $end] = $ranges[$eat_time];
    $open_time  = strtotime($open);
    $close_time = strtotime($close);
    $start_time = strtotime($start);
    $end_time   = strtotime($end);

    if ($end_time < $start_time) $end_time += 86400;
    if ($close_time < $open_time) $close_time += 86400;

    return ($open_time <= $end_time && $close_time >= $start_time);
  }

  foreach ($shops as $shop) {
    $open_time = $shop['time_open'] ?? '';
    $close_time = $shop['time_close'] ?? '';
    $distance = haversine($lat, $lng, $shop['latitude'], $shop['longitude']);
    $price_match = !$price || floatval($shop['price']) <= floatval($price);
    $type_match = !$type || stripos($shop['category'], $type) !== false;
    $time_match = !$eat_time || match_time($eat_time, $open_time, $close_time);
 

    if ($distance <= 5000 && $price_match && $type_match && $time_match) {
      $shop['distance'] = round($distance);
      $filtered[] = $shop;
    }
  }

}

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

// สร้างรายการหมวดหมู่ที่มีข้อมูลสีและไอคอน
$categories = [];
$i = 0;
foreach ($categoryLabels as $label) {
  $color = $colors[$i % count($colors)];
  $categories[] = [
    'label' => $label,
    'bg' => "$color-100",
    'text' => "$color-600",
    'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16' // คุณสามารถใช้ไอคอนเดียวกัน หรือเพิ่มไอคอนตามหมวดหมู่ได้
  ];
  $i++;
}
?>

<main class="px-4 py-10 max-w-2xl mx-auto">
  <h2 class="text-2xl sm:text-3xl font-bold mb-8 text-center text-orange-600">🍽️ กรอกความชอบของคุณ</h2>

  <form method="POST" onsubmit="return fillLocation();" class="space-y-4 bg-white p-6 rounded-xl shadow-xl">
    <input type="hidden" name="lat" id="lat">
    <input type="hidden" name="lng" id="lng">

    <label class="block">
      <span class="font-semibold text-gray-700">ราคา (บาท):</span>
      <input type="number" name="price" required class="border border-gray-300 p-2 rounded w-full mt-1">
    </label>

    <label class="block">
      <span class="font-semibold text-gray-700">ประเภทอาหาร:</span>
      <select name="type" class="border border-gray-300 p-2 rounded w-full mt-1">
        <option value="">เลือกประเภทอาหาร</option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= htmlspecialchars($category['label']) ?>" class="text-<?= htmlspecialchars($category['text']) ?>">
            <?= htmlspecialchars($category['label']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <label class="block">
      <span class="font-semibold text-gray-700">เวลาที่อยากกิน:</span>
      <select name="eat_time" class="border border-gray-300 p-2 rounded w-full mt-1">
        <option value="">- เลือกช่วงเวลา -</option>
        <option value="breakfast" class="text-red-600 bg-red-100">เช้า</option>
        <option value="lunch" class="text-orange-600 bg-orange-100">เที่ยง</option>
        <option value="dinner" class="text-yellow-600 bg-yellow-100">เย็น</option>
        <option value="nigth" class="text-green-600 bg-green-100">กลางคืน</option>
      </select>
    </label>

    <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600 transition w-full font-semibold text-lg">แสดงร้านที่ตรงกับคุณ</button>
  </form>

  <br>

  <!-- Google Ads #1 -->
  <div class="max-w-7xl mx-auto px-4 overflow-hidden" >
  <img src="assets/1.png" alt="แบนเนอร์ 1"
    class="w-full h-32 sm:h-48 md:h-72 lg:h-[200px] object-contain bg-white " />
</div>

  <br>
</main>

<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
<section class="px-4 sm:px-6 md:px-10 py-12 max-w-screen-xl mx-auto">
  <h2 class="text-2xl sm:text-3xl font-bold mb-8 text-center text-orange-600">ร้านอาหารที่ตรงกับคุณ</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php foreach ($filtered as $shop): ?>
      <?php
        $distance_text = $shop['distance'] > 1000
          ? round($shop['distance'] / 1000, 2) . ' กิโลเมตร'
          : $shop['distance'] . ' เมตร';
        $image = $shop['image_url'] ?? 'https://via.placeholder.com/400x250?text=No+Image';
      ?>
      <a href="shop.php?id=<?= $shop['id'] ?>" class="rounded-xl overflow-hidden border hover:shadow-lg transition transform hover:-translate-y-1">
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
            <div class="mt-2 text-sm text-gray-700 font-medium flex items-baseline gap-1">
                <!-- แสดงระยะทางที่นี่ --> 
                <p class="text-sm text-gray-600 mt-2">ระยะทาง: <?= $distance_text ?></p>
            </div>
            <div class="mt-2 text-sm text-gray-700 font-medium flex items-baseline gap-1">
                <p class="text-xs text-gray-400 mt-1">เปิด: <?= htmlspecialchars($shop['time_open']) ?> - <?= htmlspecialchars($shop['time_close']) ?></p>
            </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
  <?php if (empty($filtered)): ?>
    <p class="text-center text-gray-500 mt-10">ไม่พบร้านที่ตรงกับคุณ</p>
  <?php endif; ?>
</section>
<?php endif; ?>

<br>

  <!-- Google Ads #1 -->
  <div class="max-w-7xl mx-auto px-4 overflow-hidden" >
  <img src="assets/2.png" alt="แบนเนอร์ 2"
    class="w-full h-32 sm:h-48 md:h-72 lg:h-[200px] object-contain bg-white " />
</div>
<br>

</main>

<script>
function fillLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      document.getElementById('lat').value = position.coords.latitude;
      document.getElementById('lng').value = position.coords.longitude;
      document.forms[0].submit();
    });
    return false; // รอ geolocation ก่อน submit
  } else {
    alert("ไม่สามารถระบุตำแหน่งได้");
    return true;
  }
}
</script>

<?php include 'components/footer.php'; ?>