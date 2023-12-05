<?php
include '../config.php';

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
    exit();
}

$buyerID = $_SESSION['id'];

if (!isset($_POST['shopID'])) {
    echo json_encode(['success' => false, 'message' => 'ShopID not provided.']);
    exit();
}

$shopID = $_POST['shopID'];

$queryDeleteFavorite = "DELETE FROM Favorites WHERE buyer_id = '$buyerID' AND shop_id = '$shopID'";
$resultDeleteFavorite = $conn->query($queryDeleteFavorite);

if ($resultDeleteFavorite) {
    echo json_encode(['success' => true, 'message' => 'Shop deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error deleting shop: ' . $conn->error]);
}

$conn->close();
?>
