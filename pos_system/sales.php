<?php include '../components/header.php'; ?>
<main class="p-6">
  <h1 class="text-xl font-bold mb-4">ยอดขายรายวัน</h1>
  <table class="table-auto w-full text-sm">
    <thead>
      <tr class="bg-gray-100">
        <th class="px-2 py-1 text-left">วันที่</th>
        <th class="px-2 py-1 text-left">รายได้รวม</th>
        <th class="px-2 py-1 text-left">จำนวนออเดอร์</th>
      </tr>
    </thead>
    <tbody id="salesBody"></tbody>
  </table>
</main>
<script>
  async function loadSales() {
    const { data, error } = await supabase.from('sales').select('*').order('sale_date', { ascending: false });
    const body = document.getElementById('salesBody');
    body.innerHTML = error ? `<tr><td colspan="3">${error.message}</td></tr>` :
      data.map(row => `
        <tr>
          <td class="border px-2 py-1">${row.sale_date}</td>
          <td class="border px-2 py-1">฿${row.total_income}</td>
          <td class="border px-2 py-1">${row.total_orders}</td>
        </tr>`).join('');
  }

  loadSales();
</script>
<?php include '../components/footer.php'; ?>
