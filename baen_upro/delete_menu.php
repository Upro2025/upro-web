<?php
// Supabase credentials
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";

// รับค่า id และ shop_id จาก URL
$menu_id = $_GET['id'] ?? '';
$shop_id = $_GET['shop_id'] ?? '';

// เช็คว่ามีข้อมูลหรือไม่
if (empty($menu_id) || empty($shop_id)) {
    echo "❌ ไม่พบข้อมูลที่ต้องการลบ (menu_id หรือ shop_id หายไป)";
    exit;
}

// URL สำหรับลบเมนู
$api_url = "$supabase_url/rest/v1/menus?id=eq.$menu_id";

// ใช้ CURL DELETE
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $supabase_key",
    "Authorization: Bearer $supabase_key"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// ตรวจสอบผล
if ($http_code == 204) {
    header("Location: view_menus.php?shop_id=$shop_id");
    exit;
} else {
    echo "❌ ไม่สามารถลบได้ (HTTP: $http_code)";
    echo "<pre>$response</pre>";
}
?>
