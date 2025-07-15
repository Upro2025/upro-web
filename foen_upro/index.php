<?php
$current = basename($_SERVER['PHP_SELF']);
include 'components/header.php';
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";
include 'supabase.php';

// ดึงข้อมูลร้าน
$response = $client->request('GET', 'shops', [
    'headers' => [
        'apikey' => $SUPABASE_ANON_KEY,
        'Authorization' => 'Bearer ' . $SUPABASE_ANON_KEY,
        'Accept' => 'application/json',
    ],
    'query' => [
        'select' => '*',
        'order' => 'created_at.desc',
        'limit' => '*'
    ]
]);

$data = json_decode($response->getBody(), true);
?>

<main class="px-4 sm:px-6 md:px-8 py-8">
  <!-- HERO -->
  <section class="text-center max-w-3xl mx-auto">
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-[#f37021] mb-4">Discover Great Food Near You</h1>
    <p class="text-gray-600 mb-6">Find the best restaurants, exclusive deals, and delicious meals in your area</p>
    <form method="GET" action="nearby.php" id="searchForm" class="flex flex-col md:flex-row gap-2 justify-center">
      <input type="text" name="q" placeholder="Search restaurants..." 
             class="px-4 py-2 border border-gray-300 rounded-md w-full md:w-1/2 focus:ring-2 focus:ring-[#f37021] focus:outline-none" />
      <input type="hidden" name="lat" id="lat">
      <input type="hidden" name="lng" id="lng">
      <button type="submit"
        class="bg-[#f37021] text-white px-6 py-2 rounded-md flex items-center gap-2 hover:bg-orange-600 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17.657 16.657L13.414 12.414A6 6 0 1112 13.414l4.243 4.243a1 1 0 001.414-1.414zM10 16a6 6 0 100-12 6 6 0 000 12z" />
        </svg>
        <span>Find Near Me</span>
      </button>
    </form>

    <script>
      const form = document.getElementById("searchForm");
      const latInput = document.getElementById("lat");
      const lngInput = document.getElementById("lng");

      function getLocationAndSubmit() {
        navigator.geolocation.getCurrentPosition(function (position) {
          latInput.value = position.coords.latitude;
          lngInput.value = position.coords.longitude;
          form.submit();
        }, function (error) {
          alert("\u26A0\uFE0F ไม่สามารถเข้าถึงตำแหน่งของคุณได้");
        });
      }

      form.addEventListener("submit", function (e) {
        if (!latInput.value || !lngInput.value) {
          e.preventDefault();
          getLocationAndSubmit();
        }
      });
    </script>
  </section>

  <!-- POPULAR CATEGORIES -->
  <section class="mt-12">
    <h2 class="text-xl sm:text-2xl font-semibold mb-4">Popular Categories</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
      <?php
      $categories = [
        ['label' => 'Fine Dining', 'bg' => 'red-100', 'text' => 'red-600', 'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
        ['label' => 'Chef’s Table', 'bg' => 'orange-100', 'text' => 'orange-600', 'icon' => 'M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3z'],
        ['label' => 'Fast Food', 'bg' => 'yellow-100', 'text' => 'yellow-600', 'icon' => 'M5 12h14M9 16h6M7 20h10'],
        ['label' => 'Food Truck', 'bg' => 'green-100', 'text' => 'green-600', 'icon' => 'M3 13l2-2h13l2 2v5H3v-5z'],
        ['label' => 'Bar&Pub', 'bg' => 'purple-100', 'text' => 'purple-600', 'icon' => 'M12 3v18m-6 0h12'],
        ['label' => 'Cafe', 'bg' => 'blue-100', 'text' => 'blue-600', 'icon' => 'M8 21h8m-4-2v-6m0 0a4 4 0 00-4-4h0a4 4 0 00-4 4h0a4 4 0 004 4z'],
        ['label' => 'Buffet', 'bg' => 'teal-100', 'text' => 'teal-600', 'icon' => 'M3 10h18M9 21h6M12 3v18'],
      ];
      foreach ($categories as $cat):
      ?>
        <div class="rounded-xl border p-6 flex flex-col items-center hover:shadow-md transition">
          <div class="bg-<?= $cat['bg'] ?> p-3 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-<?= $cat['text'] ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="<?= $cat['icon'] ?>" />
            </svg>
          </div>
          <p class="mt-2 font-semibold text-sm md:text-base text-center break-words"><?= $cat['label'] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- FEATURED RESTAURANTS -->
  <section class="mt-12">
    <h2 class="text-xl sm:text-2xl font-semibold mb-4">Featured Restaurants</h2>
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
        <p class="text-center text-gray-500 col-span-3">No restaurants found.</p>
      <?php endif; ?>
    </div>
  </section>
</main>

<?php include 'components/footer.php'; ?>
