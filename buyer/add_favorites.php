<?php
include '../config.php';

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header('Location: ../user/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $buyerID = $_SESSION['id'];
    $shopID = $_POST['shop_id'];

    $queryCheck = "SELECT * FROM Favorites WHERE buyer_id = '$buyerID' AND shop_id = '$shopID'";
    $resultCheck = $conn->query($queryCheck);

    if ($resultCheck->num_rows == 0) {
        $queryAddToFavorites = "INSERT INTO Favorites (buyer_id, shop_id) VALUES ('$buyerID', '$shopID')";
        $conn->query($queryAddToFavorites);
        header('Location: buyer_dashboard.php');
        exit();
    } else {
        echo "이미 즐겨찾기에 추가된 정육점입니다.";
    }
}
?>
