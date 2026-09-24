<?php

require_once __DIR__ . "/db.php";


// Lấy id sản phẩm từ URL
$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;


// Lấy sản phẩm từ database
$sql = "SELECT * FROM products WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();


// Kiểm tra sản phẩm có tồn tại không
if ($result->num_rows === 0) {

    die("Không tìm thấy sản phẩm.");

}


$product = $result->fetch_assoc();

$stmt->close();

?>

<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        <?= htmlspecialchars($product["name"]) ?> - MY SHOP
    </title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >


    <style>

        .detail-container {

            max-width: 900px;

            margin: 60px auto;

            padding: 30px;

            display: flex;

            gap: 50px;

            background: white;

            border-radius: 18px;

            box-shadow:
                0 10px 30px rgba(0, 0, 0, 0.10);

        }


        .detail-image {

            width: 400px;

            height: 400px;

            object-fit: cover;

            border-radius: 15px;

        }


        .detail-info {

            flex: 1;

        }


        .detail-info h2 {

            font-size: 32px;

            margin-top: 0;

            color: #111827;

        }


        .detail-price {

            font-size: 26px;

            font-weight: bold;

            color: #e11d48;

            margin: 20px 0;

        }


        .detail-description {

            color: #6b7280;

            line-height: 1.7;

        }


        .detail-stock {

            margin-top: 20px;

            font-weight: bold;

            color: #16a34a;

        }


        .quantity {

            margin-top: 25px;

        }


        .quantity input {

            width: 80px;

            padding: 10px;

            border: 1px solid #d1d5db;

            border-radius: 8px;

            font-size: 16px;

        }


        .buy-button {

            display: inline-block;

            margin-top: 20px;

            padding: 13px 25px;

            background: #111827;

            color: white;

            border: none;

            border-radius: 8px;

            text-decoration: none;

            font-weight: bold;

            cursor: pointer;

        }


        .buy-button:hover {

            background: #374151;

        }


        .back-button {

            display: inline-block;

            margin-top: 15px;

            color: #374151;

            text-decoration: none;

        }


        @media (max-width: 700px) {

            .detail-container {

                flex-direction: column;

                margin: 30px 15px;

                padding: 20px;

            }


            .detail-image {

                width: 100%;

                height: auto;

                max-height: 400px;

            }

        }

    </style>

</head>


<body>


<!-- HEADER -->

<header>

    <div class="header-container">

        <h1>MY SHOP</h1>

        <nav>

            <a href="index.php">
                HOME
            </a>

            <a href="admin.php">
                ADMIN
            </a>

        </nav>

    </div>

</header>



<!-- PRODUCT DETAIL -->

<main>

    <div class="detail-container">


        <!-- IMAGE -->

        <img
            class="detail-image"
            src="images/<?= htmlspecialchars($product["image"]) ?>"
            alt="<?= htmlspecialchars($product["name"]) ?>"
        >


        <!-- INFORMATION -->

        <div class="detail-info">


            <h2>

                <?= htmlspecialchars($product["name"]) ?>

            </h2>


            <p class="detail-price">

                <?= number_format($product["Price"]) ?>円

            </p>


            <p class="detail-description">

                <?= nl2br(
                    htmlspecialchars($product["description"])
                ) ?>

            </p>


            <?php if ($product["stock"] > 0): ?>

                <p class="detail-stock">

                    Còn lại:
                    <?= $product["stock"] ?>
                    sản phẩm

                </p>


                <div class="quantity">

                    <label>
                        Số lượng:
                    </label>

                    <input
                        type="number"
                        id="quantity"
                        value="1"
                        min="1"
                        max="<?= $product["stock"] ?>"
                    >

                </div>


                <button
                    class="buy-button"
                    onclick="buyProduct()"
                >

                    MUA NGAY

                </button>


            <?php else: ?>

                <p
                    style="
                        color: #dc2626;
                        font-weight: bold;
                    "
                >

                    HẾT HÀNG

                </p>

            <?php endif; ?>


            <br>


            <a
                href="index.php"
                class="back-button"
            >

                ← Quay lại cửa hàng

            </a>


        </div>

    </div>

</main>



<script>

function buyProduct() {

    const quantity =
        document.getElementById("quantity").value;


    const price =
        <?= (int)$product["Price"] ?>;


    const total =
        quantity * price;


    alert(
        "Bạn đã chọn " +
        quantity +
        " sản phẩm.\n\n" +
        "Tổng tiền: " +
        total.toLocaleString() +
        "円"
    );

}

</script>


</body>

</html>