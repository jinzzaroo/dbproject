<?php
include '../config.php';

session_start();

// 구매자로 로그인된 경우에만 접근 허용
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header('Location: ../user/login.php'); // 구매자가 아니면 로그인 페이지로 이동
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buyerID = $_SESSION['id'];
    $shopID = $_POST['shop_id'];

    // 이미 즐겨찾기에 추가된 경우를 확인
    $queryCheck = "SELECT * FROM Favorites WHERE buyer_id = '$buyerID' AND shop_id = '$shopID'";
    $resultCheck = $conn->query($queryCheck);

    if ($resultCheck->num_rows == 0) {
        // 즐겨찾기 추가
        $queryAddToFavorites = "INSERT INTO Favorites (buyer_id, shop_id) VALUES ('$buyerID', '$shopID')";
        $conn->query($queryAddToFavorites);
        header('Location: buyer_dashboard.php');
        exit();
    } else {
        echo "이미 즐겨찾기에 추가된 정육점입니다.";
    }
}
?>
