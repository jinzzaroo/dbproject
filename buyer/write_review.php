<?php
include '../config.php';

session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'buyer') {
    header('Location: ../user/login.php');
    exit();
}

$buyerID = $_SESSION['id'];

$shopID = isset($_GET['shopID']) ? (int)$_GET['shopID'] : null;

if (!is_int($shopID) || $shopID <= 0) {
    echo "Invalid shop ID";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['rating']) ? $_POST['rating'] : null;

    if ($rating !== null) {
        $comment = isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : '';
        $queryInsertReview = "INSERT INTO Review (buyer_id, shop_id, rating, comment, review_date)
                            VALUES (?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($queryInsertReview);
        $stmt->bind_param("iiis", $buyerID, $shopID, $rating, $comment);

        if ($stmt->execute()) {
            $stmt->close();

            echo '<script>alert("Review submitted successfully."); window.location.href = "shopinfo.php?shopID=' . $shopID . '";</script>';
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Write Review</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .review-form {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .error-message {
            color: red;
            margin-top: -8px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>

<div class="review-form">
    <h2>Write a Review</h2>
    <form method="post" action="">
        <label for="rating">Rating:</label>
        <input type="number" name="rating" id="rating" min="1" max="5">
        <div class="error-message">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $rating === null) {
                echo "평점 : 1 ~ 5";
            }
            ?>
        </div>

        <label for="comment">Comment:</label>
        <textarea name="comment" id="comment" rows="4"></textarea>

        <button type="submit">Submit Review</button>
    </form>
</div>

</body>
</html>
