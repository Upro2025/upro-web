<?php
// ตรวจสอบว่ามีการส่งค่า shop_id มาใน URL หรือไม่
$shop_id = $_GET['id'] ?? '';
if (!$shop_id) {
    echo "❌ ไม่พบร้านค้าที่ต้องการแก้ไข!";
    exit;
}

// ตั้งค่า Supabase
$supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";

// ดึงข้อมูลร้านจาก Supabase
$api_url = "$supabase_url/rest/v1/shops?id=eq.$shop_id";
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: $supabase_key",
    "Authorization: Bearer $supabase_key"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$shop = json_decode($response, true);
if (count($shop) === 0) {
    echo "❌ ไม่พบข้อมูลร้านค้าที่ต้องการแก้ไข!";
    exit;
}
$shop = $shop[0]; // ดึงข้อมูลร้านค้าที่เราเลือก

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. ตั้งค่า Supabase
    $image_url = $shop['image_url']; // ใช้ค่า image_url เดิมถ้าไม่ได้อัปโหลดใหม่

    if (isset($_FILES["image_file"]) && $_FILES["image_file"]["error"] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES["image_file"]["tmp_name"];
        $ext = pathinfo($_FILES["image_file"]["name"], PATHINFO_EXTENSION);
        $file_name = uniqid() . "_image." . $ext;
        $mime = mime_content_type($file_tmp);
        $upload_url = "$supabase_url/storage/v1/object/shop-images/$file_name";

        // การอัปโหลดไฟล์ไป Supabase
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

        if ($http_code === 200) {
            $image_url = "$supabase_url/storage/v1/object/public/shop-images/$file_name";
        } else {
            echo "❌ อัปโหลดรูปภาพไม่สำเร็จ ($http_code)<br>";
            echo "<pre>$response</pre>";
            exit;
        }
    }

    // 2. เตรียมข้อมูลที่จะแก้ไข
    $data = [
        "name" => $_POST["name"],
        "description" => $_POST["description"],
        "category" => $_POST["category"],
        "address" => $_POST["address"],
        "latitude" => floatval($_POST["latitude"]),
        "longitude" => floatval($_POST["longitude"]),
        "phone" => $_POST["phone"],
        "time_open" => $_POST["time_open"],
        "time_close" => $_POST["time_close"],
        "price" => $_POST["price"],
        "image_url" => $image_url
    ];

    // 3. ส่งข้อมูลไปยัง Supabase
    $options = [
        "http" => [
            "method" => "PATCH",
            "header" => "Content-type: application/json\r\n" .
                        "apikey: $supabase_key\r\n" .
                        "Authorization: Bearer $supabase_key\r\n",
            "content" => json_encode($data)
        ]
    ];
    $context = stream_context_create($options);
    $result = file_get_contents("$supabase_url/rest/v1/shops?id=eq.$shop_id", false, $context);

    if ($result === FALSE) {
        echo "❌ อัปเดตข้อมูลร้านค้าไม่สำเร็จ";
    } else {
        // เมื่ออัปเดตสำเร็จ รีไดเร็กต์ไปยังหน้า dashboard.php
        header("Location: dashboard.php");
        exit; // หยุดการทำงานหลังจากรีไดเร็กต์
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขร้านค้า</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* เปลี่ยนเป็น flex-start */
            min-height: 100vh;
            overflow-y: auto; /* ทำให้สามารถเลื่อนหน้าได้ */
        }
        h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-size: 1rem;
            color: #333;
        }
        input[type="text"],
        input[type="file"],
        input[type="time"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        textarea {
            resize: vertical;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group:last-child {
            margin-bottom: 0;
        }
        .button-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    
    <form method="POST" enctype="multipart/form-data">
        <h2>แก้ไขร้านค้า</h2>
        <label>ชื่อร้านค้า: <input type="text" name="name" value="<?= htmlspecialchars($shop['name']) ?>" required></label><br>
        <label>คำอธิบาย: <textarea name="description"><?= htmlspecialchars($shop['description']) ?></textarea></label><br>
        <label>หมวดหมู่: 
            <select name="category" required>
                <option value="Fine Dining" <?= ($shop['category'] === "Fine Dining") ? 'selected' : '' ?>>Fine Dining (ภัตตาคาร)</option>
                <option value="Chef’s Table" <?= ($shop['category'] === "Chef’s Table") ? 'selected' : '' ?>>Chef’s Table</option>
                <option value="Fast Food" <?= ($shop['category'] === "Fast Food") ? 'selected' : '' ?>>ฟาสต์ฟู้ด</option>
                <option value="Food Truck" <?= ($shop['category'] === "Food Truck") ? 'selected' : '' ?>>รถขายอาหาร (Food Track)</option>
                <option value="Bar and Pub" <?= ($shop['category'] === "Bar and Pub") ? 'selected' : '' ?>>บาร์และผับ</option>
                <option value="Cafe" <?= ($shop['category'] === "Cafe") ? 'selected' : '' ?>>คาเฟ่ (Cafe)</option>
                <option value="Shabu and Buffet" <?= ($shop['category'] === "Shabu and Buffet") ? 'selected' : '' ?>>ชาบูและบุฟเฟ่ต์ (Buffet)</option>
            </select>
        </label><br>
        <label>ที่อยู่: <input type="text" name="address" value="<?= htmlspecialchars($shop['address']) ?>"></label><br>
        <label>ละติจูด: <input type="text" name="latitude" value="<?= htmlspecialchars($shop['latitude']) ?>"></label><br>
        <label>ลองจิจูด: <input type="text" name="longitude" value="<?= htmlspecialchars($shop['longitude']) ?>"></label><br>
        <label>เบอร์โทรศัพท์: <input type="text" name="phone" value="<?= htmlspecialchars($shop['phone']) ?>"></label><br>
        <label>เวลาเปิดร้าน: <input type="time" name="time_open" value="<?= htmlspecialchars($shop['time_open']) ?>"></label><br>
        <label>เวลาปิดร้าน: <input type="time" name="time_close" value="<?= htmlspecialchars($shop['time_close']) ?>"></label><br>
        <label>ราคาเริ่มต้น: <input type="text" name="price" value="<?= htmlspecialchars($shop['price']) ?>"></label><br>
        <label>เลือกรูปภาพ (ถ้ามีการอัปโหลดใหม่): <input type="file" name="image_file" accept="image/*"></label><br><br>
        <button type="submit">อัปเดตร้านค้า</button>
    </form>
</body>
</html>
