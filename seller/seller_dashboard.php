<?php
include '../config.php';

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header('Location: ../user/login.php');
    exit();
}

$id = $_SESSION['id'];
$query = "SELECT * FROM ButcherShop WHERE seller_id = '$id'";
$result = $conn->query($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style2.css">
    <title>Seller Dashboard</title>
</head>

<body>
    <nav>
        <div class="nav-left">
            <h1><a href="../index.php">MeatView.</a></h1>
        </div>
        <div class="nav-right">
            <ul>
                <?php
                echo '<li><a href="../user/logout.php">Sign out</a></li>';
                ?>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h2 style="text-align: center;">My ButcherShop (Seller)</h2>
        <br>
        <div class="button-container">
            <form method="get" action="register_shop.php">
                <input type="submit" class="shop-list-button" value="정육점 등록">
            </form>
        </div>

        <ul>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $shopID = $row['id'];
                    echo "<li class='favorite-item'>
                <div>
                    <strong>{$row['shop_name']}</strong> - {$row['location']}
                </div>
                <div class='button-group'>
                    <a href='meat/meatlist.php?shop_id=$shopID' class='view-meatlist-button'>고기 목록</a>
                    <form method='post' action=''>
                        <input type='hidden' name='shop_id' value='$shopID'>
                        <button type='submit' name='delete_shop' class='delete-shop-button'>정육점 삭제</button>
                    </form>
                </div>
              </li>";
                }



                if (isset($_POST['delete_shop'])) {
                    $deleteShopID = $_POST['shop_id'];

                    $deleteReviewQuery = "DELETE FROM Review WHERE shop_id = '$deleteShopID'";
                    $deleteReviewResult = $conn->query($deleteReviewQuery);

                    if ($deleteReviewResult) {

                        $deleteQuery = "DELETE FROM ButcherShop WHERE id = '$deleteShopID' AND seller_id = '$id'";
                        $deleteResult = $conn->query($deleteQuery);

                        if ($deleteResult) {
                        } else {
                            echo '<script>alert("정육점 삭제 중 오류가 발생했습니다.");</script>';
                        }
                    } else {
                        echo '<script>alert("관련 리뷰 삭제 중 오류가 발생했습니다.");</script>';
                    }
                }
            }

            ?>
        </ul>
    </div>
</body>

</html>