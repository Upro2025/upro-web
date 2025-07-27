<?php include 'components/header.php'; ?>

<?php
  // Supabase settings
  $supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
  $supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";

  $keyword = trim($_GET['q'] ?? '');
  $category = trim($_GET['category'] ?? '');
  $filter = $_GET['filter'] ?? '';

  if ($keyword !== '' && !isset($_GET['filter'])) {
    $filter = 'closest';
  }

  $search_terms = [];
  $mapping_path = __DIR__ . '/keyword_map.json';
  $mapping = file_exists($mapping_path) ? json_decode(file_get_contents($mapping_path), true) : [];

  if ($keyword !== '') {
    $tokens = preg_split('/\s+/', mb_strtolower($keyword, 'UTF-8'));
    foreach ($tokens as $token) {
      $search_terms[] = $token;
      foreach ($mapping as $key => $related) {
        if (mb_strpos($token, $key) !== false || $token === $key) {
          $search_terms = array_merge($search_terms, $related);
        }
      }
    }
  }

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
  $categories = array_unique(array_filter(array_map(fn($i) => trim($i['category'] ?? ''), $cat_raw)));
  sort($categories);

  $conditions = [];
  foreach (array_unique($search_terms) as $term) {
    $like = "*".str_replace('*', '', $term)."*";
    $conditions[] = "name.ilike.$like";
    $conditions[] = "description.ilike.$like";
    $conditions[] = "category.ilike.$like";
  }
  $params = [];
  if (count($conditions)) {
    $params['or'] = "(".implode(",", $conditions).")";
  }
  if ($category !== '') {
    $params['category'] = "eq.$category";
  }
  $params['select'] = '*';
  if ($filter === 'latest') {
    $params['order'] = 'created_at.desc';
  }
  $query = http_build_query($params);
  $url = "$supabase_url/rest/v1/shops?$query";

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $supabase_key",
    "Authorization: Bearer $supabase_key"
  ]);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($ch);
  curl_close($ch);
  $shops = json_decode($response, true);

  $log_path = __DIR__ . '/search_log.json';
  $log = file_exists($log_path) ? json_decode(file_get_contents($log_path), true) : [];
  if ($keyword !== '') {
    $log[$keyword] = ($log[$keyword] ?? 0) + 1;
    file_put_contents($log_path, json_encode($log, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
  }
  arsort($log);
  $topKeywords = array_keys(array_slice($log, 0, 5, true));
?>

<script>
  const keywords = <?= json_encode(array_keys($mapping)) ?>;
  document.addEventListener('DOMContentLoaded', () => {
    const input = document.querySelector('input[name="q"]');
    const datalist = document.createElement('datalist');
    datalist.id = 'suggestions';
    keywords.forEach(k => {
      const option = document.createElement('option');
      option.value = k;
      datalist.appendChild(option);
    });
    document.body.appendChild(datalist);
    input.setAttribute('list', 'suggestions');
  });
</script>

<main class="px-4 sm:px-6 md:px-10 py-6">
  <!-- Search Form -->
  <section class="text-center max-w-3xl mx-auto mt-8 px-4">
    <form method="GET" action="search.php" class="flex flex-col gap-2 justify-center mb-6">
      <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" placeholder="ค้นหาร้าน..."
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

      <!-- Filter and Category Buttons -->
      <div class="flex flex-wrap gap-2 justify-center mt-4">
        <button type="submit" name="filter" value="closest"
          class="px-4 py-2 rounded-full border <?= $filter === 'closest' ? 'bg-[#f37021] text-white' : 'bg-white text-[#f37021]' ?>">ใกล้เคียงที่สุด</button>
        <button type="submit" name="filter" value="latest"
          class="px-4 py-2 rounded-full border <?= $filter === 'latest' ? 'bg-[#f37021] text-white' : 'bg-white text-[#f37021]' ?>">ล่าสุด</button>
        <?php foreach ($categories as $cat): ?>
          <button type="submit" name="category" value="<?= htmlspecialchars($cat) ?>"
            class="px-4 py-2 rounded-full border <?= $category === $cat ? 'bg-[#f37021] text-white' : 'bg-white text-[#f37021]' ?>">
            <?= htmlspecialchars($cat) ?>
          </button>
        <?php endforeach; ?>
      </div>
    </form>
  </section>

  <br>
  <div class="max-w-7xl mx-auto px-4 overflow-hidden" >
  <img src="assets/3.png" alt="แบนเนอร์ 3"
    class="w-full h-32 sm:h-48 md:h-72 lg:h-[200px] object-contain bg-white " />
</div>
  <br>

  <!-- Display Shops -->
  <section class="max-w-screen-xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 pb-16">
    <?php if (!$shops || count($shops) === 0): ?>
      <div class="col-span-full text-center text-gray-500 text-lg">ไม่พบผลลัพธ์</div>
    <?php else: ?>
      <?php foreach ($shops as $shop): ?>
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
    </div>
</a>

      <?php endforeach; ?>
    <?php endif; ?>
  </section>

  <br>
  <div class="max-w-7xl mx-auto px-4">
    <div class="bg-gray-100 h-32 flex items-center justify-center text-gray-500">[ Google Ads Banner #1 ]</div>
  </div>
  <br>

</main>

<?php include 'components/footer.php'; ?>
