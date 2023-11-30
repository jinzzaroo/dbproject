<?php
include '../config.php';

session_start();

// 구매자로 로그인된 경우에만 접근 허용
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

// 현재 로그인한 구매자의 ID
$buyerID = $_SESSION['id'];

// Check if shopID is provided
if (!isset($_POST['shopID'])) {
    echo json_encode(['success' => false, 'message' => 'ShopID not provided.']);
    exit();
}

$shopID = $_POST['shopID'];

// Delete the shop from the favorites
$queryDeleteFavorite = "DELETE FROM Favorites WHERE buyer_id = '$buyerID' AND shop_id = '$shopID'";
$resultDeleteFavorite = $conn->query($queryDeleteFavorite);

if ($resultDeleteFavorite) {
    echo json_encode(['success' => true, 'message' => 'Shop deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting shop: ' . $conn->error]);
}

// Close the database connection
$conn->close();
?>
