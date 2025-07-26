<?php
// Set Supabase credentials
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";

// Fetch shop details
$shop_id = $_GET['shop_id'] ?? ''; // Assuming the shop_id is passed in the URL

// Function to fetch shop details
function getShopById($shop_id) {
    global $supabase_url, $supabase_key;
    $api_url = "$supabase_url/rest/v1/shops?id=eq.$shop_id";
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Authorization: Bearer $supabase_key"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    
    if(curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);
    $shop = json_decode($result, true);
    return $shop[0] ?? null; // Return the first result, which is the shop details
}

// Fetch the shop name
$shop = getShopById($shop_id);
if ($shop) {
    $shop_name = $shop['name']; // Store shop name
} else {
    $shop_name = "Unknown Shop"; // Default if shop not found
}

// Function to fetch menus for the specific shop
function getMenusByShopId($shop_id) {
    global $supabase_url, $supabase_key;
    $api_url = "$supabase_url/rest/v1/menus?menu_id=eq.$shop_id"; // Ensure correct column for filtering
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: $supabase_key",
        "Authorization: Bearer $supabase_key"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    
    if(curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }

    curl_close($ch);
    return json_decode($result, true);
}
// Fetch menus for the specific shop
$menus = getMenusByShopId($shop_id);
?>

<main class="p-8 max-w-7xl mx-auto font-sans">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center"><?= htmlspecialchars($shop_name) ?>'s Menus</h1>

    <div class="overflow-x-auto rounded-lg shadow-md border border-gray-200 mb-6">
        <table class="min-w-full text-sm text-left bg-white border-collapse border border-gray-200">
            <thead class="uppercase bg-gray-100">
                <tr>
                    <th class="border border-gray-300 px-3 py-2">ID</th>
                    <th class="border border-gray-300 px-3 py-2">Menu Name</th>
                    <th class="border border-gray-300 px-3 py-2">Description</th>
                    <th class="border border-gray-300 px-3 py-2">Price</th>
                    <th class="border border-gray-300 px-3 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($menus)): ?>
                    <?php foreach ($menus as $menu): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($menu['id']) ?></td>
                            <td class="border border-gray-300 px-3 py-2 font-medium"><?= htmlspecialchars($menu['name']) ?></td>
                            <td class="border border-gray-300 px-3 py-2"><?= htmlspecialchars($menu['description']) ?></td>
                            <td class="border border-gray-300 px-3 py-2"><?= number_format($menu['price'], 2) ?> บาท</td>
                            <td class="border border-gray-300 px-3 py-2">
                                <a href="edit_menu.php?id=<?= $menu['id'] ?>" class="text-blue-500 hover:underline">Edit</a> |
                                <a href="delete_menu.php?id=<?= $menu['id'] ?>&shop_id=<?= $shop_id ?>" class="text-red-500 hover:underline">Delete</a>


                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-gray-500">ยังไม่มีเมนูสำหรับร้านนี้</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add New Menu Button -->
    <div class="mt-8 text-center">
        <a href="add_menu.php?shop_id=<?= $shop_id ?>" class="bg-blue-500 text-white px-6 py-3 rounded-md shadow-lg hover:bg-blue-600 transition duration-200">
            เพิ่มเมนูใหม่
        </a>
    </div>
</main>

<!-- Add styles to make it more modern and responsive -->
<style>
    body {
        background-color: #f9fafb;
        font-family: 'Roboto', sans-serif;
    }
    h1 {
        color: #333;
        font-weight: bold;
    }
    .bg-white {
        background-color: white;
    }
    .text-gray-800 {
        color: #333;
    }
    .text-gray-600 {
        color: #4a4a4a;
    }
    .text-blue-500 {
        color: #007bff;
    }
    .hover\:underline:hover {
        text-decoration: underline;
    }
    .bg-blue-500 {
        background-color: #007bff;
    }
    .hover\:bg-blue-600:hover {
        background-color: #0056b3;
    }
</style>
