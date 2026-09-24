<?php

require_once __DIR__ . "/db.php";

$message = "";
$messageType = "";


/*
|--------------------------------------------------------------------------
| XỬ LÝ KHI NHẤN NÚT THÊM SẢN PHẨM
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Lấy dữ liệu từ form
    $name = trim($_POST["name"] ?? "");
    $price = (int)($_POST["price"] ?? 0);
    $description = trim($_POST["description"] ?? "");
    $stock = (int)($_POST["stock"] ?? 0);


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA DỮ LIỆU
    |--------------------------------------------------------------------------
    */

    if ($name === "") {

        $message = "Vui lòng nhập tên sản phẩm.";
        $messageType = "error";

    } elseif ($price <= 0) {

        $message = "Giá sản phẩm phải lớn hơn 0.";
        $messageType = "error";

    } elseif ($description === "") {

        $message = "Vui lòng nhập mô tả sản phẩm.";
        $messageType = "error";

    } elseif ($stock < 0) {

        $message = "Số lượng không được nhỏ hơn 0.";
        $messageType = "error";

    } elseif (!isset($_FILES["image"])) {

        $message = "Vui lòng chọn ảnh sản phẩm.";
        $messageType = "error";

    } elseif ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

        $message = "Có lỗi khi chọn ảnh. Mã lỗi: " . $_FILES["image"]["error"];
        $messageType = "error";

    } else {


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA ẢNH
        |--------------------------------------------------------------------------
        */

        $image = $_FILES["image"];

        $imageName = basename($image["name"]);

        $imageTmp = $image["tmp_name"];

        $imageSize = $image["size"];


        // Kiểm tra loại ảnh
        $allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/gif",
            "image/webp"
        ];

        $imageType = mime_content_type($imageTmp);


        if (!in_array($imageType, $allowedTypes)) {

            $message = "Chỉ được upload JPG, PNG, GIF hoặc WEBP.";
            $messageType = "error";

        } elseif ($imageSize > 5 * 1024 * 1024) {

            $message = "Ảnh không được lớn hơn 5MB.";
            $messageType = "error";

        } else {


            /*
            |--------------------------------------------------------------------------
            | TẠO TÊN ẢNH MỚI
            |--------------------------------------------------------------------------
            */

            $extension = strtolower(
                pathinfo($imageName, PATHINFO_EXTENSION)
            );

            $newImageName = uniqid("product_", true) . "." . $extension;


            /*
            |--------------------------------------------------------------------------
            | THƯ MỤC LƯU ẢNH
            |--------------------------------------------------------------------------
            */

            $uploadDir = __DIR__ . "/images/";


            // Nếu chưa có thư mục images thì tạo
            if (!is_dir($uploadDir)) {

                mkdir($uploadDir, 0755, true);

            }


            /*
            |--------------------------------------------------------------------------
            | ĐƯỜNG DẪN LƯU ẢNH
            |--------------------------------------------------------------------------
            */

            $imagePath = $uploadDir . $newImageName;


            /*
            |--------------------------------------------------------------------------
            | UPLOAD ẢNH
            |--------------------------------------------------------------------------
            */

            if (move_uploaded_file($imageTmp, $imagePath)) {


                /*
                |--------------------------------------------------------------------------
                | THÊM SẢN PHẨM VÀO DATABASE
                |--------------------------------------------------------------------------
                */

                $sql = "INSERT INTO products
                        (name, Price, description, image, stock)
                        VALUES (?, ?, ?, ?, ?)";


                $stmt = $conn->prepare($sql);


                if ($stmt) {


                    $stmt->bind_param(
                        "sissi",
                        $name,
                        $price,
                        $description,
                        $newImageName,
                        $stock
                    );


                    if ($stmt->execute()) {

                        $message = "Thêm sản phẩm thành công!";

                        $messageType = "success";

                    } else {

                        $message = "Lỗi database: " . $stmt->error;

                        $messageType = "error";

                        // Nếu database lỗi thì xóa ảnh vừa upload
                        if (file_exists($imagePath)) {

                            unlink($imagePath);

                        }

                    }


                    $stmt->close();


                } else {

                    $message = "Lỗi SQL: " . $conn->error;

                    $messageType = "error";

                    // Xóa ảnh nếu SQL lỗi
                    if (file_exists($imagePath)) {

                        unlink($imagePath);

                    }

                }


            } else {

                $message = "Không thể upload ảnh.";

                $messageType = "error";

            }

        }

    }

}

