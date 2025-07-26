<?php
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";

// Function to fetch all restaurants from the shops table
function getAllShops() {
    global $supabase_url, $supabase_key;
    $api_url = "$supabase_url/rest/v1/shops?select=*";
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Authorization: Bearer $supabase_key"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    return json_decode($result, true);
}

// Fetch all restaurants
$shops = getAllShops();
?>

<main class="p-8 max-w-7xl mx-auto font-sans">
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Restaurant Management Dashboard</h1>
    
    <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 mb-6">
        <table class="min-w-full text-sm text-left bg-white border-collapse border border-gray-200">
            <thead class="uppercase bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-3 py-2">ID</th>
                    <th class="border border-gray-300 px-3 py-2">Name</th>
                    <th class="border border-gray-300 px-3 py-2">Description</th>
                    <th class="border border-gray-300 px-3 py-2">Category</th>
                    <th class="border border-gray-300 px-3 py-2">Address</th>
                    <th class="border border-gray-300 px-3 py-2">Latitude</th>
                    <th class="border border-gray-300 px-3 py-2">Longitude</th>
                    <th class="border border-gray-300 px-3 py-2">Phone</th>
                    <th class="border border-gray-300 px-3 py-2">Image</th>
                    <th class="border border-gray-300 px-3 py-2">Created At</th>
                    <th class="border border-gray-300 px-3 py-2">Shop ID</th>
                    <th class="border border-gray-300 px-3 py-2">Time Open</th>
                    <th class="border border-gray-300 px-3 py-2">Time Close</th>
                    <th class="border border-gray-300 px-3 py-2">Price</th>
                    <th class="border border-gray-300 px-3 py-2">แก้ไข</th>
                    <th class="border border-gray-300 px-3 py-2">ลบ</th>
                    <th class="border border-gray-300 px-3 py-2">เมนู</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($shops as $shop): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($shop['id']) ?></td>
                        <td class="border border-gray-300 px-3 py-2 font-medium"><?= htmlspecialchars($shop['name']) ?></td>
                        <td class="border border-gray-300 px-3 py-2 max-w-xs truncate" title="<?= htmlspecialchars($shop['description']) ?>">
                            <?= htmlspecialchars($shop['description']) ?>
                        </td>
                        <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($shop['category']) ?></td>
                        <td class="border border-gray-300 px-3 py-2 max-w-xs truncate" title="<?= htmlspecialchars($shop['address']) ?>">
                            <?= htmlspecialchars($shop['address']) ?>
                        </td>
                        <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($shop['latitude']) ?></td>
                        <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($shop['longitude']) ?></td>
                        <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($shop['phone']) ?></td>
                        <td class="border border-gray-300 px-3 py-2">
    <div style="width:64px; height:64px; overflow:hidden; border-radius:8px; border:1px solid #ddd; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
        <img src="<?= htmlspecialchars($shop['image_url']) ?>" 
             alt="<?= htmlspecialchars($shop['name']) ?>" 
             style="width:100%; height:100%; object-fit:cover;">
    </div>
</td>

                        <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($shop['created_at']) ?></td>
                        <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($shop['shop_id']) ?></td>
                        <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($shop['time_open']) ?></td>
                        <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($shop['time_close']) ?></td>
                        <td class="border border-gray-300 px-3 py-2 font-semibold text-orange-600">
                            <?= number_format($shop['price']) ?> บาท
                        </td>
                        <td class="border border-gray-300 px-3 py-2 text-center">
                            <a href="edit_shop.php?id=<?= $shop['id'] ?>" class="bg-blue-500 text-white px-2 py-1 rounded-md shadow hover:bg-blue-600 transition">แก้ไข</a>
                        </td>
                        <td class="border border-gray-300 px-3 py-2 text-center">
                            <a href="delete_shop.php?id=<?= $shop['id'] ?>" class="bg-red-500 text-white px-2 py-1 rounded-md shadow hover:bg-red-600 transition">ลบ</a>
                        </td>
                        <td class="border border-gray-300 px-3 py-2 text-center">
                            <a href="view_menus.php?shop_id=<?= $shop['id'] ?>" class="bg-green-500 text-white px-2 py-1 rounded-md shadow hover:bg-green-600 transition">เมนู</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-8 text-center">
        <a href="add_shop.php" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow-lg transition duration-200">
            ➕ เพิ่มร้านอาหารใหม่
        </a>
    </div>
</main>
