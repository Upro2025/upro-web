<?php
include 'components/header.php';
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";
include 'supabase.php';

// รับ id ร้านจาก URL
$shop_id = $_GET['id'] ?? '';

// ดึงข้อมูลร้านจากฐานข้อมูล
$shop = null;
if ($shop_id !== '') {
    $api_url = "$supabase_url/rest/v1/shops?id=eq.$shop_id&select=*";
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "apikey: $supabase_key",
      "Authorization: Bearer $supabase_key"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    if (is_array($data) && count($data)) {
        $shop = $data[0];
    }
}

// ถ้ายังไม่ได้ร้าน ให้แจ้งเตือน
if (!$shop) {
    echo "<div class='text-center text-red-500 font-bold text-xl mt-20'>ไม่พบข้อมูลร้าน!</div>";
    include 'components/footer.php';
    exit;
}

// ดึงเมนูจากตาราง menus
$menus = null;
$menu_api_url = "$supabase_url/rest/v1/menus?menu_id=eq.$shop_id&select=*";
$menu_ch = curl_init($menu_api_url);
curl_setopt($menu_ch, CURLOPT_HTTPHEADER, [
    "apikey: $supabase_key",
    "Authorization: Bearer $supabase_key"
]);
curl_setopt($menu_ch, CURLOPT_RETURNTRANSFER, true);
$menu_result = curl_exec($menu_ch);
curl_close($menu_ch);
$menus = json_decode($menu_result, true);

?>

<main class="p-8 max-w-6xl mx-auto font-sans">
  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <!-- ซ้าย: รูปภาพ + ที่อยู่ + เบอร์ + เวลา + แผนที่ -->
    <div class="md:col-span-1">
      <!-- รูปภาพ -->
      <img src="<?= htmlspecialchars($shop['image_url'] ?: 'assets/restaurant1.jpg') ?>"
           class="rounded-xl w-full h-64 object-cover mb-4" alt="<?= htmlspecialchars($shop['name']) ?>">

      <!-- ที่อยู่ -->
      <div class="bg-white rounded-lg shadow-md p-4 border border-gray-100 mb-4">
        <h2 class="text-lg font-semibold mb-2 text-[#f37021]">ข้อมูลร้าน</h2>
        <ul class="space-y-2 text-gray-700 text-sm">
          <li class="flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/>
            </svg>
            <?= htmlspecialchars($shop['address']) ?>
          </li>
          <li class="flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M3 5h18M3 10h18M10 15h4"/>
            </svg>
            <?= htmlspecialchars($shop['phone']) ?>
          </li>
          <li class="flex items-center gap-2">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path d="M12 8v4l3 3"/>
            </svg>
            <?= htmlspecialchars($shop['time_open']) ?> - <?= htmlspecialchars($shop['time_close']) ?>
          </li>
        </ul>
        <!-- Google Map -->
      <?php
        $lat = $shop['latitude'];
        $lng = $shop['longitude'];
        $gmap_url = "https://www.google.com/maps/search/?api=1&query=$lat,$lng";
      ?>
      <a href="<?= $gmap_url ?>" target="_blank" class="block mt-4 mb-2">
        <div class="text-center text-[#f37021] text-sm font-semibold hover:underline">นำทางด้วย Google Maps</div>
      </a>
      </div>
    </div>

    <!-- ขวา: รายละเอียดร้าน -->
    <div class="md:col-span-2 flex flex-col gap-4">
      <div class="bg-white rounded-lg shadow-md p-4 border border-gray-100 mb-4">
        <div class="flex-1">
          <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($shop['name']) ?></h1>
          <span class="inline-block bg-orange-100 text-orange-600 text-xs px-3 py-1 rounded-full mb-2"><?= htmlspecialchars($shop['category']) ?></span>
          <p class="text-gray-600 leading-relaxed mb-4"><?= htmlspecialchars($shop['description']) ?></p>
          <div class="text-xl font-semibold text-[#f37021] mb-3">เริ่มต้น <?= number_format($shop['price']) ?> บาท</div>
        </div>
      </div>

      <!-- Google Ads (แยกบล็อกจากรายละเอียดร้าน) -->
      <div class="w-full md:w-full flex-shrink-0 mt-6 md:mt-0">
        <div class="bg-gray-50 rounded-lg border shadow p-4 text-center">
          <span class="font-semibold text-gray-600 text-xs">Google Ads</span>
          <div class="my-2 h-20 bg-gray-200 rounded animate-pulse"></div>
          <span class="text-xs text-gray-400">พื้นที่โฆษณา</span>
        </div>
      </div>
    </div>
  </div>

  <!-- แสดงเมนูร้าน -->
      <?php if ($menus): ?>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Menu</h2>
        <div class="grid md:grid-cols-3 gap-4">
          <?php foreach ($menus as $menu): ?>
            <div class="bg-white rounded-lg shadow-md p-4 border border-gray-100 mb-4">
              <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($menu['name']) ?></h3>
              <p class="text-gray-600"><?= htmlspecialchars($menu['description']) ?></p>
              <div class="text-orange-500 font-semibold text-lg"><?= number_format($menu['price']) ?> บาท</div>
              <div class="mt-2">
                <?php if ($menu['available']): ?>
                  <span class="inline-block bg-green-100 text-green-600 text-xs px-2 py-1 rounded-md">Available</span>
                <?php else: ?>
                  <span class="inline-block bg-red-100 text-red-600 text-xs px-2 py-1 rounded-md">Out of Stock</span>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <p class="text-center text-gray-500">ไม่มีเมนูสำหรับร้านนี้</p>
      <?php endif; ?>
</main>

<?php include 'components/footer.php'; ?>
