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
    <title>판매자 대시보드</title>
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <ul>
        <li><a href="../index.php">Home</a></li>
        <li><a href="../user/logout.php">Logout</a></li>
    </ul>
</nav>

<h2>판매자 대시보드</h2>

<!-- 기존 내용 -->

<!-- 정육점 등록 페이지로 이동할 수 있는 링크 -->
<a href="register_shop.php">정육점 등록</a>

<!-- 판매자가 등록한 정육점 목록 표시 -->
<?php
if ($result->num_rows > 0) {
    echo "<h3>등록한 정육점 목록:</h3>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        $shopID = $row['id'];
        echo "<li>{$row['shop_name']} - 위치: {$row['location']} ";
        echo "<a href='meat/meatlist.php?shop_id=$shopID'>고기 목록</a></li>";
    }
    echo "</ul>";
} else {
    echo "등록된 정육점이 없습니다.";
}

?>

<!-- 기존 내용 -->

</body>
</html>
