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
        <option value="FastFood">Fast Food</option>
        <option value="Cafe">Cafe</option>
        <option value="Buffet">Buffet</option>
        <option value="Bar&Pub">Bar&Pub</option>
      </select>
    </label>

    <label class="block">
      <span class="font-semibold text-gray-700">เวลาที่อยากกิน:</span>
      <select name="eat_time" class="border border-gray-300 p-2 rounded w-full mt-1">
        <option value="">- เลือกช่วงเวลา -</option>
        <option value="breakfast">เช้า</option>
        <option value="lunch">เที่ยง</option>
        <option value="dinner">เย็น</option>
        <option value="nigth">กลางคืน</option>
      </select>
    </label>

    <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded hover:bg-orange-600 transition w-full font-semibold text-lg">แสดงร้านที่ตรงกับคุณ</button>
  </form>
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
      <div class="border rounded-xl overflow-hidden shadow bg-white hover:shadow-lg transition duration-300">
        <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($shop['name']) ?>" class="w-full h-48 object-cover">
        <div class="p-4">
          <h3 class="text-lg font-semibold text-gray-900 mb-1 truncate"><?= htmlspecialchars($shop['name']) ?></h3>
          <span class="inline-block bg-orange-100 text-orange-500 text-xs px-2 py-1 rounded-full mb-2">
            <?= htmlspecialchars($shop['category']) ?>
          </span>
          <p class="text-sm text-gray-500 mb-1">เริ่มต้น <span class="text-orange-500 font-bold text-base"><?= $shop['price'] ?></span> บาท</p>
          <p class="text-sm text-gray-600">ระยะทาง: <?= $distance_text ?></p>
          <p class="text-xs text-gray-400 mt-1">เปิด: <?= $shop['time_open'] ?> - <?= $shop['time_close'] ?></p>
          <div class="text-right mt-3">
            <a href="#" class="bg-orange-500 text-white text-sm px-4 py-1.5 rounded hover:bg-orange-600 transition">
              View Details
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php if (empty($filtered)): ?>
    <p class="text-center text-gray-500 mt-10">ไม่พบร้านที่ตรงกับคุณ</p>
  <?php endif; ?>
</section>
<?php endif; ?>
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