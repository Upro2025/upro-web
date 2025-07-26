<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. ตั้งค่า Supabase
    $supabase_url = "https://pvojevkazwrkjdwqjgrw.supabase.co";
$supabase_key = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB2b2pldmthendya2pkd3FqZ3J3Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NTIzMzcyNTMsImV4cCI6MjA2NzkxMzI1M30.AonguMS1faIMvGJk2qDFBxsLosXuXW6j5r3HtHmccgU";

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
if (isset($upload_url)) {
    echo "Upload URL: $upload_url\n";
}
if (isset($supabase_key)) {
    echo "Key Start: " . substr($supabase_key, 0, 10) . "\n";
}
if (isset($mime)) {
    echo "MIME: $mime\n";
}
if (isset($http_code)) {
    echo "HTTP Code: $http_code\n";
}
if (isset($response)) {
    echo "Response:\n$response";
}
echo "</pre>";
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มร้านค้า</title>
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
    <div>
        <h2>เพิ่มร้านค้าใหม่</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">ชื่อร้านค้า:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="description">คำอธิบาย:</label>
                <textarea name="description" id="description"></textarea>
            </div>

            <div class="form-group">
                <label for="category">หมวดหมู่:</label>
                <select name="category" id="category" required>
                    <option value="Fine Dining">Fine Dining (ภัตตาคาร)</option>
                    <option value="Chef’s Table">Chef’s Table</option>
                    <option value="Fast Food">ฟาสต์ฟู้ด</option>
                    <option value="Food Truck">รถขายอาหาร (Food Track)</option>
                    <option value="Bar and Pub">บาร์และผับ</option>
                    <option value="Cafe">คาเฟ่ (Cafe)</option>
                    <option value="Shabu and Buffet">ชาบูและบุฟเฟ่ต์ (Buffet)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="address">ที่อยู่:</label>
                <input type="text" name="address" id="address">
            </div>

            <div class="form-group">
                <label for="latitude">ละติจูด:</label>
                <input type="text" name="latitude" id="latitude">
            </div>

            <div class="form-group">
                <label for="longitude">ลองจิจูด:</label>
                <input type="text" name="longitude" id="longitude">
            </div>

            <div class="form-group">
                <label for="phone">เบอร์โทรศัพท์:</label>
                <input type="text" name="phone" id="phone">
            </div>

            <div class="form-group">
                <label for="time_open">เวลาเปิดร้าน:</label>
                <input type="time" name="time_open" id="time_open">
            </div>

            <div class="form-group">
                <label for="time_close">เวลาปิดร้าน:</label>
                <input type="time" name="time_close" id="time_close">
            </div>

            <div class="form-group">
                <label for="price">ราคาเริ่มต้น:</label>
                <input type="text" name="price" id="price">
            </div>

            <div class="form-group">
                <label for="image_file">เลือกรูปภาพ:</label>
                <input type="file" name="image_file" id="image_file" accept="image/*">
            </div>

            <button type="submit">เพิ่มร้านค้า</button>
        </form>
    </div>
</body>
</html>
