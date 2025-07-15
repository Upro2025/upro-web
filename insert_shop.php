<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. ตั้งค่า Supabase
    $supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
    $supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU"; // 🔒 เปลี่ยนตรงนี้เป็น anon key จริง
    $bucket = "shop-images";
    $image_url = null;

    // 2. อัปโหลดภาพ
    if (isset($_FILES["image_file"]) && $_FILES["image_file"]["error"] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES["image_file"]["tmp_name"];
        $ext = pathinfo($_FILES["image_file"]["name"], PATHINFO_EXTENSION);
        $file_name = uniqid() . "_image." . $ext;
        $mime = mime_content_type($file_tmp);
        $upload_url = "$supabase_url/storage/v1/object/$bucket/$file_name";

        $ch = curl_init($upload_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($file_tmp));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $supabase_key",
            "apikey: $supabase_key",
            "Content-Type: $mime",
            "x-upsert: true"
        ]);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            echo "❌ อัปโหลดรูปภาพไม่สำเร็จ ($http_code)<br>";
            echo "<pre>$response</pre>";
            exit;
        }

        // สร้าง URL แบบ public
        $image_url = "$supabase_url/storage/v1/object/public/$bucket/$file_name";
    }

    // 3. เตรียมข้อมูลร้าน
    $data = [
        "name" => $_POST["name"],
        "description" => $_POST["description"],
        "category" => $_POST["category"],
        "address" => $_POST["address"],
        "latitude" => floatval($_POST["latitude"]),
        "longitude" => floatval($_POST["longitude"]),
        "phone" => $_POST["phone"],
        "time_open" => isset($_POST["time_open"]) ? $_POST["time_open"] : null,
        "time_close" => isset($_POST["time_close"]) ? $_POST["time_close"] : null,
        "price" => isset($_POST["price"]) ? $_POST["price"] : null,
        "image_url" => $image_url
    ];

    // 4. POST ไปที่ Supabase Table: shops
    $options = [
        "http" => [
            "method" => "POST",
            "header" => "Content-type: application/json\r\n" .
                        "apikey: $supabase_key\r\n" .
                        "Authorization: Bearer $supabase_key\r\n",
            "content" => json_encode($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents("$supabase_url/rest/v1/shops", false, $context);

    if ($result === FALSE) {
        echo "❌ เพิ่มข้อมูลร้านค้าไม่สำเร็จ";
    } else {
        echo "✅ เพิ่มร้านค้าสำเร็จ!";
    }
}

echo "<pre>";
echo "Upload URL: $upload_url\n";
echo "Key Start: " . substr($supabase_key, 0, 10) . "\n";
echo "MIME: $mime\n";
echo "HTTP Code: $http_code\n";
echo "Response:\n$response";
echo "</pre>";

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>เพิ่มร้านค้า</title>
</head>
<body>
    <h2>เพิ่มร้านค้าใหม่</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>ชื่อร้านค้า: <input type="text" name="name" required></label><br>
        <label>คำอธิบาย: <textarea name="description"></textarea></label><br>
        <label>หมวดหมู่: <input type="text" name="category"></label><br>
        <label>ที่อยู่: <input type="text" name="address"></label><br>
        <label>ละติจูด: <input type="text" name="latitude"></label><br>
        <label>ลองจิจูด: <input type="text" name="longitude"></label><br>
        <label>เบอร์โทรศัพท์: <input type="text" name="phone"></label><br>
        <label>เวลาเปิดร้าน: <input type="time" name="time_open"></label><br>
        <label>เวลาปิดร้าน: <input type="time" name="time_close"></label><br>
        <label>ราคาเริ่มต้น: <input type="text" name="price"></label><br>
        <label>เลือกรูปภาพ: <input type="file" name="image_file" accept="image/*"></label><br><br>
        <button type="submit">เพิ่มร้านค้า</button>
    </form>
</body>
</html>
