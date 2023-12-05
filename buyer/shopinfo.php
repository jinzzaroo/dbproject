<?php
include '../config.php';

session_start();

$loggedInUserId = $_SESSION['id'];
$reviewsPerPage = 5;
$shopID = isset($_GET['shopID']) ? $_GET['shopID'] : null;

if (!$shopID) {
    echo "Invalid shop ID";
    exit();
}

$queryShopInfo = "SELECT * FROM ButcherShop WHERE id = '$shopID'";
$resultShopInfo = $conn->query($queryShopInfo);

if (!$resultShopInfo) {
    echo "Error fetching shop information: " . $conn->error;
    exit();
}

$shopInfo = $resultShopInfo->fetch_assoc();

$queryTotalReviews = "SELECT COUNT(*) as total FROM Review WHERE shop_id = '$shopID'";
$resultTotalReviews = $conn->query($queryTotalReviews);

if (!$resultTotalReviews) {
    echo "Error fetching total reviews: " . $conn->error;
    exit();
}

$totalReviews = $resultTotalReviews->fetch_assoc()['total'];
$totalPages = ceil($totalReviews / $reviewsPerPage);
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $reviewsPerPage;
$queryReviews = "SELECT Review.*, Buyer.userid, Review.buyer_id as buyer_id, Review.id as review_id
                FROM Review
                JOIN Buyer ON Review.buyer_id = Buyer.id
                WHERE Review.shop_id = '$shopID'
                LIMIT $offset, $reviewsPerPage";

$resultReviews = $conn->query($queryReviews);

if (!$resultReviews) {
    echo "Error fetching reviews: " . $conn->error;
    exit();
}

$queryMeats = "SELECT * FROM Meat WHERE shop_id = '$shopID'";
$resultMeats = $conn->query($queryMeats);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style3.css">

    <title>Shop</title>
    <style>

    </style>
</head>

<body>

    <div class="dashboard-container">

        <nav>
            <div class="nav-left">
                <h1><a href="../index.php">MeatView.</a></h1>
            </div>
            <div class="nav-right">
                <ul>
                    <?php
                    echo '<li><a href="buyer_dashboard.php">Home</a></li>';
                    echo '<li><a href="../user/logout.php">Sign out</a></li>';
                    ?>
                </ul>
            </div>
        </nav>

        <div class="flex-container">
            <div class="shop-details">
                <?php
                if ($shopInfo) {
                    echo "<h1>{$shopInfo['shop_name']}</h1>";
                    echo "<h3>{$shopInfo['location']}</h3>";
                } else {
                    echo "Shop information not available.";
                }
                ?>
            </div>

            <div class="meat-list">
                <h3>Meat List</h3>
                <?php
                if ($resultMeats && $resultMeats->num_rows > 0) {
                    while ($row = $resultMeats->fetch_assoc()) {
                        echo "<div class='meat-list-item'>";
                        echo "<p>Meat Name: {$row['meat_name']}</p>";
                        echo "<p>Price: {$row['price']}</p>";
                        echo "<p>Quantity: {$row['quantity']}</p>";
                        echo "</div>";
                    }
                } else {
                    echo "No meats available.";
                }
                ?>
            </div>

            <div class="reviews">
                <h3>Reviews
                    <button class="write-review-button" onclick="location.href='write_review.php?shopID=<?php echo $shopID; ?>'">Write Review</button>
                </h3>
                <?php
                if ($resultReviews && $resultReviews->num_rows > 0) {
                    while ($row = $resultReviews->fetch_assoc()) {
                        echo "<div class='review-item'>";
                        echo "<p>User ID: {$row['userid']}</p>";
                        echo "<p>Rating: {$row['rating']}</p>";
                        echo "<p>Comment: {$row['comment']}</p>";
                        echo "<p>Review Date: {$row['review_date']}</p>";

                        if ($row['buyer_id'] == $loggedInUserId) {
                            echo "<button onclick='deleteReview({$row['review_id']})'>Delete</button>";
                        }

                        echo "</div>";
                    }

                    echo "<ul class='pagination'>";
                    for ($i = 1; $i <= $totalPages; $i++) {
                        echo "<li><a href='?shopID=$shopID&page=$i'>$i</a></li>";
                    }
                    echo "</ul>";
                } else {
                    echo "No reviews available.";
                }
                ?>
            </div>

            <script>
                document.addEventListener('click', function(event) {
                    if (event.target.classList.contains('delete-button')) {
                        var reviewId = event.target.getAttribute('data-review-id');
                        deleteReview(reviewId);
                    }
                });
                function deleteReview(reviewId) {
                    if (confirm("Are you sure you want to delete this review?")) {
                        fetch(`delete_review.php?reviewID=${reviewId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alert(data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                            });
                    }
                }
            </script>
        </div>
    </div>
</body>

</html>