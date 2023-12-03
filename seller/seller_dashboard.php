<!-- seller_dashboard.php -->

<?php
include '../config.php'; // 데이터베이스 연결 설정 파일

session_start();

// 판매자로 로그인된 경우에만 접근 허용
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header('Location: ../user/login.php'); // 판매자가 아니면 로그인 페이지로 이동
    exit();
}

// 현재 판매자의 ID 가져오기
$id = $_SESSION['id'];

// 현재 판매자가 등록한 정육점 목록 가져오기
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
                echo '<li><a href="../user/logout.php">Sign out</a></li>'; // 경로 수정
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
                </div>
              </li>";
                }
            } else {
                echo "즐겨찾기에 등록된 정육점이 없습니다.";
            }
            ?>
        </ul>

</body>

</html>