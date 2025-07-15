<!-- menu_add.php -->
<?php include 'menu_bar.php'; ?>
<main class="p-6 max-w-md mx-auto">
  <h1 class="text-xl font-bold mb-4">เพิ่มเมนูใหม่</h1>
  <form id="menuForm" class="space-y-4">
    <input type="text" name="name" placeholder="ชื่อเมนู" class="w-full border p-2 rounded" required />
    <textarea name="description" placeholder="รายละเอียดเมนู" class="w-full border p-2 rounded"></textarea>
    <input type="number" name="price" placeholder="ราคา" class="w-full border p-2 rounded" required />
    <input type="text" name="image_url" placeholder="URL รูปภาพ" class="w-full border p-2 rounded" />
    <button type="submit" class="bg-orange-500 text-white px-4 py-2 rounded">เพิ่มเมนู</button>
  </form>
  <div id="result" class="mt-4 text-sm text-green-600"></div>
</main>
<script>
  const form = document.getElementById('menuForm');
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(form).entries());
    data.shop_id = 'your-shop-id'; // ใส่ shop_id จริง

    const { error } = await supabase.from('menus').insert([data]);
    document.getElementById('result').textContent = error ? error.message : 'เพิ่มเมนูเรียบร้อยแล้ว';
    if (!error) form.reset();
  });
</script>
<?php include '../foen_upro/components/footer.php'; ?>
