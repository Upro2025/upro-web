<?php
// Supabase credentials
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";

// รับ menu_id จาก URL
$menu_id = $_GET['id'] ?? '';

if (empty($menu_id)) {
    echo "❌ ไม่พบ menu_id ที่ต้องการแก้ไข";
    exit;
}

// ดึงข้อมูลเมนูเพื่อนำไปแก้ไข
function getMenuById($menu_id) {
    global $supabase_url, $supabase_key;
    $url = "$supabase_url/rest/v1/menus?id=eq.$menu_id";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key"
        ]
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    return $data[0] ?? null;
}

$menu = getMenuById($menu_id);

if (!$menu) {
    echo "❌ ไม่พบเมนูในระบบ";
    exit;
}

$shop_id = $menu['menu_id']; // <-- สมมติว่าชื่อคอลัมน์ shop_id ใน menus คือ menu_id
$name = $menu['name'];
$description = $menu['description'];
$price = $menu['price'];

$message = '';

// เมื่อมีการกดบันทึกการแก้ไข
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $payload = [
        'name' => $name,
        'description' => $description,
        'price' => $price
    ];

    $update_url = "$supabase_url/rest/v1/menus?id=eq.$menu_id";
    $ch = curl_init($update_url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "PATCH",
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key"
        ]
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 204) {
        header("Location: view_menus.php?shop_id=$shop_id");
        exit;
    } else {
        $message = "❌ ไม่สามารถบันทึกการแก้ไขได้ (HTTP: $http_code)";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขเมนู</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="max-w-xl mx-auto mt-12 bg-white p-8 shadow-md rounded-lg">
    <h2 class="text-xl font-bold mb-6">✏️ แก้ไขเมนู</h2>

    <?php if ($message): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4">
        <div>
            <label class="block font-semibold text-gray-700">ชื่อเมนู</label>
            <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required class="w-full p-2 border rounded-md">
        </div>

        <div>
            <label class="block font-semibold text-gray-700">รายละเอียด</label>
            <textarea name="description" required class="w-full p-2 border rounded-md"><?= htmlspecialchars($description) ?></textarea>
        </div>

        <div>
            <label class="block font-semibold text-gray-700">ราคา (บาท)</label>
            <input type="number" name="price" value="<?= htmlspecialchars($price) ?>" required class="w-full p-2 border rounded-md">
        </div>

        <div class="flex justify-end space-x-3">
            <a href="view_menus.php?shop_id=<?= htmlspecialchars($shop_id) ?>" class="px-4 py-2 bg-gray-300 rounded-md hover:bg-gray-400">ยกเลิก</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">บันทึก</button>
        </div>
    </form>
</div>
</body>
</html>
