<!-- add_meat.php -->

<?php
include 'config.php'; // 데이터베이스 연결 설정 파일

session_start();

// 판매자로 로그인된 경우에만 접근 허용
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header('Location: login.php'); // 판매자가 아니면 로그인 페이지로 이동
    exit();
}

// 정육점 ID 가져오기
if (isset($_GET['shop_id'])) {
    $shopID = $_GET['shop_id'];

    // 정육점 정보 가져오기
    $shopQuery = "SELECT * FROM ButcherShop WHERE id = '$shopID' AND seller_id = '{$_SESSION['id']}'";
    $shopResult = $conn->query($shopQuery);

    if ($shopResult->num_rows > 0) {
        $shopRow = $shopResult->fetch_assoc();
    } else {
        echo "해당 정육점을 찾을 수 없습니다.";
        exit();
    }
} else {
    echo "정육점 ID가 제공되지 않았습니다.";
    exit();
}

// 고기 추가 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $meatName = $_POST['meat_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // 고기 추가 쿼리
    $addMeatQuery = "INSERT INTO Meat (meat_name, price, shop_id, quantity) VALUES ('$meatName', '$price', '$shopID', '$quantity')";

    if ($conn->query($addMeatQuery) === TRUE) {
        echo "고기가 추가되었습니다!";
        header('Location: meatlist.php?shop_id=' . $shopID);
        exit();

    } else {
        echo "Error: " . $addMeatQuery . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>고기 추가</title>
</head>
<body>

<h2>고기 추가 - <?php echo $shopRow['shop_name']; ?></h2>

<form method="post" action="add_meat.php?shop_id=<?php echo $shopID; ?>">
    <label for="meat_name">고기 이름:</label>
    <input type="text" name="meat_name" required>
    <br>
    <label for="price">가격:</label>
    <input type="text" name="price" required>
    <br>
    <label for="quantity">수량:</label>
    <input type="number" name="quantity" required>
    <br>
    <input type="submit" value="추가">
</form>

<!-- 기존 내용 -->

</body>
</html>