?>


<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin - MY SHOP</title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            margin: 0;

            padding: 50px 20px;

            font-family: Arial, sans-serif;

            background: #f3f4f6;

        }


        /*
        |--------------------------------------------------------------------------
        | THÔNG BÁO
        |--------------------------------------------------------------------------
        */

        .message {

            width: 500px;

            max-width: 100%;

            margin: 0 auto 20px;

            padding: 15px 20px;

            border-radius: 10px;

            text-align: center;

            font-weight: bold;

        }


        .success {

            background: #dcfce7;

            color: #166534;

            border: 1px solid #86efac;

        }


        .error {

            background: #fee2e2;

            color: #991b1b;

            border: 1px solid #fca5a5;

        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN BOX
        |--------------------------------------------------------------------------
        */

        .admin {

            width: 500px;

            max-width: 100%;

            margin: auto;

            padding: 35px;

            background: white;

            border-radius: 15px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.10);

        }


        /*
        |--------------------------------------------------------------------------
        | TIÊU ĐỀ
        |--------------------------------------------------------------------------
        */

        h1 {

            margin: 0 0 30px;

            padding: 18px 25px;

            text-align: center;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #111827,
                    #4b5563
                );

            border-radius: 14px;

            font-size: 28px;

            letter-spacing: 1px;

            box-shadow:
                0 8px 20px rgba(0, 0, 0, 0.15);

        }


        /*
        |--------------------------------------------------------------------------
        | LABEL
        |--------------------------------------------------------------------------
        */

        label {

            display: block;

            margin-top: 16px;

            margin-bottom: 7px;

            font-weight: bold;

            color: #111827;

        }


        /*
        |--------------------------------------------------------------------------
        | INPUT
        |--------------------------------------------------------------------------
        */

        input,
        textarea {

            width: 100%;

            padding: 12px;

            border: 1px solid #d1d5db;

            border-radius: 8px;

            font-size: 15px;

            background: white;

        }


        input:focus,
        textarea:focus {

            outline: none;

            border-color: #111827;

        }


        /*
        |--------------------------------------------------------------------------
        | TEXTAREA
        |--------------------------------------------------------------------------
        */

        textarea {

            height: 100px;

            resize: vertical;

        }


        /*
        |--------------------------------------------------------------------------
        | FILE INPUT
        |--------------------------------------------------------------------------
        */

        input[type="file"] {

            padding: 10px;

            cursor: pointer;

        }


        /*
        |--------------------------------------------------------------------------
        | BUTTON
        |--------------------------------------------------------------------------
        */

        button {

            width: 100%;

            margin-top: 25px;

            padding: 14px;

            border: none;

            border-radius: 8px;

            background: #111827;

            color: white;

            font-size: 16px;

            font-weight: bold;

            cursor: pointer;

        }


        button:hover {

            background: #374151;

        }


    </style>

</head>


<body>


<?php if ($message !== ""): ?>

    <div class="message <?= $messageType ?>">

        <?= htmlspecialchars($message) ?>

    </div>

<?php endif; ?>


<div class="admin">


    <h1>
        SHOP CUA HAU
    </h1>


    <form
        method="POST"
        enctype="multipart/form-data"
    >


        <!-- TÊN -->

        <label>
            Tên sản phẩm
        </label>

        <input
            type="text"
            name="name"
            placeholder="Ví dụ: Áo hoodie"
            required
        >


        <!-- GIÁ -->

        <label>
            Giá
        </label>

        <input
            type="number"
            name="price"
            placeholder="Ví dụ: 7000"
            min="1"
            required
        >


        <!-- MÔ TẢ -->

        <label>
            Mô tả
        </label>

        <textarea
            name="description"
            placeholder="Nhập mô tả sản phẩm..."
            required
        ></textarea>


        <!-- ẢNH -->

        <label>
            Ảnh sản phẩm
        </label>

        <input
            type="file"
            name="image"
            accept="image/jpeg,image/png,image/gif,image/webp"
            required
        >


        <!-- SỐ LƯỢNG -->

        <label>
            Số lượng
        </label>

        <input
            type="number"
            name="stock"
            placeholder="Ví dụ: 20"
            min="0"
            required
        >


        <!-- BUTTON -->

        <button type="submit">

            THÊM SẢN PHẨM

        </button>


    </form>


</div>


</body>

</html>