<?php
// Set Supabase credentials
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";

$shop_id = $_GET['shop_id'] ?? '';

if (empty($shop_id)) {
    echo "<p class='text-red-600'>❌ ไม่พบร้านที่ต้องการเพิ่มเมนู</p>";
    exit;
}

// Function to fetch shop details
function getShopById($shop_id) {
    global $supabase_url, $supabase_key;
    $api_url = "$supabase_url/rest/v1/shops?id=eq.$shop_id";
    $ch = curl_init($api_url);
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => [
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key"
        ],
        CURLOPT_RETURNTRANSFER => true
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    $shop = json_decode($result, true);
    return $shop[0] ?? null;
}

// Fetch shop details to get shop_name
$shop = getShopById($shop_id);
if (!$shop) {
    echo "<p class='text-red-600'>❌ ไม่พบข้อมูลร้านที่ระบุ</p>";
    exit;
}

$shop_name = $shop['name'];

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $payload = [
        'menu_id' => $shop_id,
        'name' => $name,
        'description' => $description,
        'price' => $price
    ];

    $ch = curl_init("$supabase_url/rest/v1/menus");
    curl_setopt_array($ch, [
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "apikey: $supabase_key",
            "Authorization: Bearer $supabase_key"
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload)
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 201) {
        header("Location: view_menus.php?shop_id=$shop_id");
        exit;
    } else {
        $message = "❌ ไม่สามารถเพิ่มเมนูได้ (HTTP Code: $http_code)";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-2xl mx-auto py-12">
        <div class="bg-white rounded-lg shadow-xl p-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">✨ เพิ่มเมนูใหม่สำหรับร้าน "<?= htmlspecialchars($shop_name) ?>"</h1>

            <?php if ($message): ?>
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="add_menu.php?shop_id=<?= htmlspecialchars($shop_id) ?>" method="POST" class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700">ชื่อเมนู</label>
                    <input type="text" name="name" id="name" required class="w-full p-2 border rounded-md">
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700">รายละเอียดเมนู</label>
                    <textarea name="description" id="description" required class="w-full p-2 border rounded-md h-32"></textarea>
                </div>

                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700">ราคา (บาท)</label>
                    <input type="number" name="price" id="price" required class="w-full p-2 border rounded-md">
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="view_menus.php?shop_id=<?= htmlspecialchars($shop_id) ?>" class="py-2 px-4 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">ยกเลิก</a>
                    <button type="submit" class="py-2 px-4 bg-blue-600 text-white rounded-md hover:bg-blue-700">บันทึกเมนู</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
