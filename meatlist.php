<!-- meatlist.php -->

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

    // 해당 정육점의 고기 목록 가져오기
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
    <title>고기 목록 - <?php echo $shopRow['shop_name']; ?></title>
</head>
<body>

<h2>고기 목록 - <?php echo $shopRow['shop_name']; ?></h2>

<!-- 고기 목록 표시 -->
<?php
if ($meatResult->num_rows > 0) {
    echo "<h3>고기 목록:</h3>";
    echo "<ul>";
    while ($meatRow = $meatResult->fetch_assoc()) {
        echo "<li>{$meatRow['meat_name']} - 가격: {$meatRow['price']}원 - 수량: {$meatRow['quantity']}";

        // 수정 버튼
        echo " <a href='edit_meat.php?meat_id={$meatRow['id']}'>수정</a>";

        // 삭제 버튼 (JavaScript를 사용하여 확인 메시지 표시)
        echo " <a href='#' onclick='confirmDelete({$meatRow['id']})'>삭제</a>";

        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "등록된 고기가 없습니다.";
}
?>

<!-- 고기 추가 페이지로 이동할 수 있는 링크 -->
<a href="add_meat.php?shop_id=<?php echo $shopID; ?>">고기 추가</a>

<!-- JavaScript로 삭제 확인 메시지 표시 -->
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
