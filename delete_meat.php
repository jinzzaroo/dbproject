<?php
include 'config.php'; // 데이터베이스 연결 설정 파일

session_start();

// 판매자로 로그인된 경우에만 접근 허용
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'seller') {
    header('Location: login.php'); // 판매자가 아니면 로그인 페이지로 이동
    exit();
}

// 고기 ID 및 정육점 ID 가져오기
if (isset($_GET['meat_id'])) {
    $meatID = $_GET['meat_id'];

    // Get the shop_id associated with the deleted meat
    $getShopIDQuery = "SELECT shop_id FROM Meat WHERE id = '$meatID'";
    $shopIDResult = $conn->query($getShopIDQuery);

    if ($shopIDResult->num_rows > 0) {
        $shopIDRow = $shopIDResult->fetch_assoc();
        $shopID = $shopIDRow['shop_id'];
    } else {
        echo "해당 고기의 정육점을 찾을 수 없습니다.";
        exit();
    }

    // 해당 고기 정보 가져오기
    $meatQuery = "SELECT * FROM Meat WHERE id = '$meatID'";
    $meatResult = $conn->query($meatQuery);

    if ($meatResult->num_rows > 0) {
        $meatRow = $meatResult->fetch_assoc();
    } else {
        echo "해당 고기를 찾을 수 없습니다.";
        exit();
    }
} else {
    echo "고기 ID가 제공되지 않았습니다.";
    exit();
}

// 삭제 버튼 클릭 시의 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 고기 삭제 쿼리
    $deleteQuery = "DELETE FROM Meat WHERE id = '$meatID'";

    if ($conn->query($deleteQuery) === TRUE) {
        echo "고기가 성공적으로 삭제되었습니다!";
        header("Location: meatlist.php?shop_id=$shopID"); // Redirect to the meat list page with the correct shop_id
        exit();
    } else {
        echo "Error: " . $deleteQuery . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>고기 삭제</title>
</head>
<body>

<h2>고기 삭제</h2>

<!-- 현재 고기 정보 표시 -->
<h3>현재 고기 정보:</h3>
<p>고기 이름: <?php echo $meatRow['meat_name']; ?></p>
<p>가격: <?php echo $meatRow['price']; ?>원</p>
<p>수량: <?php echo $meatRow['quantity']; ?></p>

<!-- 삭제 확인 메시지 및 폼 -->
<p>정말로 삭제하시겠습니까?</p>
<form method="post" action="delete_meat.php?meat_id=<?php echo $meatID; ?>">
    <input type="submit" value="삭제">
</form>

</body>
</html>
