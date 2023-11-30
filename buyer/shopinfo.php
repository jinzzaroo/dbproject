<?php
include '../config.php';

// Constants for pagination
$reviewsPerPage = 5;

// Fetch shop details based on shopID from the GET request
$shopID = isset($_GET['shopID']) ? $_GET['shopID'] : null;

if (!$shopID) {
    // Handle the case where shopID is not provided
    // You may redirect or show an error message
    echo "Invalid shop ID";
    exit();
}

// Fetch shop details, reviews, meat list, etc., based on $shopID
$queryShopInfo = "SELECT * FROM ButcherShop WHERE id = '$shopID'";
$resultShopInfo = $conn->query($queryShopInfo);

// Check if shop info is retrieved successfully
if (!$resultShopInfo) {
    echo "Error fetching shop information: " . $conn->error;
    exit();
}

$shopInfo = $resultShopInfo->fetch_assoc();

// Fetch total number of reviews
$queryTotalReviews = "SELECT COUNT(*) as total FROM Review WHERE shop_id = '$shopID'";
$resultTotalReviews = $conn->query($queryTotalReviews);

// Check if total reviews are retrieved successfully
if (!$resultTotalReviews) {
    echo "Error fetching total reviews: " . $conn->error;
    exit();
}

$totalReviews = $resultTotalReviews->fetch_assoc()['total'];

// Calculate total pages
$totalPages = ceil($totalReviews / $reviewsPerPage);

// Get current page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for the query
$offset = ($page - 1) * $reviewsPerPage;

// Fetch reviews for the current page
$queryReviews = "SELECT Review.*, Buyer.userid
                FROM Review
                JOIN Buyer ON Review.buyer_id = Buyer.id
                WHERE Review.shop_id = '$shopID'
                LIMIT $offset, $reviewsPerPage";
$resultReviews = $conn->query($queryReviews);

// Check if reviews are retrieved successfully
if (!$resultReviews) {
    echo "Error fetching reviews: " . $conn->error;
    exit();
}

// Fetch meats for the shop
$queryMeats = "SELECT * FROM Meat WHERE shop_id = '$shopID'";
$resultMeats = $conn->query($queryMeats);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Information</title>
    <style>
        body {
            display: flex;
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f4f4f4;
        }

        .dashboard-container {
            flex: 1;
            margin: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .navbar {
            background-color: #333;
            padding: 10px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }

        h2 {
            color: #333;
        }

        .flex-container {
            display: flex;
            justify-content: space-between;
            margin: 20px;
        }

        .shop-details,
        .meat-list,
        .reviews {
            flex: 0 0 30%; /* Adjust the width as needed */
            margin: 10px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .meat-list {
            background-color: #f9f9f9;
        }

        .meat-list-item,
        .review-item {
            margin-bottom: 15px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 5px;
            background-color: #fff;
        }

        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            justify-content: center;
        }

        .pagination li {
            margin-right: 5px;
        }

        .purchase-button,
        .write-review-button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .purchase-button:hover,
        .write-review-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="dashboard-container">

    <div class="navbar">
        <a href="buyer_dashboard.php">Buyer Home</a>
        <a href="../user/logout.php">Logout</a>
    </div>

    <h2>Shop Information</h2>

    <!-- Shop details -->
    <div class="flex-container">
        <div class="shop-details">
            <?php
            if ($shopInfo) {
                echo "<h3>{$shopInfo['shop_name']}</h3>";
                echo "<p>{$shopInfo['location']}</p>";
                // Add more details as needed
            } else {
                echo "Shop information not available.";
            }
            ?>
        </div>

        <!-- Meat list -->
        <div class="meat-list">
            <h3>Meat List</h3>
            <?php
            if ($resultMeats && $resultMeats->num_rows > 0) {
                while ($row = $resultMeats->fetch_assoc()) {
                    echo "<div class='meat-list-item'>";
                    echo "<p>Meat Name: {$row['meat_name']}</p>";
                    echo "<p>Price: {$row['price']}</p>";
                    // Add more meat details as needed
                    echo "</div>";
                }
            } else {
                echo "No meats available.";
            }
            ?>
        </div>
    </div>

    <!-- Reviews -->
    <div class="reviews">
        <h3>Reviews</h3>
        <?php
        if ($resultReviews && $resultReviews->num_rows > 0) {
            while ($row = $resultReviews->fetch_assoc()) {
                echo "<div class='review-item'>";
                echo "<p>User ID: {$row['userid']}</p>";
                echo "<p>Rating: {$row['rating']}</p>";
                echo "<p>Comment: {$row['comment']}</p>";
                // Add more review details as needed
                echo "</div>";
            }

            // Pagination
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
</div>

</body>
</html>