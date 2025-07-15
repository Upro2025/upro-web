
<?php include 'menu_bar.php'; ?>

<main class="ml-64 p-6"> <!-- เพิ่ม ml-64 -->
  <h1 class="text-2xl font-bold text-[#f37021] mb-6 flex items-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-[#f37021]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6a1 1 0 001 1h6m-7 7h-2a2 2 0 01-2-2v-4a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2z" />
    </svg>
    Dashboard ร้านอาหาร
  </h1>

  <!-- ส่วนอื่น ๆ ทั้งหมดไม่ต้องเปลี่ยน -->


  <!-- Section: Summary Cards -->
  <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-4 flex items-center justify-between">
      <div>
        <p class="text-gray-600 text-sm">เมนูทั้งหมด</p>
        <h2 class="text-xl font-bold" id="menuCount">0</h2>
      </div>
      <svg class="w-6 h-6 text-[#f37021]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
    </div>

    <div class="bg-white rounded-lg shadow p-4 flex items-center justify-between">
      <div>
        <p class="text-gray-600 text-sm">ยอดขายวันนี้</p>
        <h2 class="text-xl font-bold" id="salesToday">฿0</h2>
      </div>
      <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h11M9 21V3m9 4h3m-3 4h3m-3 4h3" />
      </svg>
    </div>

    <div class="bg-white rounded-lg shadow p-4 flex items-center justify-between">
      <div>
        <p class="text-gray-600 text-sm">ออเดอร์ใหม่</p>
        <h2 class="text-xl font-bold" id="newOrders">0</h2>
      </div>
      <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4H4z" />
      </svg>
    </div>

    <div class="bg-white rounded-lg shadow p-4 flex items-center justify-between">
      <div>
        <p class="text-gray-600 text-sm">รีวิวเฉลี่ย</p>
        <h2 class="text-xl font-bold" id="avgRating">-</h2>
      </div>
      <svg class="w-6 h-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.943a1 1 0 00.95.69h4.15c.969 0 1.371 1.24.588 1.81L17.6 11.81a1 1 0 00-.364 1.118l1.286 3.943c.3.921-.755 1.688-1.54 1.118L12 15.347l-3.36 2.444c-.784.57-1.838-.197-1.54-1.118l1.287-3.943a1 1 0 00-.364-1.118L4.663 9.37c-.783-.57-.38-1.81.588-1.81h4.15a1 1 0 00.95-.69l1.286-3.943z" />
      </svg>
    </div>
  </div>

  <!-- Section: Coming soon or other content -->
  <div class="bg-gray-50 p-6 rounded-lg border border-dashed text-center text-gray-500">
    เพิ่มรายงาน หรือรายการเมนูเด่น ได้ที่นี่เร็วๆ นี้
  </div>
</main>

<script>
  const shop_id = 'your-shop-id'; // แก้ตามร้าน

  async function loadDashboard() {
    // เมนูทั้งหมด
    let { count: menuCount } = await supabase.from('menus').select('*', { count: 'exact', head: true }).eq('shop_id', shop_id);
    document.getElementById('menuCount').textContent = menuCount ?? 0;

    // ยอดขายวันนี้
    const today = new Date().toISOString().split('T')[0];
    let { data: sales } = await supabase.from('sales').select('total_income').eq('shop_id', shop_id).eq('sale_date', today).maybeSingle();
    document.getElementById('salesToday').textContent = `฿${sales?.total_income ?? 0}`;

    // ออเดอร์ใหม่วันนี้
    let { count: newOrders } = await supabase.from('orders').select('*', { count: 'exact', head: true }).eq('shop_id', shop_id).eq('status', 'pending');
    document.getElementById('newOrders').textContent = newOrders ?? 0;

    // รีวิวเฉลี่ย
    let { data: reviews } = await supabase.from('reviews').select('rating').eq('shop_id', shop_id);
    let ratings = reviews?.map(r => r.rating) || [];
    let avg = ratings.length ? (ratings.reduce((a, b) => a + b, 0) / ratings.length).toFixed(1) : '-';
    document.getElementById('avgRating').textContent = avg;
  }

  loadDashboard();
</script>

