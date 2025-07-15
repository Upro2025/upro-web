<?php include '../components/header.php'; ?>
<main class="p-6">
  <h1 class="text-xl font-bold mb-4">รายการออเดอร์</h1>
  <ul id="orderList" class="space-y-4"></ul>
</main>
<script>
  async function fetchOrders() {
    const { data, error } = await supabase.from('orders').select(`
      id, created_at, total_price, status,
      users (name),
      shops (name)
    `).order('created_at', { ascending: false });

    const list = document.getElementById('orderList');
    list.innerHTML = error ? `<li>${error.message}</li>` :
      data.map(o => `<li class="border p-4 rounded">
        <p><strong>ร้าน:</strong> ${o.shops.name}</p>
        <p><strong>ลูกค้า:</strong> ${o.users?.name ?? '-'}</p>
        <p><strong>ยอดรวม:</strong> ฿${o.total_price}</p>
        <p><strong>สถานะ:</strong> ${o.status}</p>
        <p class="text-xs text-gray-500">${o.created_at}</p>
      </li>`).join('');
  }

  fetchOrders();
</script>
<?php include '../components/footer.php'; ?>
