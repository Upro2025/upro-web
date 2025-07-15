<?php
include 'components/header.php';
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";
include 'supabase.php';

function haversine($lat1, $lon1, $lat2, $lon2) {
    $R = 6371000; // เมตร
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat/2)**2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2)**2;
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $R * $c;
}

$query = $_GET['q'] ?? '';
$userLat = isset($_GET['lat']) ? floatval($_GET['lat']) : null;
$userLng = isset($_GET['lng']) ? floatval($_GET['lng']) : null;

$shops_url = "$supabase_url/rest/v1/shops?select=*&apikey=$supabase_key";
$response = file_get_contents($shops_url);
$shops = json_decode($response, true);

$filtered = [];

foreach ($shops as $shop) {
    $matchKeyword = !$query ||
        stripos($shop['name'], $query) !== false ||
        stripos($shop['category'], $query) !== false;

    $matchDistance = true;

    if ($userLat !== null && $userLng !== null &&
        isset($shop['latitude'], $shop['longitude']) &&
        is_numeric($shop['latitude']) && is_numeric($shop['longitude'])) {

        $distance = haversine($userLat, $userLng, $shop['latitude'], $shop['longitude']);
        
        $matchDistance = $distance <= 20000; // ลอง 100 เมตรก่อน
    }

    if ($matchKeyword && $matchDistance) {
        $filtered[] = $shop;
    }
}

$data = $filtered;

?>


<header>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</header>

<main class="px-4 sm:px-6 md:px-10 py-6">
  <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 mb-6">
    <div>
      <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-800">Nearby Restaurants</h2>
      <p class="text-gray-500 text-sm">Restaurants within 2km of your location</p>
    </div>
    <form method="GET" action="nearby.php" id="refreshLocationForm" class="w-full md:w-auto">
      <div class="flex gap-2">
        <input type="hidden" name="lat" id="refresh_lat">
        <input type="hidden" name="lng" id="refresh_lng">
        <button type="submit"
          class="w-full md:w-auto flex items-center justify-center gap-2 bg-[#f37021] text-white px-4 py-2 rounded-md hover:bg-orange-500 transition">
          <i class="fa-solid fa-paper-plane"></i>
          Refresh Location
        </button>
      </div>
    </form>
  </div>

  <script>
    const refreshForm = document.getElementById("refreshLocationForm");
    const refreshLat = document.getElementById("refresh_lat");
    const refreshLng = document.getElementById("refresh_lng");

    refreshForm.addEventListener("submit", function (e) {
      e.preventDefault();
      navigator.geolocation.getCurrentPosition(function (position) {
        refreshLat.value = position.coords.latitude;
        refreshLng.value = position.coords.longitude;
        refreshForm.submit();
      }, function () {
        alert("\u26A0\uFE0F ไม่สามารถเข้าถึงตำแหน่งของคุณได้");
      });
    });
  </script>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php if (!empty($data)): ?>
      <?php foreach ($data as $shop): ?>
        <div class="rounded-xl overflow-hidden border hover:shadow-lg transition transform hover:-translate-y-1">
          <img src="<?= $shop['image_url'] ?: 'assets/restaurant1.jpg' ?>" class="w-full h-48 object-cover" alt="<?= htmlspecialchars($shop['name']) ?>">
          <div class="p-4">
            <h3 class="font-semibold text-lg line-clamp-1"><?= htmlspecialchars($shop['name']) ?></h3>
            <span class="inline-block bg-red-100 text-red-600 text-sm px-2 py-1 mt-1 rounded-md"><?= htmlspecialchars($shop['category']) ?></span>
            <?php if (!empty($shop['price'])): ?>
              <div class="mt-2 text-sm text-gray-700 font-medium flex items-baseline gap-1">
                <span>เริ่มต้น</span>
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
      <p class="text-center text-gray-500 col-span-full">ไม่พบร้านที่ใกล้คุณในขณะนี้</p>
    <?php endif; ?>
  </div>
</main>

<?php include 'components/footer.php'; ?>
