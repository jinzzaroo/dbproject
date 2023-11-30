<!-- index.php -->

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>메인 페이지</title>
</head>
<body>

<!-- Navigation Bar -->
<nav>
    <ul>
        <?php
        // Check if the user is a seller
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'seller') {
            echo '<li><a href="seller/seller_dashboard.php">판매자 홈</a></li>';
            echo '<li><a href="user/logout.php">로그아웃</a></li>';

        } elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'buyer') {
            echo '<li><a href="buyer/buyer_dashboard.php">구매자 홈</a></li>';
            echo '<li><a href="user/logout.php">로그아웃</a></li>';

        } else {
            // If not a seller or buyer (assuming not logged in)
            echo '<li><a href="user/register_buyer.php">구매자 회원가입</a></li>';
            echo '<li><a href="user/register_seller.php">판매자 회원가입</a></li>';
            echo '<li><a href="user/login.php">로그인</a></li>';
        }
        
        ?>
    </ul>
</nav>

<h2>환영합니다!</h2>

<!-- Rest of your content -->

</body>
</html>
