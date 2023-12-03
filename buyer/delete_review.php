<?php
include '../config.php';

// Assuming you are using POST method to delete the review
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $reviewId = $_GET['reviewID'];

    // Perform the review deletion query
    $deleteQuery = "DELETE FROM Review WHERE id = '$reviewId'";
    $deleteResult = $conn->query($deleteQuery);

    if ($deleteResult) {
        // Send a success response
        echo json_encode(['success' => true]);
    } else {
        // Send an error response
        echo json_encode(['success' => false, 'message' => 'Error deleting the review']);
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
