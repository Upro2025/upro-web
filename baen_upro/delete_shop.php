<?php
// เชื่อมต่อกับ Supabase
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";

// ตรวจสอบว่าได้รับ shop_id จาก URL หรือไม่
$shop_id = $_GET['id'] ?? '';

if ($shop_id) {
    // ส่งคำขอ DELETE ไปยัง Supabase
    $api_url = "$supabase_url/rest/v1/shops?id=eq.$shop_id";
    
    $options = [
        "http" => [
            "method" => "DELETE",
            "header" => "Content-type: application/json\r\n" .
                        "apikey: $supabase_key\r\n" .
                        "Authorization: Bearer $supabase_key\r\n"
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($api_url, false, $context);

    if ($result === FALSE) {
        echo "❌ ไม่สามารถลบข้อมูลร้านค้าได้";
    } else {
        echo "✅ ลบข้อมูลร้านค้าสำเร็จ!";
    }
} else {
    echo "❌ ข้อมูลร้านค้าไม่ถูกต้อง";
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลบร้านค้า</title>
</head>
<body>
    <h2>ลบร้านค้า</h2>
    <p>
        <?php
        // แสดงข้อความเมื่อมีการลบสำเร็จ
        if (isset($result)) {
            if (strpos($result, "✅") !== false) {
                echo "ร้านค้าถูกลบเรียบร้อยแล้ว!";
            } else {
                echo "เกิดข้อผิดพลาดในการลบร้านค้า!";
            }
        }
        ?>
    </p>
    <a href="dashboard.php">กลับไปที่หน้า Dashboard</a>
</body>
</html>
