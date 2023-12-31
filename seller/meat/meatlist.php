<!-- meatlist.php -->

<?php
include '../../config.php';

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header('Location: ../../user/login.php');
    exit();
}

if (isset($_GET['shop_id'])) {
    $shopID = $_GET['shop_id'];
    $shopQuery = "SELECT * FROM ButcherShop WHERE id = '$shopID' AND seller_id = '{$_SESSION['id']}'";
    $shopResult = $conn->query($shopQuery);

    if ($shopResult->num_rows > 0) {
        $shopRow = $shopResult->fetch_assoc();
    } else {
        echo "해당 정육점을 찾을 수 없습니다.";
        exit();
    }

    $meatQuery = "SELECT * FROM Meat WHERE shop_id = '$shopID'";
    $meatResult = $conn->query($meatQuery);
} else {
    echo "정육점 ID가 제공되지 않았습니다.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style/style2.css">
    <title>고기 목록 - <?php echo $shopRow['shop_name']; ?></title>
</head>

<body>
    <nav>
        <div class="nav-left">
            <h1><a href="../index.php">MeatView.</a></h1>
        </div>
        <div class="nav-right">
            <ul>
                <?php
                echo '<li><a href="../seller_dashboard.php">Home</a></li>';
                echo '<li><a href="../../user/logout.php">Sign out</a></li>';
                ?>
            </ul>
        </div>
    </nav>


    <div class="container">
        <br>
        <h1>고기 목록 - <?php echo $shopRow['shop_name']; ?></h1>
        <br>
        <div class="button-container">
            <form method="get" action="add_meat.php">
                <input type="hidden" name="shop_id" value="<?php echo $shopID; ?>">
                <input type="submit" class="shop-list-button" value="고기 추가">
            </form>
        </div>

        <ul>
            <?php
            while ($meatRow = $meatResult->fetch_assoc()) {
                echo "<li class='favorite-item'>
            <div>
                <strong>{$meatRow['meat_name']}</strong><br>
                가격: {$meatRow['price']}만원<br>
                수량: {$meatRow['quantity']}
            </div>
            <div class='button-group'>
                <form method='get' action='edit_meat.php'>
                    <input type='hidden' name='meat_id' value='{$meatRow['id']}'>
                    <input type='submit' class='view-shop-button' value='수정'>
                </form>
                <button class='delete-button' onclick='confirmDelete({$meatRow['id']})'>삭제</button>
            </div>
          </li>";
            }
            ?>
        </ul>
    </div>

    <script>
        function confirmDelete(meatId) {
            var confirmDelete = confirm("정말로 삭제하시겠습니까?");
            if (confirmDelete) {
                window.location.href = 'delete_meat.php?meat_id=' + meatId + '&shop_id=<?php echo $shopID; ?>';
            }
        }
    </script>

</body>

</html>