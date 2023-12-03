<?php
include '../config.php';

session_start();

// 사용자가 로그인한 경우
$loggedInUserId = $_SESSION['id'];

// 페이지당 리뷰 수
$reviewsPerPage = 5;

// GET 요청에서 shopID를 기반으로 한 가게 정보, 리뷰, 고기 목록 등 가져오기
$shopID = isset($_GET['shopID']) ? $_GET['shopID'] : null;

if (!$shopID) {
    // shopID가 제공되지 않은 경우 처리
    echo "Invalid shop ID";
    exit();
}

// 가게 정보 가져오기
$queryShopInfo = "SELECT * FROM ButcherShop WHERE id = '$shopID'";
$resultShopInfo = $conn->query($queryShopInfo);

// 가게 정보가 성공적으로 검색되었는지 확인
if (!$resultShopInfo) {
    echo "Error fetching shop information: " . $conn->error;
    exit();
}

$shopInfo = $resultShopInfo->fetch_assoc();

// 총 리뷰 수 가져오기
$queryTotalReviews = "SELECT COUNT(*) as total FROM Review WHERE shop_id = '$shopID'";
$resultTotalReviews = $conn->query($queryTotalReviews);

// 총 리뷰가 성공적으로 검색되었는지 확인
if (!$resultTotalReviews) {
    echo "Error fetching total reviews: " . $conn->error;
    exit();
}

$totalReviews = $resultTotalReviews->fetch_assoc()['total'];

// 전체 페이지 계산
$totalPages = ceil($totalReviews / $reviewsPerPage);

// 현재 페이지 가져오기
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// 쿼리에 대한 오프셋 계산
$offset = ($page - 1) * $reviewsPerPage;

// 현재 페이지의 리뷰 가져오기
$queryReviews = "SELECT Review.*, Buyer.userid, Review.buyer_id as buyer_id, Review.id as review_id
                FROM Review
                JOIN Buyer ON Review.buyer_id = Buyer.id
                WHERE Review.shop_id = '$shopID'
                LIMIT $offset, $reviewsPerPage";

$resultReviews = $conn->query($queryReviews);

// 리뷰가 성공적으로 검색되었는지 확인
if (!$resultReviews) {
    echo "Error fetching reviews: " . $conn->error;
    exit();
}

// 가게의 고기 가져오기
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
                    echo '<li><a href="buyer_dashboard.php">Home</a></li>'; // 경로 수정
                    echo '<li><a href="../user/logout.php">Sign out</a></li>'; // 경로 수정
                    ?>
                </ul>
            </div>
        </nav>

        <!-- 가게 정보 -->
        <div class="flex-container">
            <div class="shop-details">
                <?php
                if ($shopInfo) {
                    echo "<h1>{$shopInfo['shop_name']}</h1>";
                    echo "<h3>{$shopInfo['location']}</h3>";
                    // 필요한 경우 추가 세부 정보 추가
                } else {
                    echo "Shop information not available.";
                }
                ?>
            </div>

            <!-- 고기 목록 -->
            <div class="meat-list">
                <h3>Meat List</h3>
                <?php
                if ($resultMeats && $resultMeats->num_rows > 0) {
                    while ($row = $resultMeats->fetch_assoc()) {
                        echo "<div class='meat-list-item'>";
                        echo "<p>Meat Name: {$row['meat_name']}</p>";
                        echo "<p>Price: {$row['price']}</p>";
                        echo "<p>Quantity: {$row['quantity']}</p>"; // 수량 추가
                        // 필요한 경우 더 많은 고기 세부 정보 추가
                        echo "</div>";
                    }
                } else {
                    echo "No meats available.";
                }
                ?>
            </div>

            <!-- 리뷰 -->
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


                        // Review deletion button
                        if ($row['buyer_id'] == $loggedInUserId) {
                            echo "<button onclick='deleteReview({$row['review_id']})'>Delete</button>";
                        }

                        echo "</div>";
                    }

                    // 페이지네이션
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
                // 리뷰를 삭제하는 JavaScript 함수
                function deleteReview(reviewId) {
                    if (confirm("Are you sure you want to delete this review?")) {
                        // Fetch API를 사용하여 delete_review.php에 비동기 요청 수행
                        fetch(`delete_review.php?reviewID=${reviewId}`, {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    // 필요한 경우 추가 헤더를 추가할 수 있음
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // 리뷰 삭제 후 페이지 다시 로드 또는 리뷰 업데이트
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