<?php

require_once __DIR__ . "/db.php";

// Lấy sản phẩm từ database
$sql = "SELECT * FROM products ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>MY SHOP - HAU</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        /* =========================
           PRODUCT LIST
        ========================= */

        .products {

            display: grid;

            grid-template-columns:
                repeat(auto-fit, minmax(260px, 1fr));

            gap: 30px;

            max-width: 1100px;

            margin: 0 auto;

            padding: 20px;

        }


        /* =========================
           PRODUCT CARD
        ========================= */

        .product-card {

            background: white;

            border-radius: 15px;

            padding: 20px;

            text-align: center;

            box-shadow:
                0 8px 25px rgba(0, 0, 0, 0.08);

            transition:
                transform 0.3s ease,
                box-shadow 0.3s ease;

        }


        .product-card:hover {

            transform: translateY(-5px);

            box-shadow:
                0 12px 30px rgba(0, 0, 0, 0.15);

        }


        /* =========================
           PRODUCT IMAGE
        ========================= */

        .product-card img {

            width: 180px;

            height: 180px;

            object-fit: cover;

            border-radius: 12px;

            display: block;

            margin: 0 auto 18px;

        }


        /* =========================
           PRODUCT NAME
        ========================= */

        .product-card h3 {

            margin: 10px 0;

            font-size: 20px;

            color: #111827;

        }


        /* =========================
           PRICE
        ========================= */

        .product-card .price {

            font-size: 20px;

            font-weight: bold;

            color: #e11d48;

            margin: 10px 0;

        }


        /* =========================
           DESCRIPTION
        ========================= */

        .description {

            color: #6b7280;

            font-size: 14px;

            margin: 10px 0;

        }


        /* =========================
           STOCK
        ========================= */

        .stock {

            font-size: 14px;

            color: #16a34a;

            font-weight: bold;

            margin: 12px 0;

        }


        .stock.out {

            color: #dc2626;

        }


        /* =========================
           DETAIL BUTTON
        ========================= */

        .buy-button {

            display: inline-block;

            margin-top: 10px;

            padding: 10px 20px;

            background: #111827;

            color: white;

            text-decoration: none;

            border-radius: 8px;

            font-weight: bold;

            transition: background 0.3s ease;

        }


        .buy-button:hover {

            background: #374151;

        }


        /* =========================
           NO PRODUCT
        ========================= */

        .no-product {

            text-align: center;

            width: 100%;

            padding: 50px;

            color: #6b7280;

        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 600px) {

            .products {

                grid-template-columns: 1fr;

                padding: 15px;

            }


            .product-card img {

                width: 160px;

                height: 160px;

            }

        }

    </style>

</head>


<body>


<!-- =========================
     HEADER
========================= -->

<header>

    <div class="header-container">

        <h1>TRANG WEB CUA HAU</h1>

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



<!-- =========================
     MAIN
========================= -->

<main>


    <!-- HERO -->

    <section class="hero">

        <h2>
            Welcome to MY SHOP
        </h2>

        <p>
            Thời trang dành cho bạn
        </p>

    </section>



    <!-- PRODUCTS -->

    <section class="products-section">

        <h2 class="section-title">
            SẢN PHẨM
        </h2>


        <div class="products">


            <?php if ($result && $result->num_rows > 0): ?>


                <?php while ($product = $result->fetch_assoc()): ?>


                    <div class="product-card">


                        <!-- ẢNH SẢN PHẨM -->

                        <img
                            src="images/<?= htmlspecialchars($product['image']) ?>"
                            alt="<?= htmlspecialchars($product['name']) ?>"
                        >


                        <!-- TÊN -->

                        <h3>

                            <?= htmlspecialchars($product['name']) ?>

                        </h3>


                        <!-- GIÁ -->

                        <p class="price">

                            <?= number_format($product['Price']) ?>円

                        </p>


                        <!-- MÔ TẢ -->

                        <p class="description">

                            <?= htmlspecialchars($product['description']) ?>

                        </p>


                        <!-- SỐ LƯỢNG -->

                        <?php if ($product['stock'] > 0): ?>

                            <p class="stock">

                                Còn lại:
                                <?= $product['stock'] ?>
                                sản phẩm

                            </p>

                        <?php else: ?>

                            <p class="stock out">

                                HẾT HÀNG

                            </p>

                        <?php endif; ?>


                        <!-- XEM CHI TIẾT -->

                        <a
                            href="product.php?id=<?= $product['id'] ?>"
                            class="buy-button"
                        >

                            XEM CHI TIẾT

                        </a>


                    </div>


                <?php endwhile; ?>


            <?php else: ?>


                <p class="no-product">

                    Hiện chưa có sản phẩm.

                </p>


            <?php endif; ?>


        </div>

    </section>


</main>



<!-- =========================
     FOOTER
========================= -->

<footer>

    <p>
        © 2026 MY SHOP - HAU
    </p>

</footer>


</body>

</html>