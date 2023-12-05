<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $reviewId = $_GET['reviewID'];

    $deleteQuery = "DELETE FROM Review WHERE id = '$reviewId'";
    $deleteResult = $conn->query($deleteQuery);

    if ($deleteResult) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting the review']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
